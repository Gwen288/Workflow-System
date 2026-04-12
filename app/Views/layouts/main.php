<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Workflow Automation System</title>
    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        };
        // Apply theme early to avoid FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="text-gray-800 bg-gray-50 dark:bg-gray-900 dark:text-gray-100 h-screen overflow-hidden transition-colors duration-200">
    <!-- Layout Wrapper -->
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 w-full transition-colors duration-200">
        <?php if (auth()): ?>
        <!-- Sidebar -->
        <aside class="w-64 text-white flex flex-col shadow-xl z-20 flex-shrink-0" style="background-color: #3b5dd9;">
            <div class="p-6 flex items-center mb-4">
                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center font-bold text-lg mr-3 shadow-sm">
                    PA
                </div>
                <div>
                    <h1 class="text-xl font-semibold tracking-tight">PAU Workflow</h1>
                    <span class="text-xs text-blue-200 mt-0.5 block opacity-80">v1.0.0</span>
                </div>
            </div>
            
            <nav class="flex-1 mt-2 space-y-1 overflow-y-auto custom-scrollbar">
                <a href="<?= url('/dashboard') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'bg-blue-700/50 shadow-inner block' : 'hover:bg-blue-700/30 transition-colors' ?>">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>
                <?php if (!auth_user() || !in_array(auth_user()['role'], ['Student', 'HOD'])): ?>
                <a href="<?= url('/approvals') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/approvals') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 hover:bg-blue-700/30 hover:text-white transition-colors' ?>">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="ml-3 font-medium">My Approvals</span>
                </a>
                <?php else: ?>
                <a href="<?= url('/my-requests') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/my-requests') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 hover:bg-blue-700/30 hover:text-white transition-colors' ?>">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    <span class="ml-3 font-medium">My Requests</span>
                </a>
                <?php endif; ?>
                <?php if (!auth_user() || auth_user()['role'] !== 'CFO'): ?>
                <a href="<?= url('/requests/create') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/requests/create') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 hover:bg-blue-700/30 hover:text-white transition-colors' ?>">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="ml-3 font-medium">Create Request</span>
                </a>
                <?php endif; ?>
                <?php if (!auth_user() || auth_user()['role'] !== 'Student'): ?>
                <a href="<?= url('/audit') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/audit') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 hover:bg-blue-700/30 hover:text-white transition-colors' ?>">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span class="ml-3 font-medium">Audit Trail</span>
                </a>
                <?php if (auth_user() && auth_user()['role'] !== 'HOD'): ?>
                <a href="<?= url('/analytics') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/analytics') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 hover:bg-blue-700/30 hover:text-white transition-colors' ?>">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="ml-3 font-medium">Analytics</span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                <a href="<?= url('/settings') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/settings') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 hover:bg-blue-700/30 hover:text-white transition-colors' ?>">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="ml-3 font-medium">Settings</span>
                </a>
            </nav>
            <div class="p-6 text-sm text-blue-200 opacity-60 text-center">
                &copy; <?= date('Y') ?> DWAS
            </div>
        </aside>
        <?php endif; ?>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php if (auth()): ?>
            <!-- Top Navbar -->
            <header class="bg-white dark:bg-gray-800 px-8 py-4 flex items-center justify-end shadow-sm z-10 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                <div class="flex items-center space-x-6">
                    <!-- Help Icon -->
                    <button class="text-gray-500 hover:text-gray-800 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                    <!-- Notification -->
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-800 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </button>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full border-2 border-white">4</span>
                    </div>
                    
                    <!-- User Profile -->
                    <div class="flex items-center space-x-3 border-l dark:border-gray-700 pl-6 transition-colors duration-200">
                        <?php if (auth()): ?>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 flex items-center group cursor-pointer"><?= htmlspecialchars(auth_user()['name']) ?> <svg class="w-4 h-4 ml-1 opacity-50 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium tracking-wide"><?= htmlspecialchars(auth_user()['role']) ?></p>
                        </div>
                        <!-- Avatar and Logout -->
                        <div class="relative group cursor-pointer">
                            <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm hover:bg-blue-700 transition">
                                <?= strtoupper(substr(auth_user()['name'], 0, 1)) ?>
                            </div>
                            <div class="absolute right-0 top-full pt-2 w-32 hidden group-hover:block z-20">
                                <div class="bg-white dark:bg-gray-800 rounded-md shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                                    <a href="<?= url('/logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Logout</a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </header>
            <?php endif; ?>

            <!-- Scrollable Content Viewport -->
            <main class="flex-1 flex items-center justify-center overflow-x-hidden overflow-y-auto bg-slate-50 dark:bg-[#111827] relative p-8 transition-colors duration-200">
                <div class="w-full <?= !auth() ? 'max-w-md' : 'h-full' ?>">
                    <?= $content ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
