<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Request;
use App\Services\AIService;

class DashboardController extends Controller {
    public function __construct() {
        if (!auth()) {
            $this->redirect('/login');
        }
    }

    public function index() {
        $requestModel = new Request();
        $user = auth_user();
        
        $pendingRequests = [];
        $myRequests = [];
        $allRequests = [];
        
        if ($user['role'] === 'Admin') {
            $allRequests = $requestModel->getWithDetails();
        } else {
            $pendingRequests = $requestModel->getPendingForUser($user['user_id']);
            $myRequests = $requestModel->getSubmittedByUser($user['user_id']);
        }
        
        $aiService = new AIService();
        $insights = $aiService->generateDashboardInsights();

        $this->view('dashboard/index', [
            'pendingRequests' => $pendingRequests,
            'myRequests' => $myRequests,
            'allRequests' => $allRequests,
            'insights' => $insights
        ]);
    }

    public function analytics() {
        if (!is_admin()) {
            $this->redirect('/dashboard');
        }
        $aiService = new AIService();
        $this->view('dashboard/analytics', [
            'insights' => $aiService->generateDashboardInsights()
        ]);
    }
}
