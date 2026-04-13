<div class="px-8 py-10 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div class="space-y-2">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Identity Management</h1>
            <p class="text-slate-500 font-medium">Provision roles and verify institutional access levels.</p>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border border-indigo-100">
                <?= count($users) ?> Total Identities
            </div>
        </div>
    </div>

    <!-- User Table -->
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700 overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-gray-800/50 border-b border-slate-100 dark:border-gray-700">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">User Identity</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Institutional Role</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Department</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-gray-700">
                    <?php foreach ($users as $user): ?>
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-gray-700/30 transition-all">
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <span class="text-slate-500 dark:text-gray-300 font-bold text-sm"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 dark:text-gray-100"><?= htmlspecialchars($user['name']) ?></p>
                                    <p class="text-xs text-slate-400 font-medium"><?= htmlspecialchars($user['email']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <?php 
                                $roleClass = match($user['role']) {
                                    'Admin' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'Student' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'Staff' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    default => 'bg-indigo-50 text-indigo-600 border-indigo-100'
                                };
                            ?>
                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider border <?= $roleClass ?>">
                                <?= $user['role'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-bold text-slate-500 dark:text-gray-400 tracking-tight">
                                <?= htmlspecialchars($user['department'] ?? 'General') ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <?php if ($user['user_id'] != auth()): ?>
                                <a href="<?= url('/users/edit/' . $user['user_id']) ?>" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-gray-400 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all active:scale-95 shadow-sm">
                                    Manage Role
                                </a>
                            <?php else: ?>
                                <span class="inline-flex items-center px-4 py-2 bg-slate-50 dark:bg-gray-700/50 border border-slate-100 dark:border-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-300 dark:text-gray-500 cursor-not-allowed">
                                    Current Session
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
