<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Student Hub</h1>
    <p class="text-gray-500 mt-1 text-sm">Welcome back, <?= htmlspecialchars(auth_user()['name']) ?>! Manage your academic requests here.</p>
</div>

<!-- Beautiful Header Banner -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 shadow-lg text-white mb-8 relative overflow-hidden">
    <div class="relative z-10">
        <h2 class="text-2xl font-bold mb-2">Need a document quickly?</h2>
        <p class="mb-6 opacity-90 max-w-xl leading-relaxed">Whether you need clearance, transcripts, or an introductory letter for your internship, you can request it all right here with a few clicks.</p>
        <a href="<?= url('/requests/create') ?>" class="inline-block bg-white text-blue-700 font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-xl hover:-translate-y-0.5 transition-transform duration-200">
            Create a New Request
        </a>
    </div>
    <!-- Decorative elements -->
    <div class="absolute -top-12 -right-12 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl"></div>
    <div class="absolute -bottom-8 right-16 w-32 h-32 bg-indigo-300 opacity-20 rounded-full blur-xl"></div>
</div>

<?php
// Calculate metric statistics from requests
$needsAttention = 0;
$inProgress = 0;
$completed = 0;

foreach($myRequests as $req) {
    if ($req['status'] === 'Rejected') {
        $needsAttention++;
    } elseif ($req['status'] === 'Approved') {
        $completed++;
    } else {
        $inProgress++;
    }
}
?>

<!-- Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-6 relative overflow-hidden">
        <div class="flex items-center justify-between z-10 relative">
            <div>
                <p class="text-sm font-semibold text-orange-600 mb-1">Needs Attention</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= $needsAttention ?></h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-orange-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-orange-50 rounded-full opacity-50 border-4 border-orange-100"></div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6 relative overflow-hidden">
        <div class="flex items-center justify-between z-10 relative">
            <div>
                <p class="text-sm font-semibold text-blue-600 mb-1">In Progress</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= $inProgress ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 border-4 border-blue-100"></div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6 relative overflow-hidden">
        <div class="flex items-center justify-between z-10 relative">
            <div>
                <p class="text-sm font-semibold text-green-600 mb-1">Completed</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= $completed ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-green-50 rounded-full opacity-50 border-4 border-green-100"></div>
    </div>
</div>

<!-- My Recent Requests -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h2 class="text-lg font-bold text-gray-900 tracking-tight">Your Recent Requests</h2>
        <a href="<?= url('/my-requests') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">View All</a>
    </div>
    
    <div class="divide-y divide-gray-100">
        <?php if(!empty($myRequests)): ?>
            <?php foreach(array_slice($myRequests, 0, 5) as $req): ?>
                <div class="p-6 flex justify-between items-center hover:bg-slate-50 transition-colors">
                    <div class="flex items-start">
                        <div class="mt-1 mr-4">
                            <?php if($req['status'] == 'Approved'): ?>
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <?php elseif($req['status'] == 'Rejected'): ?>
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <?php else: ?>
                                <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <?php endif; ?>
                        </div>
                        <div>
                             <a href="<?= url('/requests/' . $req['request_id']) ?>" class="text-gray-800 font-semibold hover:text-blue-600 transition inline-block"><?= htmlspecialchars($req['workflow_name']) ?></a>
                             <p class="text-xs text-gray-500 mt-1">Submitted on <?= date('M d, Y', strtotime($req['submission_date'])) ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <?php if($req['status'] == 'Approved'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>
                        <?php elseif($req['status'] == 'Rejected'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                        <?php elseif($req['status'] == 'Escalated'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">At CFO</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">At <?= htmlspecialchars($req['approver_role'] ?? 'Registry') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="p-10 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p>You haven't submitted any requests yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
