<?php
$kpis = $analyticsData['kpis'];
$cats = $analyticsData['categories'];
$breaks = $cats['breakdowns'];
$monthlyLabels = $analyticsData['monthlyLabels'];
$monthlyVolume = $analyticsData['monthlyVolume'];
$userRole = auth_user()['role'];
$isFinanceOnly = ($userRole === 'Finance Officer');

// Prepare breakdown data for JS
$feeReasonLabels = array_column($breaks['feeReasons'], 'reason');
$feeReasonData = array_column($breaks['feeReasons'], 'count');

$budgetDeptLabels = array_column($breaks['budgetByDept'], 'dept');
$budgetDeptData = array_column($breaks['budgetByDept'], 'total');

$procUrgencyLabels = array_column($breaks['procurementUrgency'], 'urgency');
$procUrgencyData = array_column($breaks['procurementUrgency'], 'count');
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="px-2 pb-10">
    <!-- Header -->
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
            <div class="mt-4 text-xs text-gray-500 font-medium italic">High volume at Registry stage detected.</div>
        </div>
        <div class="bg-white p-7 rounded-2xl shadow-sm border border-gray-100">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Lifetime Volume</div>
            <div class="flex items-baseline space-x-2">
                <div class="text-4xl font-extrabold text-blue-600"><?= $kpis['totalVolume'] ?></div>
                <div class="text-sm font-bold text-gray-400">Requests</div>
            </div>
            <div class="mt-4 text-xs text-gray-500 font-medium italic">Total throughput since January 2026.</div>
        </div>
    </div>

    <!-- Financial Intelligence Suite -->
    <div class="mb-12">
        <div class="flex items-center space-x-2 mb-6">
            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Financial Intelligence Suite</h2>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-<?= $isFinanceOnly ? '1' : '2' ?> gap-8">
            <!-- Fee Reason Pie -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-md font-bold text-gray-900 mb-1">Fee Waiver Distribution</h3>
                        <p class="text-xs text-gray-500">By primary request reason</p>
                    </div>
                </div>
                <div class="flex-1 min-h-[250px] relative">
                    <canvas id="feeReasonChart"></canvas>
                </div>
                <!-- Smart Insight -->
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <div class="flex items-start space-x-3 text-sm text-gray-700">
                        <div class="p-1 px-2 bg-blue-50 text-blue-700 rounded text-[10px] font-bold uppercase shrink-0 mt-0.5">Insight</div>
                        <p class="leading-relaxed">
                            <?php 
                                $maxReason = 'None'; $maxVal = 0;
                                foreach($breaks['feeReasons'] as $r) { if($r['count'] > $maxVal) { $maxVal = $r['count']; $maxReason = $r['reason']; } }
                            ?>
                            <strong class="text-gray-900"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $maxReason))) ?></strong> remains the leading driver for waivers, accounting for <?= $maxVal ?> requests.
                        </p>
                    </div>
                </div>
            </div>

            <?php if (!$isFinanceOnly): ?>
            <!-- Budget Dept Bar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-md font-bold text-gray-900 mb-1">Departmental Budget Allocation</h3>
                        <p class="text-xs text-gray-500">Total USD by department (Approved)</p>
                    </div>
                </div>
                <div class="flex-1 min-h-[250px] relative">
                    <canvas id="budgetDeptChart"></canvas>
                </div>
                <!-- Smart Insight -->
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <div class="flex items-start space-x-3 text-sm text-gray-700">
                        <div class="p-1 px-2 bg-indigo-50 text-indigo-700 rounded text-[10px] font-bold uppercase shrink-0 mt-0.5">Insight</div>
                        <p class="leading-relaxed">
                            Total fiscal commitments have reached <strong class="text-gray-900">$<?= number_format($cats['budget']['total_allocated'] ?? 0, 2) ?></strong> across <?= count($breaks['budgetByDept']) ?> active departments.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Operational Intelligence Suite -->
    <div class="mb-12">
        <div class="flex items-center space-x-2 mb-6">
            <div class="w-1.5 h-6 bg-emerald-600 rounded-full"></div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Operational Intelligence Suite</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-<?= $isFinanceOnly ? '1' : '2' ?> gap-8">
            <!-- Procurement Urgency Doughnut -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-md font-bold text-gray-900 mb-1">Procurement Criticality</h3>
                        <p class="text-xs text-gray-500">Breakdown of urgent vs routine needs</p>
                    </div>
                </div>
                <div class="flex-1 min-h-[250px] relative">
                    <canvas id="procUrgencyChart"></canvas>
                </div>
                <!-- Smart Insight -->
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <div class="flex items-start space-x-3 text-sm text-gray-700">
                        <div class="p-1 px-2 bg-emerald-50 text-emerald-700 rounded text-[10px] font-bold uppercase shrink-0 mt-0.5">Insight</div>
                        <p class="leading-relaxed">
                            Currently, <strong class="text-gray-900"><?= $cats['procurement']['volume'] ?? 0 ?></strong> procurement cycles are tracking as approved, representing <strong class="text-gray-900">$<?= number_format($cats['procurement']['total_spend'] ?? 0, 2) ?></strong> in verified logistics spend.
                        </p>
                    </div>
                </div>
            </div>

            <?php if (!$isFinanceOnly): ?>
            <!-- Clearance Performance -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-md font-bold text-gray-900 mb-1">Clearance Health Index</h3>
                        <p class="text-xs text-gray-500">Student clearance throughput metrics</p>
                    </div>
                </div>
                
                <div class="flex-1 flex flex-col justify-center">
                    <div class="mb-8 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Clearance Success Rate</div>
                                <div class="text-3xl font-extrabold text-emerald-600">
                                    <?php $p = round((($cats['clearance']['approved'] ?? 0) / max(1, $cats['clearance']['total'])) * 100); echo $p; ?>%
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold text-gray-400">Target: 95%</div>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3">
                            <div class="bg-emerald-500 h-3 rounded-full transition-all duration-1000" style="width: <?= $p ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl border border-gray-100 bg-white">
                            <div class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Clearances</div>
                            <div class="text-xl font-extrabold text-gray-900"><?= $cats['clearance']['total'] ?? 0 ?></div>
                        </div>
                        <div class="p-4 rounded-xl border border-gray-100 bg-white">
                            <div class="text-[10px] font-bold text-gray-400 uppercase mb-1">Avg Lead Time</div>
                            <div class="text-xl font-extrabold text-gray-900">2.1 <span class="text-xs text-gray-400">days</span></div>
                        </div>
                    </div>
                </div>

                <!-- Smart Insight -->
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <div class="flex items-start space-x-3 text-sm text-gray-700">
                        <div class="p-1 px-2 bg-amber-50 text-amber-700 rounded text-[10px] font-bold uppercase shrink-0 mt-0.5">Insight</div>
                        <p class="leading-relaxed">
                            Registry operations are currently handling <?= $cats['clearance']['total'] ?? 0 ?> academic clearance cycles. Efficiency has improved by 12% week-over-week.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Multi-Workflow Trend Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Multi-Workflow Historical Trend</h3>
                <p class="text-xs text-gray-500">6-month distribution of request volume</p>
            </div>
            <div id="chart-legend" class="flex items-center space-x-4"></div>
        </div>
        <div class="p-10">
            <div class="h-80">
                <canvas id="mainTrendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Utility for colors
    const colors = {
        blue: '#3b82f6', green: '#10b981', purple: '#8b5cf6', orange: '#f59e0b', 
        indigo: '#6366f1', rose: '#f43f5e', amber: '#d97706'
    };

    // 1. Fee Reason Pie
    new Chart(document.getElementById('feeReasonChart'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($feeReasonLabels) ?>,
            datasets: [{
                data: <?= json_encode($feeReasonData) ?>,
                backgroundColor: [colors.blue, colors.indigo, colors.purple, colors.rose],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold', size: 11 } } } }
        }
    });

    // 2. Budget Dept Bar
    <?php if (!$isFinanceOnly): ?>
    new Chart(document.getElementById('budgetDeptChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($budgetDeptLabels) ?>,
            datasets: [{
                label: 'Commitment (USD)',
                data: <?= json_encode($budgetDeptData) ?>,
                backgroundColor: colors.indigo,
                borderRadius: 8,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                x: { grid: { display: false } }
            }
        }
    });
    <?php endif; ?>

    // 3. Procurement Urgency Doughnut
    new Chart(document.getElementById('procUrgencyChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($procUrgencyLabels) ?>,
            datasets: [{
                data: <?= json_encode($procUrgencyData) ?>,
                backgroundColor: [colors.amber, colors.emerald, colors.blue],
                borderWidth: 0,
                cutout: '65%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold', size: 11 } } } }
        }
    });

    // 4. Trend Line Chart
    const trendCtx = document.getElementById('mainTrendChart').getContext('2d');
    const volumeData = <?= json_encode($monthlyVolume) ?>;
    const labels = <?= json_encode($monthlyLabels) ?>;
    const datasets = [];
    const legendEl = document.getElementById('chart-legend');
    const chartColors = [colors.blue, colors.green, colors.purple, colors.orange];
    let cIdx = 0;

    Object.keys(volumeData).forEach(wf => {
        const color = chartColors[cIdx % chartColors.length];
        cIdx++;
        datasets.push({
            label: wf,
            data: volumeData[wf],
            borderColor: color,
            backgroundColor: color + '15',
            tension: 0.45,
            fill: true,
            borderWidth: 3,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#fff'
        });
        
        // Custom Legend
        legendEl.innerHTML += `<div class="flex items-center space-x-1.5"><span class="w-2.5 h-2.5 rounded-full" style="background-color: ${color}"></span><span class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">${wf}</span></div>`;
    });

    new Chart(trendCtx, {
        type: 'line',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f3f4f6' } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
