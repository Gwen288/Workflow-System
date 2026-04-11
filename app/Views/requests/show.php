<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Request Details #<?= $request['request_id'] ?></h1>
        <?php $backUrl = auth_user()['role'] === 'Student' ? url('/dashboard') : url('/approvals'); ?>
        <a href="<?= $backUrl ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow transition font-medium">
            &larr; Back to Dashboard
        </a>
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
        </div>

        <!-- Render Actual Form Data -->
        <?php if(!empty($request['metadata_json'])): 
            $meta = json_decode($request['metadata_json'], true); 
            if(is_array($meta)): ?>
            <div id="form-details" class="mt-6 border-t pt-4">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Form Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-100 shadow-inner">
                    <?php foreach($meta as $key => $val): ?>
                        <?php if(!empty($val) && $key !== 'workflow_type'): ?>
                        <div>
                            <span class="block text-xs font-bold text-gray-500 uppercase tracking-wide"><?= htmlspecialchars(str_replace('_', ' ', $key)) ?></span>
                            <span class="block text-sm text-gray-900 font-medium mt-0.5"><?= is_array($val) ? implode(', ', array_map('htmlspecialchars', $val)) : nl2br(htmlspecialchars($val)) ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php 
            endif;
        endif; ?>

        <?php if ($request['current_approver'] == auth() && in_array($request['status'], ['Pending', 'Escalated'])): ?>
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
                <select id="escalate-role" class="shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-orange-500 ml-4">
                    <option value="">-- Role --</option>
                    <?php if (auth_user()['role'] === 'Registry'): ?>
                        <option value="Finance Officer">Finance</option>
                        <option value="Library">Library</option>
                    <?php else: ?>
                        <option value="HOD">HOD</option>
                        <option value="Admin">Admin</option>
                        <option value="Finance Officer">Finance</option>
                        <option value="Registry">Registry</option>
                        <option value="Library">Library</option>
                    <?php endif; ?>
                </select>
                <button onclick="handleAction('escalate')" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded transition shadow">
                    Escalate
                </button>
            </div>
            <p id="action-msg" class="text-sm mt-3 font-semibold"></p>
        </div>
        
        <script>
        function handleAction(type) {
            const comment = document.getElementById('action-comment').value;
            let url = '<?= url('/requests/' . $request['request_id']) ?>/' + type;
            let bodyData = { comment: comment };
            
            if (type === 'escalate') {
                const role = document.getElementById('escalate-role').value;
                if (!role) {
                    alert('Please select a role to escalate to.');
                    return;
                }
                bodyData.target_role = role;
            } else if (type === 'reject' && !comment) {
                alert('Please provide a comment for rejecting.');
                return;
            }

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
                    document.getElementById('action-msg').innerHTML = '<span class="text-green-600">Successfully updated. Refreshing...</span>';
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    document.getElementById('action-msg').innerHTML = '<span class="text-red-600">Error: ' + (data.error || 'Failed') + '</span>';
                }
            }).catch(e => {
                document.getElementById('action-msg').innerHTML = '<span class="text-red-600">Network error.</span>';
            });
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
