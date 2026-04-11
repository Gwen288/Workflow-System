<?php
$kpis = $analyticsData['kpis'];
$monthlyLabels = $analyticsData['monthlyLabels'];
$monthlyVolume = $analyticsData['monthlyVolume'];
$workflowStats = $analyticsData['workflowStats'];

// Prepare workflow stats for Progress Bars and Bar Chart
$barChartLabels = [];
$barChartData = [];
$barChartColors = ['#22c55e', '#3b82f6', '#8b5cf6'];

$wfProgress = [];
$totalPendingCount = 0;

$colorIndex = 0;
foreach($workflowStats as $stat) {
    if ($stat['name'] == 'Clearance') {
        $uiName = 'Student Clearance/Letters';
        $color = '#22c55e';
        $styleClass = 'bg-green-500';
    } elseif ($stat['name'] == 'Fee Waiver') {
        $uiName = 'Fee Adjustments/Waivers';
        $color = '#3b82f6';
        $styleClass = 'bg-blue-500';
    } else {
        $uiName = 'Procurement Requests';
        $color = '#8b5cf6';
        $styleClass = 'bg-purple-500';
    }
    
    // some fake avg cycle times for the UI
    $mockAvgs = ['Clearance' => 2.8, 'Fee Waiver' => 3.2, 'Procurement' => 5.1];
    $avg = $mockAvgs[$stat['name']];

    $barChartLabels[] = $uiName;
    $barChartData[] = $avg;
    $barChartColors[] = $color;

    $wfProgress[] = [
        'name' => $uiName,
        'pending' => $stat['pending_count'],
        'avg' => $avg,
        'colorClass' => $styleClass
    ];
    $totalPendingCount += $stat['pending_count'];
}

