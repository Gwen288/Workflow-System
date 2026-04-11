<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Workflow Automation System</title>
    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="text-gray-800">
    <nav class="bg-blue-600 p-4 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/dashboard" class="text-2xl font-bold tracking-tight">DWAS</a>
            <div>
                <?php if (auth()): ?>
                    <span class="mr-4">Welcome, <?= htmlspecialchars(auth_user()['name']) ?> (<?= htmlspecialchars(auth_user()['role']) ?>)</span>
                    <a href="/logout" class="bg-blue-700 px-4 py-2 rounded shadow hover:bg-blue-800 transition">Logout</a>
                <?php else: ?>
                    <a href="/login" class="bg-blue-700 px-4 py-2 rounded shadow hover:bg-blue-800 transition">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container mx-auto mt-8 p-4">
        <?= $content ?>
    </main>

    <footer class="text-center mt-12 text-sm text-gray-500 pb-4">
        &copy; <?= date('Y') ?> Advanced AI Workflow Automation System
    </footer>
</body>
</html>
