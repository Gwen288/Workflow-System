<?php
// Calculate metric statistics from requests
$needsAttention = 0;
$inProgress = 0;
$completed = 0;

foreach($myRequests as $req) {
    if ($req['status'] === 'Rejected' && ($req['is_acknowledged'] ?? 0) == 0) {
        $needsAttention++;
    } elseif ($req['status'] === 'Approved') {
        $completed++;
    } else {
        $inProgress++;
    }
}
?>

<div class="space-y-6 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight leading-tight">
                Student <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Portal</span>
            </h1>
            <p class="text-gray-500 mt-1 text-base font-medium">Welcome back, <?= htmlspecialchars(auth_user()['name']) ?>!</p>
        </div>
        <div class="flex items-center space-x-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-white px-3 py-1.5 rounded-full shadow-sm border border-gray-100">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            <span>Live Update</span>
        </div>
    </div>

    <!-- Stunning Hero Banner (Minimized) -->
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
        <div class="relative bg-gradient-to-br from-[#1e40af] via-[#3730a3] to-[#4338ca] rounded-2xl p-6 md:p-8 shadow-xl overflow-hidden min-h-[220px] flex items-center">
            <!-- Animated Background Shapes -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            
            <div class="relative z-10 max-w-xl">
                <span class="inline-block px-3 py-1 mb-3 text-[10px] font-bold tracking-widest uppercase bg-white/20 backdrop-blur-md rounded-full text-blue-100 border border-white/10">
                    Smart Flow 🚀
                </span>
                <h2 class="text-2xl md:text-3xl font-black text-white mb-2 leading-tight">
                    Skip the queues. <span class="text-blue-300">Automate your path.</span>
                </h2>
                <p class="text-blue-100/70 mb-5 text-sm leading-relaxed font-medium">
                    Submit clearance, fee waivers, or letters in seconds.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="<?= url('/requests/create') ?>" class="px-5 py-2.5 bg-white text-blue-700 text-sm font-black rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center group/btn">
                        Get Started
                        <svg class="w-4 h-4 ml-1.5 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div>
        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4 flex items-center">
            <span class="w-1 h-4 bg-blue-600 rounded-full mr-2"></span>
            Popular Services
        </h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <a href="<?= url('/requests/create?type=4') ?>" class="group p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h4 class="font-bold text-gray-900 text-sm leading-tight">Clearance</h4>
                <p class="text-[10px] text-gray-400 mt-1">Exit & registry clearance</p>
            </a>

            <a href="<?= url('/requests/create?type=1') ?>" class="group p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="font-bold text-gray-900 text-sm leading-tight">Fee Waiver</h4>
                <p class="text-[10px] text-gray-400 mt-1">Aid or adjustments</p>
            </a>

            <a href="<?= url('/requests/create?type=5') ?>" class="group p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h4 class="font-bold text-gray-900 text-sm leading-tight">Intro Letter</h4>
                <p class="text-[10px] text-gray-400 mt-1">Internship letters</p>
            </a>

            <a href="<?= url('/requests/create?type=2') ?>" class="group p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 border-dashed border-2">
                <div class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center mb-3 group-hover:bg-gray-800 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <h4 class="font-bold text-gray-400 text-sm">More</h4>
            </a>
        </div>
    </div>

    <!-- Live Status Section -->
    <div class="space-y-6">
        <!-- Dashboard Metrics (Horizontal Row) -->
        <div>
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4 flex items-center">
                <span class="w-1 h-4 bg-purple-600 rounded-full mr-2"></span>
                Statistics
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                    <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900"><?= $needsAttention ?></h3>
                        <p class="text-[9px] font-bold text-orange-600 uppercase tracking-widest">Needs Attention</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900"><?= $inProgress ?></h3>
                        <p class="text-[9px] font-bold text-blue-600 uppercase tracking-widest">In Progress</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900"><?= $completed ?></h3>
                        <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest">Completed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed (Full Width / Table Style) -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    My Recent Submissions
                </h3>
                <a href="<?= url('/my-requests') ?>" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">Track Requests &rarr;</a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-50">
                                <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Req ID</th>
                                <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                                <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Details</th>
                                <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Submitted</th>
                                <th class="py-4 px-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php if(!empty($myRequests)): ?>
                                <?php foreach(array_slice($myRequests, 0, 5) as $req): 
                                    $meta = json_decode($req['metadata'], true);
                                    $amount = 0;
                                    $items = [];
                                    
                                    if ($req['workflow_name'] === 'Budget') {
                                        $amount = $meta['budget_amount'] ?? 0;
                                        if(!empty($meta['budget_item_1'])) $items[] = $meta['budget_item_1'];
                                        if(!empty($meta['budget_item_2'])) $items[] = $meta['budget_item_2'];
                                    } elseif ($req['workflow_name'] === 'Procurement') {
                                        $amount = $meta['procurement_cost'] ?? 0;
                                    } elseif ($req['workflow_name'] === 'Fee Waiver') {
                                        $amount = $meta['fee_requested_adjustment'] ?? 0;
                                    }
                                ?>
                                    <tr class="hover:bg-slate-50/50 transition-all group">
                                        <td class="py-5 px-6">
                                            <a href="<?= url('/requests/' . $req['request_id']) ?>" class="text-base font-bold text-blue-700 hover:text-blue-900 transition-colors">
                                                REQ-<?= str_pad($req['request_id'], 4, '0', STR_PAD_LEFT) ?>
                                            </a>
                                        </td>
                                        <td class="py-5 px-6">
                                            <span class="inline-block px-4 py-1.5 bg-gray-50 text-gray-600 text-[11px] font-bold rounded-full border border-gray-100 shadow-sm">
                                                <?= htmlspecialchars($req['workflow_name']) ?>
                                            </span>
                                        </td>
                                        <td class="py-5 px-6">
                                            <div class="flex flex-col">
                                                <?php if($amount > 0): ?>
                                                    <span class="text-sm font-black text-gray-900 tracking-tight">GHS <?= number_format($amount, 2) ?></span>
                                                <?php endif; ?>
                                                <?php if(!empty($items)): ?>
                                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                                        <?php foreach($items as $item): ?>
                                                            <span class="text-[9px] font-bold bg-blue-50 text-blue-500 px-2 py-0.5 rounded border border-blue-100/50"><?= htmlspecialchars($item) ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="py-5 px-6 text-sm text-gray-500 font-medium whitespace-nowrap">
                                            <?= date('M d, Y g:i A', strtotime($req['submission_date'])) ?>
                                        </td>
                                        <td class="py-5 px-6 text-right">
                                            <?php if($req['status'] === 'Approved'): ?>
                                                <span class="inline-flex items-center text-sm font-bold text-emerald-600">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2.5"></span>
                                                    Approved
                                                </span>
                                            <?php elseif($req['status'] === 'Rejected'): ?>
                                                <span class="inline-flex items-center text-sm font-bold text-rose-600">
                                                    <span class="w-2 h-2 rounded-full bg-rose-500 mr-2.5"></span>
                                                    Rejected
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center text-sm font-bold text-amber-500" title="At <?= htmlspecialchars($req['approver_role'] ?? 'Office') ?>">
                                                    <span class="w-2 h-2 rounded-full bg-amber-400 mr-2.5 animate-pulse"></span>
                                                    In Review
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-400 font-medium">No recent submissions found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