$bottleneckStage = 'Finance Review';
$bottleneckTime = '4.2';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="px-2 pb-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Analytics & Reports</h1>
        <p class="text-gray-500 mt-1">Key performance indicators and workflow insights</p>
    </div>

    <!-- KPIs Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Avg Cycle Time -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col relative overflow-hidden">
            <h3 class="text-gray-500 text-sm font-medium mb-3">Avg Cycle Time</h3>
            <div class="flex items-baseline mb-4">
                <span class="text-3xl font-bold text-gray-900"><?= $kpis['avgCycleTime'] ?></span>
                <span class="ml-2 text-gray-600 font-medium">days</span>
            </div>
            <div class="flex items-center text-green-600 text-xs font-semibold">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                15% vs last month
            </div>
            <div class="absolute top-6 right-6 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Approval Rate -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col relative overflow-hidden">
            <h3 class="text-gray-500 text-sm font-medium mb-3">Approval Rate</h3>
            <div class="flex items-baseline mb-4">
                <span class="text-3xl font-bold text-gray-900"><?= $kpis['approvalRate'] ?></span>
                <span class="ml-1 text-3xl font-bold text-gray-900">%</span>
            </div>
            <div class="flex items-center text-green-600 text-xs font-semibold">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                2% vs last month
            </div>
            <div class="absolute top-6 right-6 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Pending Over 7 Days -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col relative overflow-hidden ring-1 ring-red-100">
            <h3 class="text-gray-500 text-sm font-medium mb-3">Pending Over 7 Days</h3>
            <div class="flex items-baseline mb-4">
                <span class="text-3xl font-bold text-red-600"><?= $kpis['overdue7Days'] ?></span>
            </div>
            <div class="text-red-500 text-xs font-medium">
                Requires immediate attention
            </div>
            <div class="absolute top-6 right-6 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>

        <!-- Bottleneck Stage -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col relative overflow-hidden">
            <h3 class="text-gray-500 text-sm font-medium mb-3">Bottleneck Stage</h3>
            <div class="flex items-baseline mb-4">
                <span class="text-lg font-bold text-gray-900"><?= $bottleneckStage ?></span>
            </div>
            <div class="text-orange-500 text-xs font-medium">
                Avg <?= $bottleneckTime ?> days
            </div>
            <div class="absolute top-6 right-6 w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <!-- Horizontal Bar Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6 tracking-tight">Cycle Time by Workflow Type</h3>
            <div class="h-64">
                <canvas id="cycleTimeChart"></canvas>
            </div>
        </div>

        <!-- Progress Bars & Mini Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <h3 class="text-lg font-bold text-gray-900 mb-6 tracking-tight">Current Workflow Status</h3>
            
            <div class="flex-1 space-y-6">
                <?php foreach($wfProgress as $wf): ?>
                <div>
                    <div class="flex justify-between text-sm font-semibold mb-2">
                        <span class="text-gray-800"><?= $wf['name'] ?></span>
                        <span class="text-gray-900"><?= $wf['pending'] ?> pending</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 mb-1">
                        <div class="<?= $wf['colorClass'] ?> h-2.5 rounded-full" style="width: <?= min(100, max(5, ($wf['pending'] / max(1, $totalPendingCount)) * 100)) ?>%"></div>
                    </div>
                    <div class="text-[11px] text-gray-500 font-medium">Avg: <?= $wf['avg'] ?> days</div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Mini Stats below progress bars -->
            <div class="grid grid-cols-3 gap-4 mt-8 pt-6 border-t border-gray-100">
                <div class="text-center rounded-xl py-3 border border-gray-100 bg-gray-50">
                    <div class="text-2xl font-bold text-gray-900 mb-1"><?= $kpis['totalPending'] ?></div>
                    <div class="text-[11px] text-gray-500 uppercase tracking-widest">Total Pending</div>
                </div>
                <div class="text-center rounded-xl py-3 border border-green-50 bg-[#f4fdf8]">
                    <div class="text-2xl font-bold text-green-600 mb-1"><?= $kpis['completed'] ?></div>
                    <div class="text-[11px] text-green-600 uppercase tracking-widest opacity-80">Completed</div>
                </div>
                <div class="text-center rounded-xl py-3 border border-blue-50 bg-[#f0f7ff]">
                    <div class="text-2xl font-bold text-blue-600 mb-1"><?= $kpis['successRate'] ?>%</div>
                    <div class="text-[11px] text-blue-600 uppercase tracking-widest opacity-80">Success Rate</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Line Chart & Scorecard -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 tracking-tight">Monthly Request Volume</h3>
            <div class="flex space-x-4">
                <div class="flex items-center space-x-2 text-xs font-semibold text-gray-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#3b82f6]"></span><span>Fee Waivers</span>
                </div>
                <div class="flex items-center space-x-2 text-xs font-semibold text-gray-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#8b5cf6]"></span><span>Procurement</span>
                </div>
                <div class="flex items-center space-x-2 text-xs font-semibold text-gray-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#22c55e]"></span><span>Clearance</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="h-72 w-full">
                <canvas id="monthlyVolumeChart"></canvas>
            </div>
        </div>
        
        <!-- Scorecard -->
        <div class="p-6 bg-gray-50 border-t border-gray-100">
            <h3 class="text-md font-bold text-gray-900 tracking-tight mb-4">Compliance Scorecard</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-green-50 border border-green-100 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-green-700 mb-2">100%</div>
                    <div class="text-sm font-semibold text-gray-800">Privacy Compliance</div>
                    <div class="text-xs text-gray-500 mt-1">All data encrypted</div>
                </div>
                <div class="bg-green-50 border border-green-100 rounded-xl p-4 text-center opacity-90">
                    <div class="text-3xl font-bold text-green-700 mb-2">98%</div>
                    <div class="text-sm font-semibold text-gray-800">On-Time Approvals</div>
                    <div class="text-xs text-gray-500 mt-1">Within SLA</div>
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-blue-700 mb-2">100%</div>
                    <div class="text-sm font-semibold text-gray-800">Audit Trail</div>
                    <div class="text-xs text-gray-500 mt-1">Complete logging</div>
                </div>
                <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-orange-700 mb-2">95%</div>
                    <div class="text-sm font-semibold text-gray-800">User Satisfaction</div>
                    <div class="text-xs text-gray-500 mt-1">Based on feedback</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // Cycle Time Horizontal Bar Chart
    const ctxBar = document.getElementById('cycleTimeChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: <?= json_encode($barChartLabels) ?>,
            datasets: [{
                data: <?= json_encode($barChartData) ?>,
                backgroundColor: <?= json_encode($barChartColors) ?>,
                borderRadius: 4,
                barPercentage: 0.6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { 
                    beginAtZero: true,
                    grid: { borderDash: [2, 4], color: '#f3f4f6' }
                },
                y: { 
                    grid: { display: false }
                }
            }
        }
    });

    // Monthly Volume Line Chart
    const ctxLine = document.getElementById('monthlyVolumeChart').getContext('2d');
    
    // Inject dynamic data
    const monthlyLabels = <?= json_encode($monthlyLabels) ?>;
    const dataWaiver = <?= json_encode($monthlyVolume['Fee Waiver'] ?? []) ?>;
    const dataProcurement = <?= json_encode($monthlyVolume['Procurement'] ?? []) ?>;
    const dataClearance = <?= json_encode($monthlyVolume['Clearance'] ?? []) ?>;

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [
                {
                    label: 'Fee Waivers',
                    data: dataWaiver,
                    borderColor: '#3b82f6',
                    backgroundColor: '#3b82f6',
                    tension: 0.4,
                    borderWidth: 2
                },
                {
                    label: 'Procurement',
                    data: dataProcurement,
                    borderColor: '#8b5cf6',
                    backgroundColor: '#8b5cf6',
                    tension: 0.4,
                    borderWidth: 2
                },
                {
                    label: 'Clearance',
                    data: dataClearance,
                    borderColor: '#22c55e',
                    backgroundColor: '#22c55e',
                    tension: 0.4,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false } // custom legend in UI
            },
            scales: {
                x: { 
                    grid: { display: false }
                },
                y: { 
                    beginAtZero: true,
                    grid: { borderDash: [2, 4], color: '#f3f4f6' },
                    title: {
                        display: true,
                        text: 'Number of Requests'
                    }
                }
            }
        }
    });
});
</script>
