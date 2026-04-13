<div class="min-h-screen flex items-center justify-center p-6 bg-slate-50 relative overflow-hidden">
    <!-- Sophisticated Background Mesh -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-100/50 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-100/50 blur-[120px] rounded-full"></div>
    </div>

    <!-- Centered Premium Card -->
    <div class="w-full max-w-5xl bg-white rounded-[2rem] shadow-2xl shadow-slate-200/50 flex flex-col lg:flex-row overflow-hidden relative z-10 border border-slate-100 animate-in fade-in zoom-in duration-700">
        
        <!-- Left Side: Form -->
        <div class="w-full lg:w-1/2 p-10 md:p-14 border-r border-slate-50">
            <!-- Brand Logo -->
            <div class="flex items-center mb-12">
                <span class="text-xl font-black tracking-tighter uppercase"><span class="text-blue-600">PAU</span> <span class="text-slate-800">Workflow</span></span>
            </div>

            <div class="space-y-1 mb-10">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Welcome Back</h1>
                <p class="text-slate-500 font-medium">Log in to manage your operations center.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl mb-8 text-sm flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= url('/login') ?>" method="POST" class="space-y-6">
                <div>
                    <label class="block text-slate-700 text-xs font-black uppercase tracking-widest mb-2 px-1">Institutional Email</label>
                    <input type="email" name="email" placeholder="name@pau.edu" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-5 text-slate-900 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all duration-300 placeholder-slate-400">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="block text-slate-700 text-xs font-black uppercase tracking-widest">Password</label>
                        <a href="#" class="text-xs font-black text-blue-600 hover:text-blue-700 transition-colors uppercase tracking-widest">Forgot Password?</a>
                    </div>
                    <div class="relative group">
                        <input type="password" name="password" id="password" placeholder="••••••••" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-5 text-slate-900 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all duration-300 placeholder-slate-400">
                        <button type="button" onclick="togglePassword()" id="toggleBtn" class="absolute right-5 top-4 text-slate-400 hover:text-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path id="eyeIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path id="eyePath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center px-1">
                    <input type="checkbox" id="remember" class="w-5 h-5 text-blue-600 bg-slate-50 border-slate-300 rounded-lg focus:ring-blue-500 cursor-pointer">
                    <label for="remember" class="ml-3 text-sm font-bold text-slate-600 cursor-pointer">Remember Me</label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-black py-4.5 rounded-2xl shadow-xl shadow-blue-500/30 transition-all active:scale-[0.98] uppercase tracking-widest text-sm">
                    Login
                </button>

                <!-- Professional Divider -->
                <div class="flex items-center space-x-4 py-4">
                    <div class="flex-1 h-px bg-slate-100"></div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Authorized Access Only</span>
                    <div class="flex-1 h-px bg-slate-100"></div>
                </div>

                <div class="text-center pt-2">
                    <p class="text-slate-500 font-medium">Don't have an account? <a href="<?= url('/register') ?>" class="text-blue-600 font-black hover:underline underline-offset-4">Register Now.</a></p>
                </div>
            </form>
        </div>

        <!-- Right Side: Platform Preview (Blue Profile) -->
        <div class="hidden lg:flex w-1/2 bg-blue-600 relative p-12 items-center justify-center overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-[-20%] right-[-10%] w-96 h-96 bg-white/10 blur-[100px] rounded-full"></div>
                <div class="absolute bottom-[-20%] left-[-10%] w-96 h-96 bg-blue-500/20 blur-[100px] rounded-full"></div>
            </div>

            <div class="relative z-10 w-full text-center">
                <div class="max-w-xs mx-auto mb-12">
                    <h2 class="text-3xl font-black text-white leading-tight tracking-tight mb-4">Automate your <br>institutional process</h2>
                    <p class="text-blue-100/70 font-medium">Unified Intelligence for automated clearances and tracking.</p>
                </div>

                <!-- Nice Page Preview Mockup -->
                <div class="relative group animate-in slide-in-from-right-12 duration-1000">
                    <div class="absolute -inset-2 bg-white/10 rounded-[2.5rem] blur-xl"></div>
                    <div class="relative bg-white/10 p-2 backdrop-blur-md border border-white/20 rounded-[2.5rem] shadow-3xl overflow-hidden aspect-[4/3] group-hover:scale-[1.02] transition-transform duration-700 hover:rotate-1">
                        <img src="<?= url('public/assets/images/auth-preview.png') ?>?v=2" alt="Platform Preview" class="w-full h-full object-cover rounded-[2.2rem] shadow-inner">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyePath = document.getElementById('eyePath');
    
    if (input.type === 'password') {
        input.type = 'text';
        // Eye-off style
        eyeIcon.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L1 1m11 18l10.5-10.5'); // Simplified stroke change
    } else {
        input.type = 'password';
        eyeIcon.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
    }
}
</script>
