
<div class="px-2">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-gray-100 tracking-tighter">Institutional Ledger</h1>
            <p class="text-slate-500 font-medium italic">Global oversight of all active and completed transactions.</p>
        </div>
        <div class="flex space-x-3 w-full md:w-auto">
            <!-- Search -->
            <div class="relative w-full md:w-64">
                <input type="text" id="ajax-search" placeholder="Search institutional data..." class="w-full bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-gray-300 px-4 py-2.5 pl-10 rounded-2xl shadow-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <!-- Filters -->
            <div class="relative">
                <select id="ajax-filter" class="appearance-none bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-gray-300 font-bold py-2.5 pl-4 pr-10 rounded-2xl shadow-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all cursor-pointer">
                    <option value="">All Flow States</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Escalated">Escalated</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Oversight Metrics -->
    <?php
        $now = new DateTime();
        $total = count($requests);
        $pending = 0;
        $escalated = 0;
        $overdue = 0;
        
        foreach ($requests as $req) {
            if ($req['status'] === 'Pending') $pending++;
            if ($req['status'] === 'Escalated') $escalated++;
            
            $submitted = new DateTime($req['submission_date']);
            if ($now->diff($submitted)->days >= 7 && in_array($req['status'], ['Pending', 'Escalated'])) {
                $overdue++;
            }
        }
    ?>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white dark:bg-gray-800 border border-slate-100 dark:border-gray-700 rounded-3xl p-6 shadow-sm">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Throughput</h3>
            <p class="text-3xl font-black text-slate-900 dark:text-white"><?= $total ?></p>
        </div>
        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 rounded-3xl p-6 shadow-sm">
            <h3 class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mb-2">Pending Flow</h3>
            <p class="text-3xl font-black text-indigo-900 dark:text-indigo-100"><?= $pending ?></p>
        </div>
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/50 rounded-3xl p-6 shadow-sm">
            <h3 class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest mb-2">Escalations</h3>
            <p class="text-3xl font-black text-amber-900 dark:text-amber-100"><?= $escalated ?></p>
        </div>
        <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/50 rounded-3xl p-6 shadow-sm">
            <h3 class="text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest mb-2">SLA Breaches</h3>
            <p class="text-3xl font-black text-rose-900 dark:text-rose-100"><?= $overdue ?></p>
        </div>
    </div>

    <!-- Dynamic Ledger -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-gray-900/50 border-b border-slate-100 dark:border-gray-700 uppercase text-[10px] font-black text-slate-500 dark:text-gray-400 tracking-[0.1em]">
                        <th class="px-8 py-5">Transaction ID</th>
                        <th class="px-8 py-5">Workflow Type</th>
                        <th class="px-8 py-5">Initiator</th>
                        <th class="px-8 py-5">Current Custodian</th>
                        <th class="px-8 py-5">Velocity</th>
                        <th class="px-8 py-5">State</th>
                        <th class="px-8 py-5 text-right">Oversight</th>
                    </tr>
                </thead>
                <tbody id="request-table-body" class="divide-y divide-slate-50 dark:divide-gray-700">
                    <?php if (empty($requests)): ?>
                    <tr>
                        <td colspan="7" class="px-8 py-20 text-center">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No institutional data available.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $req): 
                            $submitted = new DateTime($req['submission_date']);
                            $daysOpen = $now->diff($submitted)->days;
                            $typePrefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $req['workflow_name']), 0, 3));
                            $reqCode = $typePrefix . '-' . date('Y', strtotime($req['submission_date'])) . '-' . str_pad($req['request_id'], 3, '0', STR_PAD_LEFT);
                            
                            $statusClasses = [
                                'Approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-800',
                                'Rejected' => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/20 dark:border-rose-800',
                                'Escalated' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:border-amber-800',
                                'Pending' => 'bg-indigo-50 text-indigo-600 border-indigo-100 dark:bg-indigo-900/20 dark:border-indigo-800'
                            ];
                            $sClass = $statusClasses[$req['status']] ?? 'bg-slate-50 text-slate-600';
                        ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-900/50 transition-colors group">
                            <td class="px-8 py-6 font-black text-indigo-600 dark:text-indigo-400 text-xs tracking-tight"><?= $reqCode ?></td>
                            <td class="px-8 py-6 text-slate-900 dark:text-gray-200 font-bold text-xs"><?= htmlspecialchars($req['workflow_name']) ?></td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-slate-900 dark:text-gray-200 font-black text-xs"><?= htmlspecialchars($req['submitter_name']) ?></span>
                                    <span class="text-slate-400 text-[10px] font-medium uppercase"><?= date('M d, Y', strtotime($req['submission_date'])) ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-slate-600 dark:text-gray-400 font-bold text-xs"><?= $req['approver_name'] ? htmlspecialchars($req['approver_name']) : '<span class="italic opacity-50">None</span>' ?></span>
                                <p class="text-[10px] text-slate-400 font-medium uppercase"><?= htmlspecialchars($req['approver_role'] ?? 'SYSTEM') ?></p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-black <?= $daysOpen >= 7 ? 'text-rose-500' : ($daysOpen >= 3 ? 'text-amber-500' : 'text-emerald-500') ?>">
                                    <?= $daysOpen ?>d <span class="text-[10px] opacity-60 font-medium tracking-normal">elapsed</span>
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border <?= $sClass ?>">
                                    <?= $req['status'] ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="<?= url('/requests/' . $req['request_id']) ?>" class="inline-flex items-center justify-center p-2 rounded-xl bg-slate-50 dark:bg-gray-700 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all hover:bg-indigo-50 active:scale-90 border border-transparent hover:border-indigo-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('ajax-search');
    const filterInput = document.getElementById('ajax-filter');
    const tableBody = document.getElementById('request-table-body');
    const listType = 'all';
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
                        html = `<tr><td colspan="7" class="px-8 py-20 text-center"><p class="text-xs font-black text-slate-400 uppercase tracking-widest">No matching record found.</p></td></tr>`;
                    } else {
                        data.forEach(req => {
                            const date = new Date(req.submission_date.replace(' ', 'T'));
                            const diff = Math.floor((new Date() - date) / (1000 * 60 * 60 * 24));
                            const cleanStr = (req.workflow_name || '').replace(/[^a-zA-Z]/g, '');
                            const typePrefix = cleanStr.substring(0, 3).toUpperCase();
                            const reqCode = `${typePrefix}-${date.getFullYear()}-${String(req.request_id).padStart(3, '0')}`;
                            const velocityClass = diff >= 7 ? 'text-rose-500' : (diff >= 3 ? 'text-amber-500' : 'text-emerald-500');
                            
                            const statusClasses = {
                                'Approved': 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-800',
                                'Rejected': 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/20 dark:border-rose-800',
                                'Escalated': 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:border-amber-800',
                                'Pending': 'bg-indigo-50 text-indigo-600 border-indigo-100 dark:bg-indigo-900/20 dark:border-indigo-800'
                            };
                            const sClass = statusClasses[req.status] || 'bg-slate-50 text-slate-600';
                            const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                            html += `
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-900/50 transition-colors group">
                                <td class="px-8 py-6 font-black text-indigo-600 dark:text-indigo-400 text-xs tracking-tight">${reqCode}</td>
                                <td class="px-8 py-6 text-slate-900 dark:text-gray-200 font-bold text-xs">${req.workflow_name}</td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-slate-900 dark:text-gray-200 font-black text-xs">${req.submitter_name}</span>
                                        <span class="text-slate-400 text-[10px] font-medium uppercase">${formattedDate}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-slate-600 dark:text-gray-400 font-bold text-xs">${req.approver_name || '<span class="italic opacity-50">None</span>'}</span>
                                    <p class="text-[10px] text-slate-400 font-medium uppercase">${req.approver_role || 'SYSTEM'}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-black ${velocityClass}">${diff}d <span class="text-[10px] opacity-60 font-medium tracking-normal">elapsed</span></span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border ${sClass}">${req.status}</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="${baseUrl}/requests/${req.request_id}" class="inline-flex items-center justify-center p-2 rounded-xl bg-slate-50 dark:bg-gray-700 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all hover:bg-indigo-50 active:scale-90 border border-transparent hover:border-indigo-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </td>
                            </tr>`;
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
