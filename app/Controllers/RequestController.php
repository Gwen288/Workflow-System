<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Request;
use App\Models\Workflow;
use App\Models\AuditLog;
use App\Models\ApprovalStep;
use App\Services\AIService;

class RequestController extends Controller {
    public function __construct() {
        if (!auth()) {
            $this->redirect('/login');
        }
    }

    public function index() {
        $requestModel = new Request();
        $requests = $requestModel->getWithDetails();
        $this->view('requests/index', ['requests' => $requests]);
    }

    public function search() {
        header('Content-Type: application/json');
        $query = $_GET['q'] ?? '';
        $listType = $_GET['list'] ?? 'approvals'; // approvals, myRequests, all
        $filterType = $_GET['filterType'] ?? '';

        
        $requestModel = new Request();
        if ($listType === 'myRequests') {
            $requests = $requestModel->getSubmittedByUser(auth());
        } elseif ($listType === 'all' && auth_user()['role'] === 'Admin') {
            $requests = $requestModel->getWithDetails();
        } else {
            $requests = $requestModel->getPendingForUser(auth());
        }

        // Apply filtering natively in PHP
        if (!empty($filterType)) {
            $requests = array_filter($requests, function($req) use ($filterType) {
                // Universal filter: check both status and workflow name
                return ($req['status'] ?? '') === $filterType || ($req['workflow_name'] ?? '') === $filterType;
            });
        }

        if (!empty($query)) {
            $query = strtolower($query);
            $requests = array_filter($requests, function($req) use ($query) {
                // Check code, workflow_name, submitter_name, status
                $code = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $req['workflow_name']), 0, 3)) . '-' . date('Y', strtotime($req['submission_date'])) . '-' . str_pad($req['request_id'], 3, '0', STR_PAD_LEFT);
                
                return str_contains(strtolower($code), $query) ||
                       str_contains(strtolower($req['workflow_name']), $query) ||
                       str_contains(strtolower($req['submitter_name']), $query) ||
                       str_contains(strtolower($req['status']), $query);
            });
        }

        
        echo json_encode(array_values($requests));
        exit;
    }

    public function approvals() {
        if (auth_user()['role'] === 'Student') {
            $this->redirect('/my-requests');
        }
        $requestModel = new Request();
        $requests = $requestModel->getPendingForUser(auth());
        $this->view('requests/approvals', ['requests' => $requests]);
    }

    public function myRequests() {
        $requestModel = new Request();
        $requests = $requestModel->getSubmittedByUser(auth());
        // For simplicity reusing the same table structural design from approvals
        $this->view('requests/approvals', ['requests' => $requests, 'isMyRequests' => true]);
    }


    public function create() {
        $workflowModel = new Workflow();
        $allWorkflows = $workflowModel->all();
        $userRole = auth_user()['role'];

        $workflows = array_filter($allWorkflows, function($w) use ($userRole) {
            if ($userRole === 'Student') {
                return !in_array($w['name'], ['Budget', 'Procurement']);
            } elseif ($userRole === 'HOD') {
                return in_array($w['name'], ['Budget', 'Procurement']);
            }
            return true;
        });

        $requestModel = new Request();
        $approvedBudgets = $requestModel->getApprovedBudgetsForUser(auth());

        $this->view('requests/create', [
            'workflows' => array_values($workflows),
            'approvedBudgets' => $approvedBudgets
        ]);
    }

    public function store() {
        $aiService = new AIService();
        $workflowId = $_POST['workflow_type'] ?? null;
        $priority = $_POST['priority_level'] ?? 'Medium';
        
        // Handle metadata
        $metadata = $_POST['metadata'] ?? [];
        $metadataJson = !empty($metadata) ? json_encode($metadata) : null;
        
        // Handle file upload
        $attachmentPath = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', basename($_FILES['attachment']['name']));
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
                    $attachmentPath = '/uploads/' . $filename;
                }
            }
        }

        $nextApprover = $aiService->suggestNextApprover($workflowId, $priority);

        $requestModel = new Request();
        $requestId = $requestModel->create([
            'workflow_type' => $workflowId,
            'submitted_by' => auth(),
            'status' => 'Pending',
            'current_approver' => $nextApprover ? $nextApprover['user_id'] : null,
            'priority_level' => $priority,
            'metadata' => $metadataJson,
            'attachment_path' => $attachmentPath
        ]);

        $auditLogModel = new AuditLog();
        $auditLogModel->create([
            'request_id' => $requestId,
            'action' => 'Created',
            'performed_by' => auth(),
            'comment' => 'Request submitted'
        ]);

        $this->redirect('/dashboard');
    }

    public function show($id) {
        $requestModel = new Request();
        $request = $requestModel->findWithDetails($id);
        if (!$request) {
            http_response_code(404);
            echo "Request not found";
            return;
        }

        // Logic to clear "Needs Attention" on read
        if ($request['status'] === 'Rejected' && $request['submitted_by'] == auth() && ($request['is_acknowledged'] ?? 0) == 0) {
            $requestModel->update($id, ['is_acknowledged' => 1]);
        }

        $auditLogModel = new AuditLog();
        $logs = $auditLogModel->getLogsForRequest($id);
        
        // Fetch linked budget if it's a procurement request
        $linkedBudget = null;
        if (!empty($request['metadata'])) {
            $meta = json_decode($request['metadata'], true);
            if (isset($meta['budget_reference_id'])) {
                $linkedBudget = $requestModel->findWithDetails($meta['budget_reference_id']);
            }
        }

        $this->view('requests/show', [
            'request' => $request,
            'logs' => $logs,
            'linkedBudget' => $linkedBudget
        ]);
    }

    public function document($id) {
        $requestModel = new Request();
        $request = $requestModel->findWithDetails($id);

        if (!$request) {
            die("Request not found");
        }

        // Logic to clear "Needs Attention" on read (synchronized with show method)
        if ($request['status'] === 'Rejected' && $request['submitted_by'] == auth() && ($request['is_acknowledged'] ?? 0) == 0) {
            $requestModel->update($id, ['is_acknowledged' => 1]);
        }

        $this->view('requests/document', [
            'request' => $request
        ]);
    }

    public function approve($id) {
        $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
        $comment = $data['comment'] ?? '';
        $this->processDecision($id, 'Approved', $comment);
    }

    public function reject($id) {
        $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
        $comment = $data['comment'] ?? '';
        if (empty(trim($comment))) {
            return $this->json(['error' => 'A rejection must include a comment.'], 400);
        }
        $this->processDecision($id, 'Rejected', $comment);
    }

    public function processDecision($id, $decision, $comment) {
        $requestModel = new Request();
        $request = $requestModel->find($id);

        if ($request['current_approver'] != auth()) {
            return $this->json(['error' => 'Not authorized'], 403);
        }

        $status = $decision === 'Approved' ? 'Approved' : 'Rejected';

        // Add to approval steps
        $approvalModel = new ApprovalStep();
        $approvalModel->create([
            'request_id' => $id,
            'approver_role' => auth_user()['role'],
            'status' => $decision,
            'decision' => $decision,
            'comment' => $comment
        ]);

        // Next phase logic can be handled by AI Service or hardcoded flow
        if ($request['status'] === 'Escalated') {
            if (auth_user()['role'] === 'CFO' || in_array($request['workflow_type'], [1, 7])) {
                // Finalize it directly, original submitter (e.g. HOD) will see the final status natively
                $requestModel->update($id, [
                    'status' => $decision === 'Approved' ? 'Approved' : 'Rejected', 
                    'current_approver' => null
                ]);
            } else {
                $userModel = new \App\Models\User();
                $registryUsers = $userModel->findByRole('Registry');
                $registryId = !empty($registryUsers) ? $registryUsers[0]['user_id'] : null;

                if ($registryId) {
                    // Bounce back to Registry
                    $requestModel->update($id, [
                        'current_approver' => $registryId,
                        'status' => 'Pending'
                    ]);
                } else {
                    // Fallback if no registry exists
                    $requestModel->update($id, ['status' => $decision === 'Approved' ? 'Approved' : 'Rejected', 'current_approver' => null]);
                }
            }
        } else {
            $aiService = new AIService();
            if ($decision === 'Approved') {
                $pastSteps = count($approvalModel->getStepsForRequest($id));
                $nextApprover = $aiService->suggestNextApprover($request['workflow_type'], $request['priority_level'], $pastSteps + 1);
                
                if ($nextApprover) {
                    // Route to next approver
                    $requestModel->update($id, [
                        'current_approver' => $nextApprover['user_id'],
                        'status' => 'Pending'
                    ]);
                } else {
                    // Final approval
                    $requestModel->update($id, ['status' => 'Approved', 'current_approver' => null]);
                }
            } else {
                 $requestModel->update($id, ['status' => 'Rejected', 'current_approver' => null]);
            }
        }

        // Audit Log
        $auditLogModel = new AuditLog();
        $auditLogModel->create([
            'request_id' => $id,
            'action' => $decision,
            'performed_by' => auth(),
            'comment' => $comment
        ]);

        // Check for AJAX response
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            return $this->json(['success' => true, 'status' => $decision]);
        }
        $this->redirect('/dashboard');
    }

    public function escalate($id) {
        $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
        $targetRole = $data['target_role'] ?? '';
        $comment = $data['comment'] ?? 'Escalated to ' . $targetRole;

        $requestModel = new Request();
        $request = $requestModel->find($id);

        if ($request['current_approver'] != auth()) {
            return $this->json(['error' => 'Not authorized'], 403);
        }

        // Find user by role
        $userModel = new \App\Models\User();
        $candidates = $userModel->findByRole($targetRole);
        $nextApprover = count($candidates) > 0 ? $candidates[0]['user_id'] : null;

        if (!$nextApprover) {
            return $this->json(['error' => 'Role not found or no users available'], 400);
        }

        // Add step record
        $approvalModel = new ApprovalStep();
        $approvalModel->create([
            'request_id' => $id,
            'approver_role' => auth_user()['role'],
            'status' => 'Pending',
            'decision' => 'Escalated',
            'comment' => $comment
        ]);

        $requestModel->update($id, [
            'status' => 'Escalated',
            'current_approver' => $nextApprover
        ]);

        $auditLogModel = new AuditLog();
        $auditLogModel->create([
            'request_id' => $id,
            'action' => 'Escalated',
            'performed_by' => auth(),
            'comment' => $comment
        ]);

        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            return $this->json(['success' => true, 'status' => 'Escalated']);
        }
        $this->redirect('/dashboard');
    }
}
