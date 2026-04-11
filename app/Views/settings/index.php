<div class="px-2 max-w-5xl mx-auto pb-10">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">Settings</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Manage your account and notification preferences</p>
        </div>
        <button id="saveSettingsBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm font-medium transition-colors duration-200" onclick="document.getElementById('profileForm').submit();">
            Save Changes
        </button>
    </div>

    <!-- Profile Information Form -->
    <form id="profileForm" method="POST" action="/settings/profile">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-colors duration-200">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-3 mb-6">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Profile Information</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Role</label>
                        <input type="text" readonly value="<?= htmlspecialchars($user['role']) ?>" class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 rounded-lg px-4 py-2.5 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Department</label>
                        <input type="text" readonly value="<?= strpos($user['role'], 'Finance') !== false ? 'Finance Department' : 'Administration' ?>" class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 rounded-lg px-4 py-2.5 cursor-not-allowed">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Notification Preferences -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-colors duration-200">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-6">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Notification Preferences</h2>
            </div>
            
            <div class="space-y-6 divide-y divide-gray-100 dark:divide-gray-700">
                <!-- Toggle Item -->
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Email Notifications</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications via email</p>
                    </div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 relative"></div>
                    </label>
                </div>
                
                <!-- Toggle Item -->
                <div class="pt-6 flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Weekly Reports</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Get weekly summary of workflow activities</p>
                    </div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 relative"></div>
                    </label>
                </div>

                <!-- Toggle Item -->
                <div class="pt-6 flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Overdue Request Alerts</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Alert when requests exceed 7 days</p>
                    </div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 relative"></div>
                    </label>
                </div>

                <!-- Toggle Item -->
                <div class="pt-6 flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">New Request Alerts</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Notify when new requests are assigned</p>
                    </div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 relative"></div>
                    </label>
                </div>

                <!-- Toggle Item -->
                <div class="pt-6 flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Approval Reminders</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Daily reminders for pending approvals</p>
                    </div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 relative"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Interface Options -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-colors duration-200">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-6">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Appearance</h2>
            </div>
            
            <div class="space-y-6">
                <!-- Theme Toggle -->
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200">Dark Mode</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Toggle dark styling for the Dashboard environment</p>
                    </div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" id="themeToggleSwitch" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 relative"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Security -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden transition-colors duration-200">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-6">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Security</h2>
            </div>
            
            <div class="space-y-8">
                <!-- Change password -->
                <div>
                    <button class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-200 px-5 py-2.5 rounded-lg shadow-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Change Password
                    </button>
                </div>

                <!-- 2FA -->
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">Two-Factor Authentication</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Add an extra layer of security to your account</p>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm font-medium transition">
                        Enable 2FA
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Dark mode toggle logic
    const themeToggleBtn = document.getElementById('themeToggleSwitch');

    // Check if set
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleBtn.checked = true;
    }

    themeToggleBtn.addEventListener('change', function() {
        if (this.checked) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    });
</script>
