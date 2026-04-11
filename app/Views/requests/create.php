<div class="max-w-2xl mx-auto bg-white rounded-lg shadow overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">Create New Request</h2>
        <a href="/dashboard" class="text-sm font-medium text-blue-600 hover:text-blue-500">&larr; Back</a>
    </div>
    
    <div class="p-6">
        <form action="/requests/store" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Workflow Category</label>
                <select name="workflow_type" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 bg-white">
                    <option value="">-- Select a type --</option>
                    <option value="1">Fee Waiver</option>
                    <option value="2">Procurement</option>
                    <option value="3">Clearance</option>
                </select>
                <p class="mt-2 text-xs text-gray-500 italic">AI routing engine will automatically assign the appropriate approver.</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Priority Level</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-blue-600" name="priority_level" value="Low">
                        <span class="ml-2 text-sm">Low</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-green-600" name="priority_level" value="Medium" checked>
                        <span class="ml-2 text-sm">Medium</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-red-600" name="priority_level" value="High">
                        <span class="ml-2 text-sm font-semibold text-red-600">High (Urgent)</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded shadow hover:bg-blue-700 focus:outline-none transition">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
