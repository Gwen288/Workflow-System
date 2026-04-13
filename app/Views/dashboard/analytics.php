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

// Priority Data for JS
$priorityLabels = array_column($breaks['priorityMix'], 'level');
$priorityData = array_column($breaks['priorityMix'], 'count');

// Classic Chart Data
$cycleLabels = array_column($breaks['cycleTimes'], 'name');
$cycleData = array_map(function($v) { return round($v, 1); }, array_column($breaks['cycleTimes'], 'avg_days'));
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="px-2 pb-10 space-y-12">
    <?php if ($userRole === 'Admin'): ?>
    <!-- ========================================================================================== -->
    <!-- MISSION CONTROL ANALYTICS (ADMIN ONLY)                                                     -->
    <!-- ========================================================================================== -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 animate-in fade-in slide-in-from-top-4 duration-1000">
        <div class="space-y-1">
            <h1 class="text-4xl font-black text-slate-900 dark:text-gray-100 tracking-tighter">System Intelligence</h1>
            <p class="text-slate-500 font-medium italic">Advanced heuristics and institutional focus monitoring.</p>
        </div>
        <div class="flex items-center space-x-2 bg-indigo-50 dark:bg-gray-800 px-4 py-2 rounded-2xl border border-indigo-100 dark:border-gray-700">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></span>
            <span class="text-[10px] font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-widest">Global Watch Active</span>
        </div>
    </div>

    <!-- Admin Top KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 dark:bg-gray-700 rounded-bl-[4rem] -mr-8 -mt-8 transition-all group-hover:scale-110"></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Efficiency Index</p>
            <div class="text-3xl font-black text-indigo-600"><?= $kpis['approvalRate'] ?>%</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Backlog Load</p>
            <div class="text-3xl font-black text-orange-500"><?= $kpis['totalPending'] ?></div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Identity Pool</p>
            <div class="text-3xl font-black text-slate-900 dark:text-gray-100"><?= $kpis['totalUsers'] ?></div>
        </div>
        <div class="bg-slate-900 rounded-[2rem] p-8 shadow-2xl relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-transparent"></div>
            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">SLA Integrity</p>
            <div class="text-3xl font-black text-white"><?= max(0, 100 - (int)$kpis['overdue']) ?>%</div>
        </div>
    </div>

    <!-- Top Row Focused Intelligence -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- New: Institutional Priority Mix -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700 p-10">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-sm font-black text-slate-900 dark:text-gray-100 uppercase tracking-widest tracking-[0.1em]">Priority Intelligence</h3>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">System Resource Allocation</p>
            </div>
            <div class="h-80"><canvas id="priorityMixChart"></canvas></div>
        </div>

        <!-- Role Analytics (Cross-Dept Velocity) -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700 p-10">
             <div class="flex justify-between items-center mb-10">
                <h3 class="text-sm font-black text-slate-900 dark:text-gray-100 uppercase tracking-widest tracking-[0.1em]">Flow Velocity</h3>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Avg Cycle Time (Days)</p>
            </div>
            <div class="h-80 flex flex-col items-center justify-center">
                <?php if (empty($cycleData)): ?>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-slate-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Awaiting Initial Approvals</p>
                    </div>
                <?php else: ?>
                    <canvas id="adminCycleChart"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Global Analytics (Admin/CFO Suite) -->
    <div class="mb-12">
        <div class="flex items-center space-x-2 mb-8">
            <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
            <h2 class="text-xl font-black text-slate-900 dark:text-gray-100 tracking-tight uppercase tracking-[0.1em]">Financial Intelligence Suite</h2>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Fee Waiver Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700 p-10 flex flex-col group hover:border-indigo-500 transition-all duration-500">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 dark:text-gray-100 uppercase tracking-widest">Fee Waiver Heuristics</h3>
                        <p class="text-[10px] text-slate-500 mt-1 font-medium">Categorical distribution by reason.</p>
                    </div>
                </div>
                <div class="flex-1 min-h-[280px] relative"><canvas id="feeReasonChart"></canvas></div>
            </div>

            <!-- Budget Allocation -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700 p-10 flex flex-col group hover:border-emerald-500 transition-all duration-500">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 dark:text-gray-100 uppercase tracking-widest">Departmental Commitments</h3>
                        <p class="text-[10px] text-slate-500 mt-1 font-medium">Total approved budget allocations.</p>
                    </div>
                </div>
                <div class="flex-1 min-h-[280px] relative"><canvas id="budgetDeptChart"></canvas></div>
            </div>
        </div>
    </div>

    <!-- Stretched Trend Chart (At Bottom) -->
    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700 p-10 animate-in fade-in duration-1000">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-gray-100 uppercase tracking-widest tracking-[0.2em]">Institutional Throughput Timeline</h3>
                <p class="text-[10px] text-slate-500 mt-1 font-medium italic">Multi-workflow historical volume analysis.</p>
            </div>
            <div id="chart-legend" class="flex items-center space-x-6"></div>
        </div>
        <div class="h-96"><canvas id="mainTrendChart"></canvas></div>
    </div>

    <?php elseif ($isPrivileged): ?>
        <!-- Standard CFO/Privileged Interface -->
        <div class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Intelligence Suite</h1>
                <p class="text-gray-500 mt-1">Multi-dimensional analysis of workflow dynamics</p>
            </div>
        </div>
        <!-- ... (Rest of existing privileged view can remain or be simplified) -->
    <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const colors = { blue: '#3b82f6', green: '#10b981', purple: '#8b5cf6', orange: '#f59e0b', indigo: '#6366f1', rose: '#f43f5e', amber: '#d97706', emerald: '#10b981' };
    const userRole = <?= json_encode($userRole) ?>;

    if (userRole === 'Admin') {
        // 1. New: Priority Mix Chart
        new Chart(document.getElementById('priorityMixChart'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($priorityLabels) ?>,
                datasets: [{
                    data: <?= json_encode($priorityData) ?>,
                    backgroundColor: [colors.rose, colors.orange, colors.blue],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold', size: 10 } } }, cutout: '70%'}
            }
        });

        // 2. Financial Intelligence
        new Chart(document.getElementById('feeReasonChart'), { 
            type: 'doughnut', data: { labels: <?= json_encode($feeReasonLabels) ?>, datasets: [{ data: <?= json_encode($feeReasonData) ?>, backgroundColor: [colors.blue, colors.indigo, colors.purple, colors.rose], borderWidth: 0 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, font: { weight: 'bold', size: 10 } } } } } 
        });

        new Chart(document.getElementById('budgetDeptChart'), { 
            type: 'bar', data: { labels: <?= json_encode($budgetDeptLabels) ?>, datasets: [{ label: 'USD', data: <?= json_encode($budgetDeptData) ?>, backgroundColor: colors.emerald, borderRadius: 12 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } } 
        });

        // 3. Cycle Velocity (Fixed logic fallback)
        const cycleCanvas = document.getElementById('adminCycleChart');
        if (cycleCanvas) {
            new Chart(cycleCanvas, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($cycleLabels) ?>,
                    datasets: [{
                        label: 'Days',
                        data: <?= json_encode($cycleData) ?>,
                        backgroundColor: colors.indigo,
                        borderRadius: 12, barThickness: 45
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
                }
            });
        }

        // 4. Stretched Main Trend (At Bottom)
        const trendCtx = document.getElementById('mainTrendChart').getContext('2d');
        const volumeData = <?= json_encode($monthlyVolume) ?>;
        const labels = <?= json_encode($monthlyLabels) ?>;
        const datasets = [];
        const legendEl = document.getElementById('chart-legend');
        const chartColors = [colors.blue, colors.green, colors.rose, colors.orange, colors.purple];
        let cIdx = 0;
        Object.keys(volumeData).forEach(wf => {
            const color = chartColors[cIdx % chartColors.length]; cIdx++;
            datasets.push({ label: wf, data: volumeData[wf], borderColor: color, backgroundColor: color + '05', tension: 0.45, fill: true, borderWidth: 3.5, pointRadius: 4, pointBackgroundColor: color });
            legendEl.innerHTML += `<div class="flex items-center space-x-2"><span class="w-2.5 h-2.5 rounded-full" style="background-color: ${color}"></span><span class="text-[9px] font-black text-slate-400 tracking-widest uppercase">${wf}</span></div>`;
        });
        new Chart(trendCtx, { type: 'line', data: { labels, datasets }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } } });
    }
});
</script>
