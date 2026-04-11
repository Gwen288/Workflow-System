<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Create Account</h2>
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>
    
    <form action="<?= url('/register') ?>" method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Full Name</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition" 
                id="name" type="text" name="name" placeholder="John Doe" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition" 
                id="email" type="email" name="email" placeholder="john@pau.edu" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="role">Requested Role</label>
            <div class="relative">
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition" 
                    id="role" name="role" required>
                    <option value="" disabled selected>Select a role...</option>
                    <option value="Student">Student</option>
                    <option value="HOD">Head of Department (HOD)</option>
                    <option value="Staff">General Staff</option>
                    <option value="Finance Officer">Finance Officer</option>
                    <option value="Admin">System Administrator</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition" 
                id="password" type="password" name="password" placeholder="******************" required minlength="6">
        </div>
        <div class="flex flex-col items-center justify-between mt-2 space-y-4">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded w-full focus:outline-none focus:shadow-outline transition shadow" type="submit">
                Register
            </button>
            <p class="text-sm text-gray-600">
                Already have an account? <a href="<?= url('/login') ?>" class="text-blue-600 hover:text-blue-800 font-semibold transition">Sign In</a>
            </p>
        </div>
    </form>
</div>
