<div class="min-h-screen flex items-center justify-center p-6 bg-slate-50 relative overflow-hidden">
    <!-- Sophisticated Background Mesh -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-5%] right-[-5%] w-[40%] h-[40%] bg-indigo-100/50 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-5%] left-[-5%] w-[40%] h-[40%] bg-blue-100/50 blur-[120px] rounded-full"></div>
    </div>

    <!-- Centered Premium Card -->
    <div class="w-full max-w-5xl bg-white rounded-[2rem] shadow-2xl shadow-slate-200/50 flex flex-col lg:flex-row overflow-hidden relative z-10 border border-slate-100 animate-in fade-in zoom-in duration-700">
        
        <!-- Left Side: Form -->
        <div class="w-full lg:w-1/2 p-10 md:p-14 border-r border-slate-50">
            <!-- Brand Logo -->
            <div class="flex items-center mb-10">
                <span class="text-xl font-black tracking-tighter uppercase"><span class="text-blue-600">PAU</span> <span class="text-slate-800">Workflow</span></span>
            </div>

            <div class="space-y-1 mb-8">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Create Account</h1>
                <p class="text-slate-500 font-medium">Initialize your persistent system access.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl mb-8 text-sm flex items-center shadow-sm animate-in slide-in-from-top-2">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= url('/register') ?>" method="POST" class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 text-[10px] font-black uppercase tracking-widest mb-2 px-1">First Name</label>
                        <input type="text" name="first_name" placeholder="John" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-4 text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all duration-300 placeholder-slate-400">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-[10px] font-black uppercase tracking-widest mb-2 px-1">Last Name</label>
                        <input type="text" name="last_name" placeholder="Doe" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-4 text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all duration-300 placeholder-slate-400">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-700 text-[10px] font-black uppercase tracking-widest mb-2 px-1">Institutional Email</label>
                    <input type="email" name="email" placeholder="name@pau.edu" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-4 text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all duration-300 placeholder-slate-400">
                </div>

                <div>
                    <label class="block text-slate-700 text-[10px] font-black uppercase tracking-widest mb-2 px-1">Security Password</label>
                    <input type="password" name="password" placeholder="••••••••" required minlength="6"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-4 text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all duration-300 placeholder-slate-400">
                </div>

                <div>
                    <label class="block text-slate-700 text-[10px] font-black uppercase tracking-widest mb-2 px-1">Verify Password</label>
                    <input type="password" name="confirm_password" placeholder="••••••••" required minlength="6"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 px-4 text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all duration-300 placeholder-slate-400">
                </div>

                <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-4">
                    <p class="text-[10px] text-indigo-700 font-bold text-center leading-relaxed italic">
                        By joining, you will be assigned standard access privileges. Staff accounts are subject to administrative audit.
                    </p>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-700 hover:from-indigo-700 hover:to-blue-800 text-white font-black py-4.5 rounded-2xl shadow-xl shadow-indigo-500/30 transition-all active:scale-[0.98] uppercase tracking-widest text-sm">
                    Sign Up
                </button>

                <div class="text-center pt-2">
                    <p class="text-slate-500 font-medium">Already have an account? <a href="<?= url('/login') ?>" class="text-indigo-600 font-black hover:underline underline-offset-4">Login</a></p>
                </div>
            </form>
        </div>

        <!-- Right Side: Brand Panel (Blue Profile) -->
        <div class="hidden lg:flex w-1/2 bg-blue-600 relative p-12 items-center justify-center overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute bottom-[-10%] left-[-10%] w-96 h-96 bg-white/10 blur-[100px] rounded-full"></div>
                <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-blue-700/20 blur-[100px] rounded-full"></div>
            </div>

            <div class="relative z-10 w-full text-center">
                <div class="max-w-xs mx-auto mb-10">
                    <h2 class="text-3xl font-black text-white leading-tight tracking-tight mb-4">Automate your <br>institutional process</h2>
                    <p class="text-blue-100/70 font-medium">Join the Unified Intelligence Network for automated clearances and tracking.</p>
                </div>

                <!-- Showcase Mockup -->
                <div class="relative group animate-in slide-in-from-right-12 duration-1000">
                    <div class="absolute -inset-2 bg-white/10 rounded-[2.5rem] blur-xl"></div>
                    <div class="relative bg-white/10 p-2 backdrop-blur-md border border-white/20 rounded-[2.5rem] shadow-3xl overflow-hidden aspect-[4/3] group-hover:scale-[1.02] transition-transform duration-700 hover:-rotate-1">
                        <img src="<?= url('public/assets/images/auth-preview.png') ?>?v=2" alt="Platform Preview" class="w-full h-full object-cover rounded-[2.2rem] shadow-inner">
                    </div>
                </div>

                <!-- Floating Indicator -->
                <div class="absolute -top-6 -right-6 h-12 w-12 bg-white/20 backdrop-blur-md rounded-2xl shadow-xl flex items-center justify-center border border-white/10 animate-pulse">
                    <div class="w-3 h-3 rounded-full bg-white"></div>
                </div>
            </div>
        </div>
    </div>
</div>
