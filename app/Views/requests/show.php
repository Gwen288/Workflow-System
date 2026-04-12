<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Request Details #<?= $request['request_id'] ?></h1>
        <div class="flex space-x-3">
            <?php if(auth_user()['role'] === 'Student' && in_array($request['status'], ['Approved', 'Rejected'])): ?>
            <a href="javascript:void(0)" onclick="alert('Downloading Document PDF...')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded shadow transition font-medium whitespace-nowrap flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF
            </a>
            <?php endif; ?>
            <a href="<?= url('/requests/' . $request['request_id'] . '/document') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition font-medium whitespace-nowrap">
                View Full Document
            </a>
            <?php $backUrl = auth_user()['role'] === 'Student' ? url('/dashboard') : url('/approvals'); ?>
            <a href="<?= $backUrl ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow transition font-medium whitespace-nowrap">
                &larr; Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Request Info & Approval actions -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Information</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-semibold text-gray-500">Status</p>
                <p class="font-medium text-lg">
                    <?php if($request['status'] === 'Approved'): ?>
                       <span class="text-green-600">✓ Approved</span>
                    <?php elseif($request['status'] === 'Rejected'): ?>
                       <span class="text-red-600">✗ Rejected</span>
                    <?php else: ?>
                       <span class="text-yellow-600">⏳ <?= $request['status'] ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500">Priority</p>
                <p class="font-medium"><?= $request['priority_level'] ?></p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500">Submitted On</p>
                <p><?= date('M j, Y H:i', strtotime($request['submission_date'])) ?></p>
            </div>
            <?php if (isset($linkedBudget) && $linkedBudget): 
                $bMeta = json_decode($linkedBudget['metadata'], true);
            ?>
            <div class="col-span-2 mt-4 bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex items-start space-x-4">
                <div class="bg-blue-600 text-white p-2 rounded-lg shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="text-blue-900 font-bold text-sm">Funded by Approved Budget #<?= $linkedBudget['request_id'] ?></h4>
                    <p class="text-xs text-blue-700 mt-0.5">This procurement is cross-validated against the following approved plan:</p>
                    <div class="mt-3 grid grid-cols-2 gap-x-6 gap-y-2">
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-blue-400 tracking-wider">Approved Item 1</span>
                            <span class="text-sm font-bold text-gray-800 italic">"<?= htmlspecialchars($bMeta['budget_item_1'] ?? 'N/A') ?>"</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-blue-400 tracking-wider">Approved Item 2</span>
                            <span class="text-sm font-bold text-gray-800 italic">"<?= htmlspecialchars($bMeta['budget_item_2'] ?? 'N/A') ?>"</span>
                        </div>
                        <div class="mt-2">
                            <span class="block text-[10px] uppercase font-bold text-blue-400 tracking-wider">Allocated Amount</span>
                            <span class="text-sm font-black text-gray-900">GHS <?= number_format(($bMeta['budget_amount'] ?? 0), 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>



        <?php if ($request['current_approver'] == auth() && in_array($request['status'], ['Pending', 'Escalated'])): ?>
        <?php
            $feeAmount = 0;
            $budgetAmount = 0;
            $procurementAmount = 0;
            $linkedBudgetAmount = 0;
            if (!empty($request['metadata'])) {
                $meta = json_decode($request['metadata'], true);
                
                // Fee Waiver Keys
                if (isset($meta['fee_amount'])) {
                    $feeAmount = floatval($meta['fee_amount']);
                } elseif (isset($meta['fee_requested_adjustment'])) {
                    $feeAmount = floatval($meta['fee_requested_adjustment']);
                }
                
                // Budget Key
                if (isset($meta['budget_amount'])) {
                    $budgetAmount = floatval($meta['budget_amount']);
                }
                
                // Procurement Key
                if (isset($meta['procurement_cost'])) {
                    $procurementAmount = floatval($meta['procurement_cost']);
                }

                // Linked Budget Amount
                if (isset($linkedBudget)) {
                    $lbMeta = json_decode($linkedBudget['metadata'], true);
                    $linkedBudgetAmount = floatval($lbMeta['budget_amount'] ?? 0);
                }
            }
        ?>
        <div class="mt-8 border-t pt-4" id="action-panel">
            <h3 class="text-lg font-bold text-blue-800 mb-4">Approval Decision</h3>
            <div class="flex space-x-2">
                <input type="text" id="action-comment" placeholder="Add a comment before action (optional)" class="flex-1 shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                <button onclick="handleAction('approve')" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition shadow">
                    Approve
                </button>
                <button onclick="handleAction('reject')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition shadow">
                    Reject
                </button>
                
                <!-- Escalate Setup -->
                <?php if (auth_user()['role'] !== 'CFO'): ?>
                <select id="escalate-role" class="shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-orange-500 ml-4">
                    <option value="">-- Role --</option>
                    <?php if (auth_user()['role'] === 'Registry'): ?>
                        <option value="Finance Officer">Finance</option>
                        <option value="Library">Library</option>
                    <?php elseif (auth_user()['role'] === 'Finance Officer'): ?>
                        <option value="CFO">CFO</option>
                    <?php else: ?>
                        <option value="HOD">HOD</option>
                        <option value="Admin">Admin</option>
                        <option value="Finance Officer">Finance</option>
                        <option value="Registry">Registry</option>
                        <option value="Library">Library</option>
                        <option value="CFO">CFO</option>
                    <?php endif; ?>
                </select>
                <button onclick="handleAction('escalate')" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded transition shadow">
                    Escalate
                </button>
                <?php endif; ?>
            </div>
            <p id="action-msg" class="text-sm mt-3 font-semibold"></p>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        function processFetch(url, bodyData) {
            document.getElementById('action-msg').innerHTML = '<span class="text-blue-500">Processing...</span>';
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(bodyData)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Action completed successfully.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire('Error', (data.error || 'Failed to process request.'), 'error');
                    document.getElementById('action-msg').innerHTML = '';
                }
            }).catch(e => {
                Swal.fire('Network Error', 'Could not connect to the server.', 'error');
                document.getElementById('action-msg').innerHTML = '';
            });
        }

        function handleAction(type) {
            const comment = document.getElementById('action-comment').value;
            let url = '<?= url('/requests/' . $request['request_id']) ?>/' + type;
            let bodyData = { comment: comment };
            
            const reqAmount = <?= $feeAmount ?>;
            const budgetAmount = <?= $budgetAmount ?>;
            const procurementAmount = <?= $procurementAmount ?>;
            const linkedBudgetAmount = <?= $linkedBudgetAmount ?>;
            const workflowType = <?= $request['workflow_type'] ?>;
            const userRole = '<?= auth_user()['role'] ?>';
            
            if (type === 'escalate') {
                const role = document.getElementById('escalate-role').value;
                if (!role) {
                    Swal.fire('Warning', 'Please select a role to escalate to.', 'warning');
                    return;
                }
                bodyData.target_role = role;
            } else if (type === 'reject' && !comment) {
                Swal.fire('Required', 'Please provide a comment for rejecting.', 'info');
                return;
            }

            // Budget Overrun Alert for Finance Officer
            if (userRole === 'Finance Officer' && workflowType == 8 && linkedBudgetAmount > 0 && procurementAmount > linkedBudgetAmount && (type === 'approve' || type === 'reject')) {
                Swal.fire({
                    title: 'Caution: Budget Overrun',
                    text: "This procurement exceeds its approved budget. This request should generally be escalated to the CFO for final authorization. Proceed anyway?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, proceed anyway',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        processFetch(url, bodyData);
                    }
                });
                return;
            }

            if (userRole === 'Finance Officer' && (reqAmount >= 10000 || budgetAmount >= 10000) && (type === 'approve' || type === 'reject')) {
                Swal.fire({
                    title: 'Caution: High Value',
                    text: "Amounts of GHS 10,000 or above are generally overseen by the CFO. Are you sure you want to process this yourself instead of escalating?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed anyway',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        processFetch(url, bodyData);
                    }
                });
                return; // wait for promise
            }
            
            processFetch(url, bodyData);
        }
        </script>
        <?php endif; ?>
    </div>

    <!-- Audit log -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Audit Trail</h2>
        <ul class="space-y-4">
            <?php foreach($logs as $log): ?>
                <li class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center border-2 border-blue-200">
                            <span class="text-sm font-bold text-blue-600">
                                <?= substr($log['action'], 0, 1) ?>
                            </span>
                        </span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900 border-b pb-1">
                            <?= $log['user_name'] ?> 
                            <span class="text-xs text-gray-400 pl-2"><?= date('M j, H:i', strtotime($log['timestamp'])) ?></span>
                        </p>
                        <p class="text-sm text-gray-700 mt-1">
                            <span class="font-semibold <?php
                                if ($log['action'] === 'Approved') echo 'text-green-600';
                                elseif ($log['action'] === 'Rejected') echo 'text-red-600';
                                else echo 'text-blue-600';
                            ?>"><?= $log['action'] ?></span>:
                            <?= htmlspecialchars($log['comment'] ?: 'No comment provided') ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
