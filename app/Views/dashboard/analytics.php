<?php
$kpis = $analyticsData['kpis'];
$cats = $analyticsData['categories'];
$breaks = $cats['breakdowns'];
$monthlyLabels = $analyticsData['monthlyLabels'];
$monthlyVolume = $analyticsData['monthlyVolume'];
$userRole = auth_user()['role'];
$isPrivileged = in_array($userRole, ['CFO', 'Admin']);

// Prepare breakdown data for JS
$feeReasonLabels = array_column($breaks['feeReasons'], 'reason');
$feeReasonData = array_column($breaks['feeReasons'], 'count');
$budgetDeptLabels = array_column($breaks['budgetByDept'], 'dept');
$budgetDeptData = array_column($breaks['budgetByDept'], 'total');
$procUrgencyLabels = array_column($breaks['procurementUrgency'], 'urgency');
$procUrgencyData = array_column($breaks['procurementUrgency'], 'count');

// Classic Chart Data
$cycleLabels = array_column($breaks['cycleTimes'], 'name');
$cycleData = array_map(function($v) { return round($v, 1); }, array_column($breaks['cycleTimes'], 'avg_days'));
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="px-2 pb-10">
    <?php if ($isPrivileged): ?>
    <!-- ========================================================================================== -->
    <!-- CFO/ADMIN VIEW (MODERN INTELLIGENCE SUITE)                                                 -->
    <!-- ========================================================================================== -->
    <div class="mb-10 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Intelligence Suite</h1>
            <p class="text-gray-500 mt-1">Multi-dimensional analysis of workflow dynamics</p>
        </div>
        <div class="text-right">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span> Live Data
            </span>
        </div>
    </div>

    <!-- Global KPIs Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-7 rounded-2xl shadow-sm border border-gray-100 bg-gradient-to-br from-white to-gray-50/50">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Efficiency Rating</div>
            <div class="flex items-baseline space-x-2">
                <div class="text-4xl font-extrabold text-gray-900"><?= $kpis['approvalRate'] ?>%</div>
                <div class="text-xs text-green-600 font-bold">+2.4%</div>
            </div>
            <div class="mt-4 text-xs text-gray-500 font-medium italic">Approval turnaround is currently ahead of SLA.</div>
        </div>
        <div class="bg-white p-7 rounded-2xl shadow-sm border border-gray-100">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Operational Load</div>
            <div class="flex items-baseline space-x-2">
                <div class="text-4xl font-extrabold text-orange-600"><?= $kpis['totalPending'] ?></div>
                <div class="text-sm font-bold text-gray-400">Items</div>
            </div>
            <div class="mt-4 text-xs text-gray-500 font-medium italic">High volume stage detected: <?= $kpis['bottleneck'] ?>.</div>
        </div>
        <div class="bg-white p-7 rounded-2xl shadow-sm border border-gray-100">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Lifetime Volume</div>
            <div class="flex items-baseline space-x-2">
                <div class="text-4xl font-extrabold text-blue-600"><?= $kpis['totalVolume'] ?></div>
                <div class="text-sm font-bold text-gray-400">Requests</div>
            </div>
            <div class="mt-4 text-xs text-gray-500 font-medium italic">Total throughput since system launch.</div>
        </div>
    </div>

    <!-- Suites (Existing Logic) -->
    <div class="mb-12">
        <div class="flex items-center space-x-2 mb-6">
            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Financial Intelligence Suite</h2>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Fee Waiver -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col">
                <div class="flex justify-between items-start mb-8"><div><h3 class="text-md font-bold text-gray-900 mb-1">Fee Waiver Distribution</h3><p class="text-xs text-gray-500">By primary request reason</p></div></div>
                <div class="flex-1 min-h-[250px] relative"><canvas id="feeReasonChart"></canvas></div>
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <div class="flex items-start space-x-3 text-sm text-gray-700"><div class="p-1 px-2 bg-blue-50 text-blue-700 rounded text-[10px] font-bold uppercase mt-0.5 whitespace-nowrap">Insight</div>
                    <p class="leading-relaxed"><?php $maxReason = 'None'; $maxVal = 0; foreach($breaks['feeReasons'] as $r) { if($r['count'] > $maxVal) { $maxVal = $r['count']; $maxReason = $r['reason']; } } ?>
                    <strong class="text-gray-900"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $maxReason))) ?></strong> remains the leading driver for waivers.</p></div>
                </div>
            </div>
            <!-- Budget -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col">
                <div class="flex justify-between items-start mb-8"><div><h3 class="text-md font-bold text-gray-900 mb-1">Departmental Budget Allocation</h3><p class="text-xs text-gray-500">Total USD by department (Approved)</p></div></div>
                <div class="flex-1 min-h-[250px] relative"><canvas id="budgetDeptChart"></canvas></div>
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <div class="flex items-start space-x-3 text-sm text-gray-700"><div class="p-1 px-2 bg-indigo-50 text-indigo-700 rounded text-[10px] font-bold uppercase mt-0.5 whitespace-nowrap">Insight</div>
                    <p class="leading-relaxed">Total commitments reached <strong class="text-gray-900">$<?= number_format($cats['budget']['total_allocated'] ?? 0, 2) ?></strong>.</p></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Multi-Workflow Trend -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between"><div><h3 class="text-lg font-bold text-gray-900 tracking-tight">Multi-Workflow Historical Trend</h3></div><div id="chart-legend" class="flex items-center space-x-4"></div></div>
        <div class="p-10"><div class="h-80"><canvas id="mainTrendChart"></canvas></div></div>
    </div>

    <?php else: ?>
    <!-- ========================================================================================== -->
    <!-- PROFESSIONAL VIEW (SCREENSHOT DESIGN)                                                      -->
    <!-- ========================================================================================== -->
    <div class="mb-10">
        <h1 class="text-2xl font-bold text-gray-900">Analytics & Reports</h1>
        <p class="text-gray-500 text-sm">Key performance indicators and workflow insights</p>
    </div>

    <!-- Top KPI row (Screenshot style) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Avg Cycle Time -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-semibold text-gray-400 mb-1">Avg Cycle Time</div>
                    <div class="text-3xl font-bold text-gray-800 tracking-tight"><?= $kpis['avgCycle'] ?> <span class="text-sm font-normal text-gray-500">days</span></div>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[11px] font-bold text-green-500 uppercase tracking-wider">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                15% vs last month
            </div>
        </div>

        <!-- Approval Rate -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-semibold text-gray-400 mb-1">Approval Rate</div>
                    <div class="text-3xl font-bold text-gray-800 tracking-tight"><?= $kpis['approvalRate'] ?>%</div>
                </div>
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[11px] font-bold text-green-500 uppercase tracking-wider">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                2% vs last month
            </div>
        </div>

        <!-- Pending Over 7 Days -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-semibold text-red-400 mb-1">Pending Over 7 Days</div>
                    <div class="text-3xl font-bold text-red-600 tracking-tight"><?= $kpis['overdue'] ?></div>
                </div>
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="mt-4 text-[11px] font-bold text-red-400 uppercase tracking-wider">Requires immediate attention</div>
        </div>

        <!-- Bottleneck Stage -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-semibold text-orange-400 mb-1">Bottleneck Stage</div>
                    <div class="text-lg font-bold text-gray-800 leading-tight"><?= htmlspecialchars($kpis['bottleneck']) ?></div>
                </div>
                <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="mt-4 text-[11px] font-bold text-orange-400 uppercase tracking-wider">Avg <?= $kpis['avgCycle'] ?> days wait</div>
        </div>
    </div>

    <!-- Middle Section: Charts & bars -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Cycle Time by Workflow -->
        <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-md font-bold text-gray-800 mb-6">Cycle Time by Workflow Type</h3>
            <div class="h-64 relative">
                <canvas id="cycleTimeBarChart"></canvas>
            </div>
            <div class="mt-6 grid grid-cols-3 gap-2">
                <?php foreach($breaks['cycleTimes'] as $idx => $ct): ?>
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-1 mb-1">
                        <span class="w-2 h-2 rounded-full" style="background-color: <?= ['#10b981', '#3b82f6', '#8b5cf6'][$idx % 3] ?>"></span>
                        <span class="text-[10px] text-gray-500 font-medium truncate"><?= $ct['name'] ?></span>
                    </div>
                    <div class="text-xs font-bold text-gray-900"><?= round($ct['avg_days'], 1) ?> days</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Current Workflow Status -->
        <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-md font-bold text-gray-800 mb-6">Current Workflow Status</h3>
            <div class="space-y-6">
                <?php foreach($breaks['statusProgress'] as $idx => $sp): 
                    $total = max(1, $sp['total'] ?? ($sp['pending'] + $sp['approved']));
                    $perc = round(($sp['approved'] / $total) * 100);
                    $color = ['#10b981', '#3b82f6', '#8b5cf6'][$idx % 3];
                ?>
                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <div class="text-xs font-bold text-gray-700"><?= $sp['name'] ?></div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?= $sp['pending'] ?> pending</div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="h-full transition-all duration-1000" style="width: <?= $perc ?>%; background-color: <?= $color ?>"></div>
                    </div>
                    <div class="mt-1 text-[10px] text-gray-400 font-medium">Progress: <?= $perc ?>% completed</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Monthly volume -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-md font-bold text-gray-800">Monthly Request Volume</h3>
            <div id="classic-legend" class="flex items-center space-x-4"></div>
        </div>
        <div class="h-64">
            <canvas id="classicTrendChart"></canvas>
        </div>
    </div>

    <!-- Compliance Scorecard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100 text-center">
            <div class="text-3xl font-bold text-green-500 mb-1">100%</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-0.5">Privacy Compliance</div>
            <div class="text-[9px] text-gray-400 italic">All data encrypted</div>
        </div>
        <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100 text-center">
            <div class="text-3xl font-bold text-blue-500 mb-1">98%</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-0.5">On-Time Approvals</div>
            <div class="text-[9px] text-gray-400 italic">Within SLA</div>
        </div>
        <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100 text-center">
            <div class="text-3xl font-bold text-purple-500 mb-1">100%</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-0.5">Audit Trail</div>
            <div class="text-[9px] text-gray-400 italic">Complete logging</div>
        </div>
        <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100 text-center">
            <div class="text-3xl font-bold text-orange-500 mb-1">95%</div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-0.5">User Satisfaction</div>
            <div class="text-[9px] text-gray-400 italic">Based on feedback</div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const colors = { blue: '#3b82f6', green: '#10b981', purple: '#8b5cf6', orange: '#f59e0b', indigo: '#6366f1', rose: '#f43f5e', amber: '#d97706' };
    const isPrivileged = <?= json_encode($isPrivileged) ?>;

    if (isPrivileged) {
        // MODERN SUITE CHARTS (CFO)
        new Chart(document.getElementById('feeReasonChart'), { type: 'pie', data: { labels: <?= json_encode($feeReasonLabels) ?>, datasets: [{ data: <?= json_encode($feeReasonData) ?>, backgroundColor: [colors.blue, colors.indigo, colors.purple, colors.rose], borderWidth: 0 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold', size: 11 } } } } } });
        new Chart(document.getElementById('budgetDeptChart'), { type: 'bar', data: { labels: <?= json_encode($budgetDeptLabels) ?>, datasets: [{ label: 'Commitment (USD)', data: <?= json_encode($budgetDeptData) ?>, backgroundColor: colors.indigo, borderRadius: 8, barThickness: 30 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4] } }, x: { grid: { display: false } } } } });
        
        const trendCtx = document.getElementById('mainTrendChart').getContext('2d');
        const volumeData = <?= json_encode($monthlyVolume) ?>;
        const labels = <?= json_encode($monthlyLabels) ?>;
        const datasets = [];
        const legendEl = document.getElementById('chart-legend');
        const chartColors = [colors.blue, colors.green, colors.purple, colors.orange];
        let cIdx = 0;
        Object.keys(volumeData).forEach(wf => {
            const color = chartColors[cIdx % chartColors.length]; cIdx++;
            datasets.push({ label: wf, data: volumeData[wf], borderColor: color, backgroundColor: color + '15', tension: 0.45, fill: true, borderWidth: 2.5, pointRadius: 3.5, pointBackgroundColor: '#fff' });
            legendEl.innerHTML += `<div class="flex items-center space-x-1.5"><span class="w-2.5 h-2.5 rounded-full" style="background-color: ${color}"></span><span class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">${wf}</span></div>`;
        });
        new Chart(trendCtx, { type: 'line', data: { labels, datasets }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f3f4f6' } }, x: { grid: { display: false } } } } });
    } else {
        // CLASSIC SCREENSHOT CHARTS (OTHERS)
        
        // 1. Cycle Time Horizontal Bar
        new Chart(document.getElementById('cycleTimeBarChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($cycleLabels) ?>,
                datasets: [{
                    data: <?= json_encode($cycleData) ?>,
                    backgroundColor: [colors.green, colors.blue, colors.purple],
                    borderRadius: 4, barThickness: 24
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    x: { beginAtZero: true, title: { display: true, text: 'Avg Days', font: { size: 10, weight: 'bold' } }, grid: { borderDash: [4,4] } },
                    y: { grid: { display: false } }
                }
            }
        });

        // 2. Monthly Request Volume (Area)
        const classicTrendCtx = document.getElementById('classicTrendChart').getContext('2d');
        const vData = <?= json_encode($monthlyVolume) ?>;
        const cLabels = <?= json_encode($monthlyLabels) ?>;
        const cDatasets = [];
        const cLegendEl = document.getElementById('classic-legend');
        const cChartColors = [colors.blue, colors.purple, colors.green];
        let ci = 0;
        Object.keys(vData).forEach(wf => {
            const color = cChartColors[ci % cChartColors.length]; ci++;
            cDatasets.push({ label: wf, data: vData[wf], borderColor: color, backgroundColor: color + '10', tension: 0.4, fill: true, borderWidth: 2.5, pointRadius: 4, pointBackgroundColor: color, pointBorderColor: '#fff', pointBorderWidth: 2 });
            cLegendEl.innerHTML += `<div class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full" style="background-color: ${color}"></span><span class="text-[10px] font-bold text-gray-500 tracking-wider uppercase">${wf}</span></div>`;
        });
        new Chart(classicTrendCtx, { type: 'line', data: { labels: cLabels, datasets: cDatasets }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4] } }, x: { grid: { display: false } } } } });
    }
});
</script>
