<?php
    $pendingCount = $metrics['pendingCount'] ?? count($pendingRequests ?? []);
    $overdueCount = $metrics['overdueCount'] ?? 0;
    $cycleTime = $metrics['cycleTime'] ?? "0.0"; 
    $approvalRate = $metrics['approvalRate'] ?? "0";
?>
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Overview</h1>
    <p class="text-gray-500 mt-1 text-sm">Welcome back! Here's what's happening today.</p>
</div>

<!-- Four Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Pending Approvals -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div class="text-sm font-medium text-gray-500">
                Pending<br>Approvals
            </div>
            <div class="w-10 h-10 rounded bg-blue-100/50 flex items-center justify-center text-blue-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-4xl font-bold text-gray-900"><?= $pendingCount ?></span>
        </div>
        <div class="mt-4 border-t border-gray-50 pt-4">
            <a href="<?= url('/approvals') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center">
                View all <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>

    <!-- Card 2: Overdue -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div class="text-sm font-medium text-gray-500">
                Overdue (>7<br>days)
            </div>
            <div class="w-10 h-10 rounded bg-orange-100/50 flex items-center justify-center text-orange-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-4xl font-bold text-orange-600"><?= $overdueCount ?></span>
        </div>
        <div class="mt-4 border-t border-gray-50 pt-4">
            <span class="text-xs font-medium text-orange-600">Requires immediate attention</span>
        </div>
    </div>

    <!-- Card 3: Avg Cycle Time -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div class="text-sm font-medium text-gray-500">
                Avg Cycle Time
            </div>
            <div class="w-10 h-10 rounded bg-green-100/50 flex items-center justify-center text-green-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline">
            <span class="text-4xl font-bold text-gray-900"><?= $cycleTime ?></span>
            <span class="text-sm text-gray-500 ml-1 font-medium">days</span>
        </div>
        <div class="mt-4 border-t border-gray-50 pt-4 flex items-center text-green-500">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            <span class="text-xs font-medium">15% faster than last month</span>
        </div>
    </div>

    <!-- Card 4: Approval Rate -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div class="text-sm font-medium text-gray-500">
                Approval Rate
            </div>
            <div class="w-10 h-10 rounded bg-fuchsia-100/50 flex items-center justify-center text-fuchsia-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline">
            <span class="text-4xl font-bold text-gray-900"><?= $approvalRate ?></span>
            <span class="text-sm text-gray-500 ml-1 font-medium">%</span>
        </div>
        <div class="mt-4 border-t border-gray-50 pt-4">
            <span class="text-xs font-medium text-fuchsia-600">2% increase this month</span>
        </div>
    </div>
</div>

<!-- Workflow Overview Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <h2 class="text-lg font-bold text-gray-900 mb-6 tracking-tight">Workflow Overview</h2>
    
    <div class="flex flex-col space-y-4">

        
        <?php if(!empty($pendingRequests)): ?>
            <?php foreach(array_slice($pendingRequests, 0, 3) as $req): ?>
                <div class="border-l-4 border-blue-400 bg-slate-50 rounded-r shadow-sm p-5 flex justify-between items-center hover:bg-slate-100 transition">
                    <div>
                         <a href="<?= url('/requests/' . $req['request_id']) ?>" class="text-gray-800 font-semibold hover:text-blue-600 transition mb-1 inline-block"><?= htmlspecialchars($req['workflow_name']) ?></a>
                         <p class="text-xs text-gray-500">Submitted by: <?= htmlspecialchars($req['submitter_name']) ?></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase bg-blue-100 text-blue-700">Needs Approval</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- AI Intelligence Narratives (Real Insights) -->
<?php if (!empty($insights['narrative'])): ?>
<div class="mb-8 border-t border-gray-100 pt-8">
    <div class="flex items-center space-x-2 mb-4">
        <div class="p-1.5 bg-indigo-100 rounded-lg">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Workflow Intelligence</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
        <?php foreach($insights['narrative'] as $line): ?>
            <div class="flex items-start group">
                <div class="mt-1 mr-3 w-1.5 h-1.5 rounded-full bg-indigo-400 group-hover:scale-150 transition-transform"></div>
                <p class="text-[15px] leading-relaxed text-gray-700 font-medium">
                    <?= htmlspecialchars($line) ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
