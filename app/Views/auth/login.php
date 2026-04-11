<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">System Login</h2>
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>
    
    <form action="/login" method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">User Email</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition" 
                id="email" type="email" name="email" placeholder="email@pau.edu" required value="samuel@pau.edu">
            <div class="text-xs text-gray-500 mt-3 p-3 bg-gray-50 border rounded uppercase tracking-wide">
                <p class="font-bold text-gray-700 mb-1">Demo Accounts:</p>
                <div class="grid grid-cols-2 gap-2">
                    <p><b>Student:</b> alice.student@pau.edu</p>
                    <p><b>HOD:</b> jane.doe@pau.edu</p>
                    <p><b>Finance:</b> ama@pau.edu</p>
                    <p><b>Library:</b> library@pau.edu</p>
                    <p><b>CFO:</b> samuel@pau.edu</p>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full focus:outline-none focus:shadow-outline transition shadow" type="submit">
                Sign In
            </button>
        </div>
    </form>
</div>
