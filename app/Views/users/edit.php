<div class="px-8 py-10 max-w-6xl mx-auto">
    <!-- Back Navigation -->
    <a href="<?= url('/users') ?>" class="inline-flex items-center text-xs font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors mb-10 group">
        <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Institutional Identities
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Identity Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700 overflow-hidden sticky top-10 animate-in fade-in slide-in-from-left-4 duration-700">
                <div class="h-32 bg-gradient-to-br from-indigo-600 to-blue-700 relative">
                    <div class="absolute -bottom-10 left-10">
                        <div class="w-20 h-20 rounded-3xl bg-white dark:bg-gray-800 p-1 shadow-xl">
                            <div class="w-full h-full rounded-2xl bg-slate-100 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-indigo-600 dark:text-gray-300 font-black text-3xl"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-10 pt-16 pb-10">
                    <h3 class="text-xl font-black text-slate-900 dark:text-gray-100 tracking-tight leading-tight"><?= htmlspecialchars($user['name']) ?></h3>
                    <p class="text-slate-400 font-medium text-xs mb-6"><?= htmlspecialchars($user['email']) ?></p>
                    
                    <div class="space-y-4">
                        <div class="bg-slate-50 dark:bg-gray-700/30 p-4 rounded-2xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Current Role</p>
                            <p class="text-sm font-black text-indigo-600"><?= $user['role'] ?></p>
                        </div>
                        <div class="bg-slate-50 dark:bg-gray-700/30 p-4 rounded-2xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Affiliation</p>
                            <p class="text-sm font-black text-slate-700 dark:text-gray-300"><?= htmlspecialchars($user['department'] ?? 'General') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Identity Control Center -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 dark:border-gray-700 overflow-hidden animate-in fade-in slide-in-from-right-4 duration-700">
                <div class="px-10 py-8 border-b border-slate-50 dark:border-gray-700 flex justify-between items-center bg-slate-50/50 dark:bg-gray-800/50">
                    <div>
                        <h2 class="text-lg font-black text-slate-900 dark:text-gray-100 tracking-tight">Identity Control</h2>
                        <p class="text-xs text-slate-500 font-medium">Re-provision access levels and institutional domains.</p>
                    </div>
                    <div class="bg-indigo-600 text-[10px] text-white font-black px-3 py-1 rounded-full uppercase tracking-widest">
                        SECURE
                    </div>
                </div>

                <div class="p-10">
                    <form action="<?= url('/users/update/' . $user['user_id']) ?>" method="POST" class="space-y-10">
                        <!-- Role Selection -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-slate-900 dark:text-gray-300 text-sm font-black tracking-tight mb-2">Institutional Domain</label>
                                <p class="text-xs text-slate-500 mb-6">Assigning a domain determines which workflows the user can initiate or approve.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php 
                                    $roleDetails = [
                                        'Student' => 'Primary submitter for clearances and basic requests.',
                                        'Staff' => 'Institutional processes, procurement initiator.',
                                        'HOD' => 'Departmental oversight and first-level approvals.',
                                        'Finance Officer' => 'Budget management and payment clearances.',
                                        'Registry' => 'Official status and clearance verification.',
                                        'Admin' => 'Global system oversight and configuration.'
                                    ];
                                    foreach ($roles as $role): 
                                    ?>
                                    <label class="relative flex items-start p-4 rounded-2xl border-2 transition-all cursor-pointer group <?= $user['role'] === $role ? 'border-indigo-600 bg-indigo-50/50' : 'border-slate-100 hover:border-slate-200 bg-white' ?>">
                                        <input type="radio" name="role" value="<?= $role ?>" <?= $user['role'] === $role ? 'checked' : '' ?> class="sr-only">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-black text-slate-900 uppercase tracking-wider"><?= $role ?></span>
                                                <?php if($user['role'] === $role): ?>
                                                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-[10px] text-slate-500 font-medium line-clamp-2"><?= $roleDetails[$role] ?? 'Specialized approver role.' ?></p>
                                        </div>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Department -->
                            <div class="pt-6">
                                <label class="block text-slate-900 dark:text-gray-300 text-sm font-black tracking-tight mb-2">Institutional Branch (Department)</label>
                                <input type="text" name="department" value="<?= htmlspecialchars($user['department'] ?? 'General') ?>" 
                                    class="w-full bg-slate-50 border border-slate-200 dark:bg-gray-700 dark:border-gray-600 rounded-2xl py-4 px-6 text-slate-900 dark:text-gray-100 font-bold outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50 dark:border-gray-700">
                            <button type="submit" class="w-full bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-700 text-white font-black py-5 rounded-3xl shadow-xl transition-all active:scale-[0.98] uppercase tracking-widest text-sm flex items-center justify-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.355r2.754-4.704A5.991 5.991 0 0012 15a5.991 5.991 0 00-2.754 1.651L12 21.355z"></path></svg>
                                Commit Security Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
