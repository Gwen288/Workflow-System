
<div class="px-2">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">My Approvals</h1>
            <p class="text-gray-500 mt-1"><?= count($requests) ?> requests awaiting your action</p>
        </div>
        <div class="flex space-x-3">
            <!-- Filters -->
            <button class="bg-white border text-gray-700 px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition flex items-center space-x-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span>All Types</span>
            </button>
            <button class="bg-white border text-gray-700 px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition flex items-center justify-between min-w-[140px]">
                <span>All Status</span>
                <svg class="w-4 h-4 ml-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <?php
        $now = new DateTime();
        $pendingCount = count($requests);
        $overdueCount = 0;
        $inProgressCount = 0;
        
        foreach ($requests as $req) {
            $submitted = new DateTime($req['submission_date']);
            $diff = $now->diff($submitted)->days;
            if ($diff >= 5) {
                $overdueCount++;
            } else {
                $inProgressCount++;
            }
        }
    ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pending Review -->
        <div class="bg-[#f0f7ff] border border-blue-100 rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-medium text-blue-600 mb-2">Pending Review</h3>
            <p class="text-3xl font-bold text-blue-900"><?= $pendingCount ?></p>
        </div>
        <!-- In Progress -->
        <div class="bg-[#fff9f0] border border-orange-100 rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-medium text-orange-600 mb-2">In Progress</h3>
            <p class="text-3xl font-bold text-orange-900"><?= $inProgressCount ?></p>
        </div>
        <!-- Overdue -->
        <div class="bg-[#fff0f0] border border-red-100 rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-medium text-red-600 mb-2">Overdue</h3>
            <p class="text-3xl font-bold text-red-900"><?= $overdueCount ?></p>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 uppercase text-[11px] font-bold text-gray-500 tracking-wider">
                    <th class="px-6 py-4">Request ID</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Requester</th>
                    <th class="px-6 py-4">Current Stage</th>
                    <th class="px-6 py-4">Days Open</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-gray-500">No requests awaiting your approval</span>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($requests as $req): 
                        $submitted = new DateTime($req['submission_date']);
                        $daysOpen = $now->diff($submitted)->days;
                        
                        $typePrefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $req['workflow_name']), 0, 3));
                        $reqCode = $typePrefix . '-' . date('Y') . '-' . str_pad($req['request_id'], 3, '0', STR_PAD_LEFT);
                        
                        $isOverdue = $daysOpen >= 5;
                        $statusClass = $isOverdue ? 'bg-red-50 text-red-600 border-red-100' : 'bg-orange-50 text-orange-600 border-orange-100';
                        $statusText = $isOverdue ? 'Overdue' : 'In Progress';
                        
                        $daysColorClass = 'text-green-600';
                        if ($daysOpen >= 5) {
                            $daysColorClass = 'text-red-600';
                        } elseif ($daysOpen >= 3) {
                            $daysColorClass = 'text-orange-600';
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
                            <div class="flex flex-col">
                                <span class="text-gray-900 font-semibold text-sm"><?= htmlspecialchars($req['submitter_name']) ?></span>
                                <span class="text-gray-500 text-xs mt-0.5"><?= date('n/j/Y', strtotime($req['submission_date'])) ?></span>
                            </div>
                        </td>
                        <!-- Current Stage -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-gray-700 font-medium text-sm"><?= htmlspecialchars(auth_user()['role']) ?> Review</span>
                            </div>
                        </td>
                        <!-- Days Open -->
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-1.5 <?= $daysColorClass ?> font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span><?= $daysOpen ?> days</span>
                            </div>
                        </td>
                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $statusClass ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                        <!-- Action -->
                        <td class="px-6 py-4">
                            <a href="<?= url('/requests/' . $req['request_id']) ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-1.5 px-4 rounded-lg transition-colors shadow-sm inline-block">
                                Review
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
