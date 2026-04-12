<div class="px-2">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Audit Trail & Search</h1>
        <p class="text-gray-500 mt-1">Comprehensive log of all workflow requests and actions</p>
    </div>

    <!-- Search and Filters Section -->
    <div class="mb-6 space-y-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="audit-ajax-search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search by ID, Requester, or Status..." class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
            </div>
            
            <div class="flex gap-2">
                <!-- Type Filter -->
                <select id="audit-filter-type" class="bg-white border border-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-100 transition cursor-pointer">
                    <option value="">All Types</option>
                    <?php 
                        $allTypes = [];
                        foreach($requests as $r) { if(!empty($r['workflow_name'])) $allTypes[$r['workflow_name']] = 1; }
                        foreach(array_keys($allTypes) as $t): 
                    ?>
                        <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Status Filter -->
                <select id="audit-filter-status" class="bg-white border border-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-100 transition cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Escalated">Escalated</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Results Banner -->
    <div class="bg-[#f0f7ff] border-t border-b border-blue-100 p-4 rounded-xl mb-6 shadow-sm flex justify-between items-center">
        <span id="audit-count-label" class="text-blue-800 font-medium"><?= count($requests) ?> requests found</span>
        <div id="audit-loading" class="hidden">
            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
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
                    <th class="px-6 py-4">Days Open</th>
                    <th class="px-6 py-4">Last Action</th>
                </tr>
            </thead>
            <tbody id="audit-table-body" class="divide-y divide-gray-100">
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No requests found matching your criteria</td>
                </tr>
                <?php else: ?>
                    <?php 
                    $now = new DateTime();
                    foreach ($requests as $req): 
                        $submitted = new DateTime($req['submission_date']);
                        $daysOpen = $now->diff($submitted)->days;
                        $typePrefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $req['workflow_name']), 0, 3));
                        $reqCode = $typePrefix . '-' . date('Y', strtotime($req['submission_date'])) . '-' . str_pad($req['request_id'], 3, '0', STR_PAD_LEFT);
                        $dbStatus = $req['status'];
                        $isOverdue = $daysOpen >= 5 && in_array($dbStatus, ['Pending', 'Escalated']);
                        $statusClass = $isOverdue ? 'bg-red-50 text-red-600 border-red-100' : (in_array($dbStatus, ['Pending', 'Escalated']) ? 'bg-orange-50 text-orange-600 border-orange-100' : ($dbStatus === 'Approved' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-gray-100 text-gray-600 border-gray-200'));
                        $statusText = $isOverdue ? 'Overdue' : $dbStatus;

                        $lastAction = "Under Review";
                        if (!empty($req['last_action_type']) && $req['last_action_type'] !== 'Created') {
                            $lastAction = htmlspecialchars($req['last_action_type']) . (!empty($req['last_action_comment']) ? ': ' . htmlspecialchars($req['last_action_comment']) : '');
                        }
                    ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4"><span class="font-semibold text-blue-600"><?= $reqCode ?></span></td>
                        <td class="px-6 py-4"><span class="text-gray-700 font-medium"><?= htmlspecialchars($req['workflow_name']) ?></span></td>
                        <td class="px-6 py-4"><span class="text-gray-900 font-semibold text-sm"><?= htmlspecialchars($req['submitter_name']) ?></span></td>
                        <td class="px-6 py-4"><span class="text-gray-600 text-sm"><?= date('M j, Y', strtotime($req['submission_date'])) ?></span></td>
                        <td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $statusClass ?>"><?= $statusText ?></span></td>
                        <td class="px-6 py-4"><span class="text-gray-700 text-sm"><?= $daysOpen ?> days</span></td>
                        <td class="px-6 py-4"><span class="text-gray-500 text-xs truncate max-w-[150px] inline-block" title="<?= $lastAction ?>"><?= $lastAction ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const searchInput = document.getElementById('audit-ajax-search');
    const typeFilter = document.getElementById('audit-filter-type');
    const statusFilter = document.getElementById('audit-filter-status');
    const tableBody = document.getElementById('audit-table-body');
    const countLabel = document.getElementById('audit-count-label');
    const loadingIcon = document.getElementById('audit-loading');
    const baseUrl = '<?= rtrim(url('/'), '/') ?>';

    let debounceTimer = null;

    function fetchAuditResults() {
        clearTimeout(debounceTimer);
        const query = searchInput.value;
        const type = typeFilter.value;
        const status = statusFilter.value;

        debounceTimer = setTimeout(() => {
            loadingIcon.classList.remove('hidden');
            tableBody.style.opacity = '0.5';

            fetch(`${baseUrl}/audit/search?q=${encodeURIComponent(query)}&type=${encodeURIComponent(type)}&status=${encodeURIComponent(status)}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = `<tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">No requests found matching your criteria</td></tr>`;
                    } else {
                        data.forEach(req => {
                            const date = new Date(req.submission_date.replace(' ', 'T'));
                            const diffTime = Math.abs(new Date() - date);
                            const daysOpen = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                            
                            const typePrefix = (req.workflow_name || '').replace(/[^a-zA-Z]/g, '').substring(0, 3).toUpperCase();
                            const reqCode = `${typePrefix}-${date.getFullYear()}-${String(req.request_id).padStart(3, '0')}`;
                            
                            const isOverdue = daysOpen >= 5 && (req.status === 'Pending' || req.status === 'Escalated');
                            let statusClass = 'bg-gray-100 text-gray-600 border-gray-200';
                            let statusText = req.status;

                            if (isOverdue) { statusClass = 'bg-red-50 text-red-600 border-red-100'; statusText = 'Overdue'; }
                            else if (req.status === 'Pending' || req.status === 'Escalated') { statusClass = 'bg-orange-50 text-orange-600 border-orange-100'; statusText = 'In Progress'; }
                            else if (req.status === 'Approved') { statusClass = 'bg-green-50 text-green-600 border-green-100'; }

                            let lastAction = "Under Review";
                            if (req.last_action_type && req.last_action_type !== 'Created') {
                                lastAction = req.last_action_type + (req.last_action_comment ? ': ' + req.last_action_comment : '');
                            }

                            const options = { year: 'numeric', month: 'short', day: 'numeric' };
                            const formattedDate = date.toLocaleDateString('en-US', options);

                            html += `
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4"><span class="font-semibold text-blue-600">${reqCode}</span></td>
                                <td class="px-6 py-4"><span class="text-gray-700 font-medium">${req.workflow_name}</span></td>
                                <td class="px-6 py-4"><span class="text-gray-900 font-semibold text-sm">${req.submitter_name}</span></td>
                                <td class="px-6 py-4"><span class="text-gray-600 text-sm">${formattedDate}</span></td>
                                <td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border ${statusClass}">${statusText}</span></td>
                                <td class="px-6 py-4"><span class="text-gray-700 text-sm">${daysOpen} days</span></td>
                                <td class="px-6 py-4"><span class="text-gray-500 text-xs truncate max-w-[150px] inline-block" title="${lastAction}">${lastAction}</span></td>
                            </tr>`;
                        });
                    }
                    tableBody.innerHTML = html;
                    countLabel.innerText = `${data.length} requests found`;
                    tableBody.style.opacity = '1';
                    loadingIcon.classList.add('hidden');
                });
        }, 300);
    }

    searchInput.addEventListener('input', fetchAuditResults);
    typeFilter.addEventListener('change', fetchAuditResults);
    statusFilter.addEventListener('change', fetchAuditResults);
</script>
