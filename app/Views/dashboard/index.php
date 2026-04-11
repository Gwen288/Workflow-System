<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Insights</h1>
        <p class="text-gray-500 mt-1">Logged in as <?= htmlspecialchars(auth_user()['role']) ?> (<?= htmlspecialchars(auth_user()['department']) ?>)</p>
    </div>
    <a href="/requests/create" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded shadow transition font-semibold">
        + New Request
    </a>
</div>

<!-- AI Analytics Overlay -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow border-t-4 border-blue-500 flex flex-col justify-center items-center">
        <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-2">Automated Efficiency Score</h3>
        <p class="text-4xl font-bold text-blue-600"><?= htmlspecialchars($insights['efficiencyScore']) ?>%</p>
        <p class="text-xs mt-2 text-green-600">▲ 2.5% from last week</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow border-t-4 border-indigo-500 col-span-2 relative">
        <h3 class="text-indigo-600 text-sm font-bold uppercase tracking-wider mb-2 flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            Live AI Analysis
        </h3>
        <ul id="ai-feed" class="list-disc pl-5 text-gray-700 text-sm h-32 overflow-hidden relative">
            <!-- Simulated typing effect -->
        </ul>
        <script>
            setTimeout(() => {
                const flags = <?= json_encode($insights['anomalyFlags']) ?>;
                const feed = document.getElementById('ai-feed');
                let html = '';
                flags.forEach((f, i) => {
                    setTimeout(() => {
                        let li = document.createElement('li');
                        li.className = "mb-2 animate-pulse text-indigo-800";
                        li.textContent = '> Analyzer: ' + f;
                        feed.appendChild(li);
                        setTimeout(() => li.classList.remove('animate-pulse'), 1000);
                    }, i * 1500 + 500);
                });
            }, 500);
        </script>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <?php if (!in_array(auth_user()['role'], ['Student', 'Staff'])): ?>
    <!-- Pending Action (For Approvers) -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
            <h2 class="text-xl font-bold text-blue-800">Requires Your Approval</h2>
        </div>
        <div class="p-6">
            <?php if(!empty($pendingRequests)): ?>
                <ul class="divide-y divide-gray-200" id="pending-list">
                    <?php foreach($pendingRequests as $req): ?>
                        <li class="py-4" id="req-<?= $req['request_id'] ?>">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-blue-600 truncate"><?= htmlspecialchars($req['workflow_name']) ?></p>
                                    <p class="text-sm text-gray-500">Submitted by: <?= htmlspecialchars($req['submitter_name']) ?></p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Priority: <?= htmlspecialchars($req['priority_level']) ?>
                                    </span>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex space-x-2">
                                    <a href="/requests/<?= $req['request_id'] ?>" class="font-medium bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition">View Details &rarr;</a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 italic text-sm text-center py-4">No pending approvals.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- My Submissions -->
    <div class="bg-white rounded-lg shadow overflow-hidden <?= in_array(auth_user()['role'], ['Student', 'Staff']) ? 'col-span-2' : '' ?>">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">My Recent Submissions</h2>
        </div>
        <div class="p-6">
            <?php if(!empty($myRequests)): ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach($myRequests as $req): ?>
                        <li class="py-4">
                            <div class="flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        <a href="/requests/<?= $req['request_id'] ?>" class="hover:underline"><?= htmlspecialchars($req['workflow_name']) ?></a>
                                    </p>
                                    <p class="text-xs text-gray-500 pt-1">Currently with: <?= htmlspecialchars($req['approver_name'] ?? 'System') ?></p>
                                </div>
                                <div>
                                    <?php
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        if($req['status'] === 'Approved') $statusClass = 'bg-green-100 text-green-800';
                                        if($req['status'] === 'Rejected') $statusClass = 'bg-red-100 text-red-800';
                                        if($req['status'] === 'Pending') $statusClass = 'bg-yellow-100 text-yellow-800';
                                        if($req['status'] === 'Escalated') $statusClass = 'bg-orange-100 text-orange-800';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                        <?= htmlspecialchars($req['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                 <p class="text-gray-500 italic text-sm text-center py-4">You haven't submitted any requests yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
