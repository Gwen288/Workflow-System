
<div class="px-2">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight"><?= isset($isMyRequests) ? 'My Requests' : 'My Approvals' ?></h1>
            <p class="text-gray-500 mt-1"><?= count($requests) ?> <?= isset($isMyRequests) ? 'requests currently being tracked' : 'requests awaiting your action' ?></p>
        </div>
        <div class="flex space-x-3 w-full md:w-auto mt-4 md:mt-0">
            <!-- Search -->
            <div class="relative w-full md:w-64">
                <input type="text" id="ajax-search" placeholder="Search requests..." class="w-full bg-white border border-gray-200 text-gray-700 px-4 py-2 pl-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <!-- Filters -->
            <div class="relative">
                <select id="ajax-filter" class="appearance-none bg-white border border-gray-200 text-gray-700 font-medium py-2 pl-4 pr-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Escalated">Escalated</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <?php
        $now = new DateTime();
        $pendingCount = isset($isMyRequests) ? 0 : count($requests);
        $overdueCount = 0;
        $inProgressCount = 0;
        
        foreach ($requests as $req) {
            if (isset($isMyRequests)) {
                if ($req['status'] === 'Rejected') {
                    $overdueCount++;
                } elseif ($req['status'] === 'Approved') {
                    $pendingCount++;
                } else {
                    $inProgressCount++;
                }
            } else {
                $submitted = new DateTime($req['submission_date']);
                $diff = $now->diff($submitted)->days;
                if ($diff >= 5) {
                    $overdueCount++;
                } else {
                    $inProgressCount++;
                }
            }
        }
    ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pending Review / Completed -->
        <div class="bg-[#f0f7ff] border border-blue-100 rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-medium text-blue-600 mb-2"><?= isset($isMyRequests) ? 'Completed' : 'Pending Review' ?></h3>
            <p class="text-3xl font-bold text-blue-900"><?= $pendingCount ?></p>
        </div>
        <!-- In Progress -->
        <div class="bg-[#fff9f0] border border-orange-100 rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-medium text-orange-600 mb-2">In Progress</h3>
            <p class="text-3xl font-bold text-orange-900"><?= $inProgressCount ?></p>
        </div>
        <!-- Overdue -->
        <div class="bg-[#fff0f0] border border-red-100 rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-medium text-red-600 mb-2"><?= isset($isMyRequests) ? 'Needs Attention' : 'Overdue' ?></h3>
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
            <tbody id="request-table-body" class="divide-y divide-gray-100">
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-gray-500"><?= isset($isMyRequests) ? 'You have not submitted any tracking requests' : 'No requests awaiting your approval' ?></span>
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
                        if (isset($isMyRequests)) {
                            if ($req['status'] === 'Approved') {
                                $statusClass = 'bg-green-50 text-green-600 border-green-100';
                                $statusText = 'Approved';
                            } elseif ($req['status'] === 'Rejected') {
                                $statusClass = 'bg-red-50 text-red-600 border-red-100';
                                $statusText = 'Rejected';
                            } elseif ($req['status'] === 'Escalated') {
                                $statusClass = 'bg-purple-50 text-purple-600 border-purple-100';
                                $statusText = 'Escalated';
                            } else {
                                $statusClass = 'bg-amber-50 text-amber-600 border-amber-100';
                                $statusText = 'Pending';
                            }
                        } else {
                            $statusClass = $isOverdue ? 'bg-red-50 text-red-600 border-red-100' : 'bg-orange-50 text-orange-600 border-orange-100';
                            $statusText = $isOverdue ? 'Overdue' : 'In Progress';
                        }
                        
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
                                <?php 
                                    $stageLabel = auth_user()['role'] . ' Review';
                                    if (isset($isMyRequests)) {
                                        $stageLabel = ($req['status'] === 'Approved') ? 'Completed' : ($req['approver_name'] ? htmlspecialchars($req['approver_name']) . ' Reviewing' : 'System Processing');
                                    }
                                ?>
                                <span class="text-gray-700 font-medium text-sm"><?= $stageLabel ?></span>
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
                            <div class="flex space-x-2">
                                <?php if(isset($isMyRequests)): ?>
                                <a href="<?= url('/requests/' . $req['request_id']) ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-1.5 px-4 rounded-lg transition-colors shadow-sm inline-block whitespace-nowrap">
                                    View Details
                                </a>
                                <?php else: ?>
                                <a href="<?= url('/requests/' . $req['request_id']) ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-1.5 px-4 rounded-lg transition-colors shadow-sm inline-block whitespace-nowrap">
                                    Review
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    const searchInput = document.getElementById('ajax-search');
    const filterInput = document.getElementById('ajax-filter');
    const tableBody = document.getElementById('request-table-body');
    const listType = '<?= isset($isMyRequests) ? "myRequests" : "approvals" ?>';
    const baseUrl = '<?= rtrim(url('/'), '/') ?>';

    let timeout = null;
    
    function fetchResults() {
        clearTimeout(timeout);
        const query = searchInput.value;
        const filterType = filterInput.value;
        
        timeout = setTimeout(() => {
            tableBody.style.opacity = '0.5';
            
            fetch(`${baseUrl}/requests/search?q=${encodeURIComponent(query)}&list=${listType}&filterType=${encodeURIComponent(filterType)}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = `<tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-gray-500">No requests found matching "${query}"</span>
                                </div>
                            </td>
                        </tr>`;
                    } else {
                        data.forEach(req => {
                            const date = new Date(req.submission_date.replace(' ', 'T'));
                            const diffTime = Math.abs(new Date() - date);
                            const daysOpen = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                            
                            const nameStr = req.workflow_name || '';
                            const cleanStr = nameStr.replace(/[^a-zA-Z]/g, '');
                            const typePrefix = cleanStr.substring(0, 3).toUpperCase();
                            const reqIdStr = String(req.request_id).padStart(3, '0');
                            const reqCode = `${typePrefix}-${date.getFullYear()}-${reqIdStr}`;
                            
                            const isOverdue = daysOpen >= 5 && (req.status === 'Pending' || req.status === 'Escalated');
                            const statusClass = isOverdue ? 'bg-red-50 text-red-600 border-red-100' : 'bg-orange-50 text-orange-600 border-orange-100';
                            const statusText = req.status === 'Approved' ? 'Approved' : (req.status === 'Rejected' ? 'Rejected' : (isOverdue ? 'Overdue' : 'In Progress'));
                            
                            let daysColorClass = 'text-green-600';
                            if (daysOpen >= 5) daysColorClass = 'text-red-600';
                            else if (daysOpen >= 3) daysColorClass = 'text-orange-600';
                            
                            const formattedDate = (date.getMonth()+1) + '/' + date.getDate() + '/' + date.getFullYear();

                            html += `
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4"><span class="font-semibold text-blue-600">${reqCode}</span></td>
                                <td class="px-6 py-4"><span class="text-gray-700 font-medium">${req.workflow_name}</span></td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-gray-900 font-semibold text-sm">${req.submitter_name}</span>
                                        <span class="text-gray-500 text-xs mt-0.5">${formattedDate}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-700 font-medium text-sm">${listType === 'myRequests' ? (req.approver_name ? req.approver_name + ' Reviewing' : 'System Processing') : '<?= htmlspecialchars(auth_user()['role']) ?> Review'}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-1.5 ${daysColorClass} font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>${daysOpen} days</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border ${statusClass}">
                                        ${statusText}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="${baseUrl}/requests/${req.request_id}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-1.5 px-4 rounded-lg transition-colors shadow-sm inline-block whitespace-nowrap">
                                            ${listType === 'myRequests' ? 'View Details' : 'Review'}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            `;
                        });
                    }
                    
                    tableBody.innerHTML = html;
                    tableBody.style.opacity = '1';
                });
        }, 300);
    }
    
    searchInput.addEventListener('input', fetchResults);
    filterInput.addEventListener('change', fetchResults);
</script>
