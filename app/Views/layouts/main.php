<?php 
    $notifications = [];
    if (auth()) {
        $reqModel = new \App\Models\Request();
        $notifications = $reqModel->getNotificationsForUser(auth(), auth_user()['role']);
    }
?>
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
        <aside class="w-64 text-white flex flex-col shadow-xl z-20 flex-shrink-0 print:hidden" style="background-color: #3b5dd9;">
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
                <?php if (auth_user() && !in_array(auth_user()['role'], ['Student', 'HOD', 'Admin'])): ?>
                <a href="<?= url('/approvals') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/approvals') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 hover:bg-blue-700/30 hover:text-white transition-colors' ?>">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="ml-3 font-medium">My Approvals</span>
                </a>
                <?php else: ?>
                    <?php if (auth_user() && auth_user()['role'] !== 'Admin'): ?>
                    <a href="<?= url('/my-requests') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/my-requests') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 hover:bg-blue-700/30 hover:text-white transition-colors' ?>">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <span class="ml-3 font-medium">My Requests</span>
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (auth_user() && !in_array(auth_user()['role'], ['CFO', 'Logistics', 'Admin'])): ?>
                <a href="<?= url('/requests/create') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/requests/create') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 hover:bg-blue-700/30 hover:text-white transition-all duration-200' ?>">
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
                <?php if (auth_user() && auth_user()['role'] === 'Admin'): ?>
                <a href="<?= url('/users') ?>" class="flex items-center px-5 py-3 mx-4 rounded-xl <?= strpos($_SERVER['REQUEST_URI'], '/users') !== false ? 'bg-blue-700/50 shadow-inner text-white block' : 'text-blue-100 opacity-80 hover:bg-blue-700/30 hover:opacity-100 hover:text-white transition-all duration-200' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="ml-3 font-medium">User Management</span>
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
            <header class="bg-white dark:bg-gray-800 px-8 py-4 flex items-center justify-end shadow-sm z-10 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200 print:hidden">
            <div class="flex items-center space-x-6">
                <!-- Help Icon -->
                <button onclick="toggleHelpModal()" class="text-gray-400 hover:text-indigo-600 transition-all p-2 rounded-xl hover:bg-indigo-50 active:scale-95" title="System Guidance">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>

                <!-- Notification Center -->
                <div class="relative">
                    <button onclick="toggleNotifications()" class="text-gray-400 hover:text-indigo-600 transition-all p-2 rounded-xl hover:bg-indigo-50 relative active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <?php if (count($notifications) > 0): ?>
                            <span class="absolute top-1.5 right-1.5 bg-rose-500 text-white text-[9px] font-black w-4 h-4 flex items-center justify-center rounded-full border-2 border-white animate-pulse">
                                <?= count($notifications) ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    
                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" class="hidden absolute right-0 mt-4 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden transform transition-all duration-300 origin-top-right">
                        <div class="px-5 py-4 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                            <h3 class="font-black text-[10px] uppercase tracking-widest text-gray-500">Intelligence Alerts</h3>
                            <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full"><?= count($notifications) ?> New</span>
                        </div>
                        <div class="max-h-96 overflow-y-auto custom-scrollbar">
                            <?php if (empty($notifications)): ?>
                                <div class="p-8 text-center">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-3.586-3.586a2 2 0 00-2.828 0L12 11m0 0l-4-4m0 0L4 11"></path></svg>
                                    </div>
                                    <p class="text-xs font-bold text-gray-400">System is quiet. All clear.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach($notifications as $notif): ?>
                                    <a href="<?= url('/requests/' . $notif['request_id']) ?>" class="block px-5 py-4 hover:bg-indigo-50/30 transition-colors border-b border-gray-50 last:border-0 group">
                                        <div class="flex items-start">
                                            <div class="w-2 h-2 rounded-full bg-indigo-600 mt-1.5 mr-3 group-hover:scale-150 transition-transform"></div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start">
                                                    <span class="text-[10px] font-black uppercase tracking-tight text-indigo-700"><?= $notif['notify_type'] ?></span>
                                                    <span class="text-[9px] font-bold text-gray-400 uppercase"><?= date('H:i', strtotime($notif['submission_date'])) ?></span>
                                                </div>
                                                <p class="text-xs font-black text-gray-900 mt-1"><?= $notif['workflow_name'] ?> Request</p>
                                                <?php if (isset($notif['submitter_name'])): ?>
                                                    <p class="text-[10px] text-gray-500 font-medium">From: <?= $notif['submitter_name'] ?></p>
                                                <?php else: ?>
                                                    <p class="text-[10px] text-gray-500 font-medium italic">Refer: REQ-<?= str_pad($notif['request_id'], 4, '0', STR_PAD_LEFT) ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($notifications)): ?>
                            <a href="<?= url('/approvals') ?>" class="block py-3 text-center text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                View Intelligence Feed
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="flex items-center space-x-3 border-l dark:border-gray-700 pl-6 transition-colors duration-200">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 flex items-center group cursor-pointer"><?= htmlspecialchars(auth_user()['name']) ?> <svg class="w-4 h-4 ml-1 opacity-50 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium tracking-wide"><?= htmlspecialchars(auth_user()['role']) ?></p>
                    </div>
                    <!-- Avatar and Logout -->
                    <div class="relative group cursor-pointer">
                        <div class="w-9 h-9 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm hover:bg-indigo-700 transition">
                            <?= strtoupper(substr(auth_user()['name'], 0, 1)) ?>
                        </div>
                        <div class="absolute right-0 top-full pt-2 w-32 hidden group-hover:block z-20">
                            <div class="bg-white dark:bg-gray-800 rounded-md shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                                <a href="<?= url('/logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Help Modal Structure -->
        <div id="help-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="p-8 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-indigo-600">
                    <div class="text-white">
                        <h2 class="text-2xl font-black tracking-tight">System Intelligence Guide</h2>
                        <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mt-1 opacity-80">Workflow Navigation Support</p>
                    </div>
                    <button onclick="toggleHelpModal()" class="text-white/50 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-4">Request Lifecycle</h4>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-[10px] font-black mr-3 mt-0.5">⏳</span>
                                <div>
                                    <p class="text-xs font-black text-gray-900">Pending / Escalated</p>
                                    <p class="text-[10px] text-gray-500 leading-relaxed font-medium">Currently awaiting review by the designated authority.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] font-black mr-3 mt-0.5">✓</span>
                                <div>
                                    <p class="text-xs font-black text-gray-900">Approved</p>
                                    <p class="text-[10px] text-gray-500 leading-relaxed font-medium">Clearance granted. Official document is now available for download.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="w-5 h-5 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center text-[10px] font-black mr-3 mt-0.5">✗</span>
                                <div>
                                    <p class="text-xs font-black text-gray-900">Rejected</p>
                                    <p class="text-[10px] text-gray-500 leading-relaxed font-medium">Action required. Check the audit trail for reviewer comments.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Intelligence Alert System</h4>
                        <p class="text-[11px] text-gray-600 dark:text-gray-400 font-medium leading-relaxed mb-4">
                            The **Bell icon** tracks requests that specifically require your input. 
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-[10px] font-bold text-gray-500">
                                <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></div>
                                Automatic Escalation Oversight
                            </li>
                            <li class="flex items-center text-[10px] font-bold text-gray-500">
                                <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></div>
                                Budget Overrun Detection
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Global Interactive Scripts -->
        <script>
            function toggleNotifications() {
                const dropdown = document.getElementById('notification-dropdown');
                dropdown.classList.toggle('hidden');
                // Close help modal if open
                const helpModal = document.getElementById('help-modal');
                if (!helpModal.classList.contains('hidden')) helpModal.classList.add('hidden');
            }

            function toggleHelpModal() {
                const modal = document.getElementById('help-modal');
                modal.classList.toggle('hidden');
                // Close notifications if open
                const dropdown = document.getElementById('notification-dropdown');
                if (!dropdown.classList.contains('hidden')) dropdown.classList.add('hidden');
            }

            // ATTACHMENT PREVIEW ENGINE
            function viewAttachment(url) {
                const modal = document.getElementById('attachment-preview-modal');
                const contentArea = document.getElementById('attachment-content');
                const frame = document.getElementById('attachment-frame');
                const image = document.getElementById('attachment-image');
                
                const ext = url.split('.').pop().toLowerCase();
                
                // Reset views
                frame.classList.add('hidden');
                image.classList.add('hidden');
                frame.src = '';
                image.src = '';
                
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    image.src = url;
                    image.classList.remove('hidden');
                } else if (ext === 'pdf') {
                    frame.src = url + '#toolbar=0';
                    frame.classList.remove('hidden');
                } else {
                    // Fallback for others (download)
                    window.open(url, '_blank');
                    return;
                }
                
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeAttachmentPreview() {
                const modal = document.getElementById('attachment-preview-modal');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                // Clear sources to stop any background loading/audio from PDFs
                document.getElementById('attachment-frame').src = '';
                document.getElementById('attachment-image').src = '';
            }

            // Close on click outside
            window.onclick = function(event) {
                const dropdown = document.getElementById('notification-dropdown');
                if (dropdown) {
                    const bell = dropdown.previousElementSibling;
                    if (bell && !bell.contains(event.target) && !dropdown.contains(event.target) && !dropdown.classList.contains('hidden')) {
                        dropdown.classList.add('hidden');
                    }
                }
                
                const previewModal = document.getElementById('attachment-preview-modal');
                if (event.target === previewModal) {
                    closeAttachmentPreview();
                }
            }
        </script>
        <?php endif; ?>

            <!-- Global Attachment Preview Modal -->
            <div id="attachment-preview-modal" class="hidden fixed inset-0 z-[150] flex items-center justify-center p-4 md:p-10 bg-gray-900/80 backdrop-blur-md">
                <div class="bg-white dark:bg-gray-800 w-full max-w-5xl h-full rounded-2xl shadow-3xl overflow-hidden flex flex-col animate-in fade-in zoom-in duration-200">
                    <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            </div>
                            <h3 class="font-black text-xs uppercase tracking-widest text-gray-500">Document Specification Preview</h3>
                        </div>
                        <button onclick="closeAttachmentPreview()" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition-all active:scale-90">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div id="attachment-content" class="flex-1 bg-gray-100 dark:bg-gray-900 overflow-auto flex items-center justify-center relative">
                        <iframe id="attachment-frame" class="hidden w-full h-full border-0" src=""></iframe>
                        <img id="attachment-image" class="hidden max-w-full max-h-full object-contain shadow-2xl" src="" />
                        
                        <!-- Premium Loader -->
                        <div id="preview-loader" class="absolute inset-0 flex items-center justify-center bg-white/50 backdrop-blur-sm hidden pointer-events-none">
                            <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t dark:border-gray-700 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Digital Archive Proof &bull; Secure View</p>
                    </div>
                </div>
            </div>

            <!-- Scrollable Content Viewport -->
            <main class="flex-1 flex overflow-x-hidden overflow-y-auto bg-slate-50 dark:bg-[#111827] relative transition-colors duration-200 print:p-0 print:bg-white <?= auth() ? 'p-8' : '' ?>">
                <div class="w-full h-full">
                    <?= $content ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
