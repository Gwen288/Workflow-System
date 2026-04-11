<div class="px-2">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Audit Trail & Search</h1>
        <p class="text-gray-500 mt-1">Comprehensive log of all workflow requests and actions</p>
    </div>

    <!-- Search and Filters Section -->
    <div class="mb-6 flex space-x-4">
        <form method="GET" action="/audit" class="flex-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="q" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search requests..." class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                <button type="submit" class="hidden"></button>
            </div>
        </form>
        <button class="bg-white border border-gray-200 text-gray-700 px-5 py-3 rounded-lg shadow-sm hover:bg-gray-50 transition flex items-center space-x-2 whitespace-nowrap">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            <span class="font-medium">Advanced Filters</span>
        </button>
    </div>

    <!-- Results Banner -->
    <div class="bg-[#f0f7ff] border-t border-b border-blue-100 p-4 rounded-xl mb-6 shadow-sm">
        <span class="text-blue-800 font-medium"><?= count($requests) ?> requests found</span>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 uppercase text-[11px] font-bold text-gray-500 tracking-wider">
                    <th class="px-6 py-4">Request ID</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Requester</th>
                    <th class="px-6 py-4">Date Submitted</th>
                    <th class="px-6 py-4">Current Status</th>
                    <th class="px-6 py-4">Days in Process</th>
                    <th class="px-6 py-4">Last Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-gray-500">No requests found matching your criteria</span>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php 
                    $now = new DateTime();
                    foreach ($requests as $req): 
                        $submitted = new DateTime($req['submission_date']);
                        $daysOpen = $now->diff($submitted)->days;
                        
                        $typePrefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $req['workflow_name']), 0, 3));
                        $reqCode = $typePrefix . '-' . date('Y') . '-' . str_pad($req['request_id'], 3, '0', STR_PAD_LEFT);
                        
                        // Status Logic
                        $dbStatus = $req['status'];
                        $isOverdue = $daysOpen >= 5 && in_array($dbStatus, ['Pending', 'Escalated']);
                        $statusClass = '';
                        $statusText = $dbStatus;
                        
                        if ($isOverdue) {
                            $statusClass = 'bg-red-50 text-red-600 border-red-100';
                            $statusText = 'Overdue';
                        } elseif (in_array($dbStatus, ['Pending', 'Escalated'])) {
                            $statusClass = 'bg-orange-50 text-orange-600 border-orange-100';
                            $statusText = 'In Progress';
                        } elseif ($dbStatus === 'Approved') {
                            $statusClass = 'bg-green-50 text-green-600 border-green-100';
                        } elseif ($dbStatus === 'Rejected') {
                            $statusClass = 'bg-gray-100 text-gray-600 border-gray-200';
                        }

                        // Last action formatting
                        $lastAction = "No actions yet";
                        if (!empty($req['last_action_type']) || !empty($req['last_action_comment'])) {
                            if (!empty($req['last_action_comment']) && $req['last_action_type'] === 'Approved') {
                                $lastAction = htmlspecialchars($req['last_action_comment']);
                            } else {
                                $lastAction = htmlspecialchars($req['last_action_type'] ?? '') . ' ' . htmlspecialchars($req['last_action_comment'] ?? '');
                            }
                            
                            // fallback format based on string matching mockup: "Approved by Department", "Under Review", "Pending Finance Check"
                            // A simple output format for visual mocking:
                            if ($req['last_action_type'] === 'Created') {
                                $lastAction = 'Under Review';
                            }
                        }
                    ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <!-- Request ID -->
                        <td class="px-6 py-4">
                            <span class="font-semibold text-blue-600"><?= $reqCode ?></span>
                        </td>
                        <!-- Type -->
                        <td class="px-6 py-4">
                            <span class="text-gray-700 font-medium"><?= htmlspecialchars($req['workflow_name']) ?></span>
                        </td>
                        <!-- Requester -->
                        <td class="px-6 py-4">
                            <span class="text-gray-900 font-semibold text-sm"><?= htmlspecialchars($req['submitter_name']) ?></span>
                        </td>
                        <!-- Date Submitted -->
                        <td class="px-6 py-4">
                            <span class="text-gray-600 text-sm"><?= date('M j, Y', strtotime($req['submission_date'])) ?></span>
                        </td>
                        <!-- Current Status -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $statusClass ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                        <!-- Days in Process -->
                        <td class="px-6 py-4">
                            <span class="text-gray-700 text-sm"><?= $daysOpen ?> days</span>
                        </td>
                        <!-- Last Action -->
                        <td class="px-6 py-4">
                            <span class="text-gray-500 text-xs truncate max-w-[150px] inline-block" title="<?= $lastAction ?>">
                                <?= $lastAction ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
