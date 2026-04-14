<div class="px-8 py-10 max-w-7xl mx-auto space-y-12">
    <!-- Header: Mission Status -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Mission Control</h1>
            <p class="text-slate-500 font-medium mt-2">Global oversight & automated institutional intelligence.</p>
        </div>
        
        <div class="flex space-x-4">
            <div class="bg-white dark:bg-gray-800 px-6 py-3 rounded-2xl border border-slate-100 dark:border-gray-700 shadow-sm flex items-center">
                <div class="w-2 h-2 rounded-full bg-emerald-500 mr-3 animate-pulse"></div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">System Health: <span class="text-slate-900 dark:text-white">OPTIMAL</span></span>
            </div>
            <button onclick="window.print()" class="bg-indigo-600 px-6 py-3 rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none flex items-center cursor-pointer hover:bg-indigo-700 active:scale-95 transition-all text-white border-0 group">
                <svg class="w-4 h-4 text-white mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="text-[10px] font-black uppercase tracking-widest">Export Insight</span>
            </button>
        </div>
    </div>

    <!-- Tier 1: System Metrics (Clean KPI strip) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-slate-100 dark:border-gray-700 shadow-sm group hover:border-indigo-500 transition-all duration-300">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-indigo-500 transition-colors">Identities</p>
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight"><?= $metrics['totalUsers'] ?></h3>
                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full">+12%</span>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-slate-100 dark:border-gray-700 shadow-sm group hover:border-indigo-500 transition-all duration-300">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-indigo-500 transition-colors">Active Channels</p>
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight"><?= $metrics['activeWorkflows'] ?></h3>
                <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full">LIVE</span>
            </div>
        </div>
        <a href="<?= url('/requests') ?>" class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-slate-100 dark:border-gray-700 shadow-sm group hover:border-indigo-500 transition-all duration-300 block">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-indigo-500 transition-colors">Total Throughput</p>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight"><?= count($allRequests) ?></h3>
                    <p class="text-[9px] font-black text-indigo-600 uppercase mt-1 opacity-0 group-hover:opacity-100 transition-opacity">Launch Ledger &rarr;</p>
                </div>
                <span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded-full">LIFETIME</span>
            </div>
        </a>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-slate-100 dark:border-gray-700 shadow-sm group hover:border-indigo-500 transition-all duration-300">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-indigo-500 transition-colors">Avg Flow</p>
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">88<span class="text-sm pb-1">%</span></h3>
                <span class="text-[10px] font-bold text-purple-500 bg-purple-50 px-2 py-0.5 rounded-full">OPTIMAL</span>
            </div>
        </div>
    </div>

    <!-- Tier 2: Real-time Intelligence & Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Live Audit Activity (Span 8) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex justify-between items-center px-4">
                <h2 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em]">Institutional Velocity Stream</h2>
                <div class="flex space-x-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-slate-100 dark:border-gray-700 shadow-2xl shadow-slate-200/40 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-30"></div>
                <div class="p-10 space-y-10">
                    <?php if (empty($metrics['recentAudit'])): ?>
                        <div class="text-center py-20 bg-slate-50/50 dark:bg-gray-900/50 rounded-3xl border-2 border-dashed border-slate-200">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No active system signals detected.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($metrics['recentAudit'] as $idx => $audit): ?>
                            <div class="flex group">
                                <div class="flex flex-col items-center mr-8">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-900 dark:bg-indigo-600 flex items-center justify-center text-white font-black text-xs shadow-lg group-hover:scale-110 transition-transform">
                                        <?= strtoupper(substr($audit['action'], 0, 1)) ?>
                                    </div>
                                    <?php if($idx < count($metrics['recentAudit']) - 1): ?>
                                        <div class="w-0.5 h-full bg-slate-100 dark:bg-gray-700 mt-4 group-hover:bg-indigo-200 transition-colors"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 pb-10">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 tracking-widest uppercase mb-1 block"><?= $audit['action'] ?> Event</span>
                                            <p class="text-lg font-black text-slate-900 dark:text-white tracking-tight"><?= $audit['user_name'] ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= date('H:i', strtotime($audit['timestamp'])) ?></p>
                                            <p class="text-[9px] font-bold text-slate-300 uppercase"><?= date('d M Y', strtotime($audit['timestamp'])) ?></p>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-slate-100 dark:border-gray-700 group-hover:border-indigo-100 transition-colors">
                                        <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed font-medium">
                                            Verified <strong class="text-slate-900 dark:text-gray-200"><?= $audit['workflow_name'] ?></strong> transaction with status <span class="bg-white/80 dark:bg-gray-800 px-2 py-0.5 rounded border dark:border-gray-700 italic"><?= $audit['action'] ?></span>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Role Analytics & Insights (Span 4) -->
        <div class="lg:col-span-4 space-y-10">
            <!-- Global Identity Mix -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-slate-100 dark:border-gray-700 shadow-xl p-10 space-y-8">
                <div>
                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Identity Distribution</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Breakdown of institutional roles across the system.</p>
                </div>
                
                <div class="space-y-6">
                    <?php foreach($metrics['deptStats'] as $stat): 
                        $perc = round(($stat['count'] / $metrics['totalUsers']) * 100);
                        $colors = ['bg-indigo-600', 'bg-emerald-600', 'bg-blue-600', 'bg-amber-600', 'bg-rose-600'];
                        $color = $colors[array_search($stat, $metrics['deptStats']) % count($colors)];
                    ?>
                        <div class="group">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest"><?= htmlspecialchars($stat['department'] ?: 'SYSTEM') ?></span>
                                <span class="text-[10px] font-black text-slate-900 dark:text-white"><?= $perc ?>%</span>
                            </div>
                            <div class="w-full bg-slate-50 dark:bg-gray-900 rounded-full h-1.5 overflow-hidden">
                                <div class="<?= $color ?> h-full transition-all duration-1000 group-hover:scale-y-125" style="width: <?= $perc ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="pt-6 border-t border-slate-50 dark:border-gray-700">
                    <a href="<?= url('/users') ?>" class="text-[10px] font-black text-indigo-600 hover:text-indigo-700 uppercase tracking-widest block text-center">Manage Identities &rarr;</a>
                </div>
            </div>

            <!-- Neural Summary -->
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full -mr-16 -mt-16 blur-3xl group-hover:bg-indigo-500/20 transition-all"></div>
                
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400">Heuristic Summary</span>
                </div>
                
                <div class="space-y-6">
                    <?php foreach(array_slice($insights['narrative'] ?? [], 0, 3) as $line): ?>
                        <div class="flex items-start">
                             <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 mr-4 flex-shrink-0"></div>
                             <p class="text-xs font-bold leading-relaxed text-slate-300 italic opacity-80 group-hover:opacity-100 transition-opacity">
                                "<?= htmlspecialchars($line) ?>"
                             </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
