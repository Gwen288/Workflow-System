<div class="px-2">
    <!-- Header Area -->
    <div class="mb-8 p-8 rounded-2xl bg-gradient-to-r from-blue-900 via-indigo-800 to-indigo-900 border border-blue-800 shadow-xl relative overflow-hidden">
        <!-- Abstract gradient shapes -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-blue-500 opacity-20 blur-3xl mix-blend-screen pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-purple-500 opacity-20 blur-3xl mix-blend-screen pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center">
                    Department Command Center
                </h1>
                <p class="text-blue-200 mt-2 text-lg">Oversee, track, and submit all departmental procurements and budgets.</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-6 md:mt-0 flex gap-4">
                <a href="<?= url('/requests/create') ?>" class="bg-gradient-to-r from-orange-400 to-orange-500 hover:from-orange-500 hover:to-orange-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform transition hover:-translate-y-1 hover:shadow-orange-500/30 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Request
                </a>
            </div>
        </div>
    </div>

    <!-- Active Financial Metrics -->
    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2 flex items-center">
        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Financial Overview
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <!-- Budget Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 mt-4 mr-4 bg-emerald-100 text-emerald-600 p-2 rounded-xl group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Requested Budget</p>
            <p class="text-3xl font-black text-gray-900 mt-2">GHS <?= number_format($metrics['totalBudget'], 2) ?></p>
            <p class="text-xs font-medium text-emerald-500 mt-2">From <?= count(array_filter($myRequests, fn($r) => $r['workflow_name'] === 'Budget')) ?> active requests</p>
        </div>
        
        <!-- Procurements Cost -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 mt-4 mr-4 bg-blue-100 text-blue-600 p-2 rounded-xl group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Procurement Est.</p>
            <p class="text-3xl font-black text-gray-900 mt-2">GHS <?= number_format($metrics['totalProcurement'], 2) ?></p>
            <p class="text-xs font-medium text-blue-500 mt-2">From <?= count(array_filter($myRequests, fn($r) => $r['workflow_name'] === 'Procurement')) ?> active requests</p>
        </div>

        <!-- Approvals -->
        <div class="bg-gray-900 text-white rounded-2xl p-6 shadow-lg relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500 rounded-full opacity-20 -mr-10 -mt-10 transform scale-150 rotate-45 pointer-events-none"></div>
            <p class="text-sm font-semibold text-blue-300 uppercase tracking-widest relative z-10">Approved Budgets</p>
            <p class="text-4xl font-black mt-2 relative z-10"><?= $metrics['approvedBudgets'] ?></p>
            <p class="text-xs font-medium text-gray-400 mt-2 relative z-10 flex items-center"><span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span> Fully executed</p>
        </div>

        <div class="bg-gray-800 text-white rounded-2xl p-6 shadow-lg relative overflow-hidden group">
            <p class="text-sm font-semibold text-orange-300 uppercase tracking-widest relative z-10">Approved Procurements</p>
            <p class="text-4xl font-black mt-2 relative z-10"><?= $metrics['approvedProcurements'] ?></p>
            <p class="text-xs font-medium text-gray-400 mt-2 relative z-10 flex items-center"><span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span> Cleared for purchase</p>
        </div>
    </div>


    <!-- Recent Activity Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 flex items-center text-lg">
                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                My Recent Submissions
            </h3>
            <a href="<?= url('/my-requests') ?>" class="text-blue-600 hover:text-blue-800 font-semibold text-sm transition flex items-center">
                Track Requests &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="py-4 px-6 font-semibold text-xs text-gray-500 uppercase tracking-wider">Req ID</th>
                        <th class="py-4 px-6 font-semibold text-xs text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="py-4 px-6 font-semibold text-xs text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="py-4 px-6 font-semibold text-xs text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 flex-1">
                    <?php if (empty($myRequests)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span>No requests submitted yet.</span>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach (array_slice($myRequests, 0, 5) as $req): ?>
                        <tr class="hover:bg-blue-50/30 transition group">
                            <td class="py-4 px-6">
                                <a href="<?= url('/requests/' . $req['request_id']) ?>" class="font-bold text-blue-600 hover:text-blue-800">
                                    REQ-<?= str_pad($req['request_id'], 4, '0', STR_PAD_LEFT) ?>
                                </a>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full border border-gray-200">
                                    <?= htmlspecialchars($req['workflow_name']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">
                                <?= date('M j, Y g:i A', strtotime($req['submission_date'])) ?>
                            </td>
                            <td class="py-4 px-6">
                                <?php if($req['status'] === 'Approved'): ?>
                                    <span class="inline-flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-green-500"></span><span class="text-sm font-semibold text-green-700">Approved</span></span>
                                <?php elseif($req['status'] === 'Rejected'): ?>
                                    <span class="inline-flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span><span class="text-sm font-semibold text-red-700">Rejected</span></span>
                                <?php else: ?>
                                    <span class="inline-flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-yellow-400"></span><span class="text-sm font-semibold text-yellow-700">In Progress</span></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
