<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Create New Request</h1>
        <p class="text-gray-500 mt-1">Submit a new workflow request for approval</p>
    </div>
    
    <form action="<?= url('/requests/store') ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden text-sm">
        <div class="p-8">
            <!-- Form Card 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Request Type -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Request Type</label>
                    <div class="relative">
                        <select name="workflow_type" id="workflow_type" required class="block appearance-none w-full bg-white border border-gray-200 text-gray-700 py-3 px-4 rounded-lg leading-tight focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 focus:bg-white shadow-sm transition">
                            <option value="" disabled selected>Select a request type...</option>
                            <?php foreach($workflows as $wf): ?>
                                <option value="<?= $wf['workflow_id'] ?>"><?= htmlspecialchars($wf['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Universal Details -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Student ID</label>
                    <input type="text" name="metadata[student_id]" placeholder="e.g., PAU2024001" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Student Name</label>
                    <input type="text" name="metadata[student_name]" value="<?= htmlspecialchars(auth_user()['name']) ?>" readonly class="appearance-none block w-full bg-gray-50 text-gray-800 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white shadow-sm cursor-not-allowed">
                </div>
            </div>
            
            <hr class="border-gray-100 my-8">

            <!-- DYNAMIC BLOCKS -->
            <div id="dynamic-container" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- This will be populated or unhidden by JS depending on the selection -->
            </div>

            <!-- Dynamic Fields: Fee Waiver -->
            <div id="fields_1" class="dynamic-fields hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 w-full">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Current Fee Amount (GHS)</label>
                    <input type="number" step="0.01" name="metadata[fee_current_fee]" placeholder="0.00" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Requested Adjustment (GHS)</label>
                    <input type="number" step="0.01" name="metadata[fee_requested_adjustment]" placeholder="0.00" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Reason for Adjustment</label>
                    <div class="relative">
                        <select name="metadata[fee_reason]" class="block appearance-none w-full bg-white border border-gray-200 text-gray-700 py-3 px-4 rounded-lg leading-tight focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                            <option value="">Select reason...</option>
                            <option value="financial_hardship">Financial Hardship</option>
                            <option value="merit_scholarship">Merit Scholarship applied late</option>
                            <option value="billing_error">Billing Error</option>
                            <option value="other">Other</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Justification</label>
                    <textarea name="metadata[fee_justification]" rows="4" placeholder="Detailed justification..." class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4"></textarea>
                </div>
            </div>

            <!-- Dynamic Fields: Clearance Form -->
            <div id="fields_3" class="dynamic-fields hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 w-full">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Areas to Clear</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer bg-white"><input type="checkbox" name="metadata[clearance_library]" value="yes" class="form-checkbox h-4 w-4 text-blue-600"><span class="ml-2 font-medium text-gray-700">Library</span></label>
                        <label class="inline-flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer bg-white"><input type="checkbox" name="metadata[clearance_dormitory]" value="yes" class="form-checkbox h-4 w-4 text-blue-600"><span class="ml-2 font-medium text-gray-700">Dormitory</span></label>
                        <label class="inline-flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer bg-white"><input type="checkbox" name="metadata[clearance_finance]" value="yes" class="form-checkbox h-4 w-4 text-blue-600"><span class="ml-2 font-medium text-gray-700">Finance</span></label>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Reason for Clearance</label>
                    <input type="text" name="metadata[clearance_reason]" placeholder="e.g. Graduation, Transfer, Leave of Absence" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                </div>
            </div>

            <!-- Dynamic Fields: Letters (Introductory, Transcript, English Proficiency) -->
            <div id="fields_letters" class="dynamic-fields hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 w-full">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Addressee Name / Institution</label>
                    <input type="text" name="metadata[letter_addressee]" placeholder="e.g. HR Manager, Google Corp" class="appearance-none block w-full bg-white border border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-gray-700">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Delivery Method</label>
                    <div class="relative">
                        <select name="metadata[letter_delivery]" class="block appearance-none w-full bg-white border border-gray-200 text-gray-700 py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                            <option value="email">Email Directly</option>
                            <option value="pickup">Physical Pickup</option>
                            <option value="mail">Postal Mail</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Number of Copies</label>
                    <input type="number" name="metadata[letter_copies]" value="1" min="1" max="10" class="appearance-none block w-full bg-white border border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-gray-700">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Delivery Details / Postal Address / Email</label>
                    <textarea name="metadata[letter_contact]" rows="2" placeholder="Where should we send this?" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:border-blue-400 focus:ring-blue-100"></textarea>
                </div>
            </div>
            
            <!-- File Upload -->
            <div class="my-8">
                <label class="block text-gray-700 font-semibold mb-3">Supporting Documents (Optional)</label>
                <div class="w-full flex justify-center px-6 py-10 border-2 border-blue-200 border-dashed rounded-xl bg-[#f8fbff] hover:bg-[#f0f6ff] transition-colors relative cursor-pointer" id="drop-area">
                    <input type="file" name="attachment" id="file-input" class="absolute w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx" />
                    <div class="space-y-2 text-center flex flex-col items-center">
                        <div class="text-blue-500 bg-white shadow-sm rounded-full w-12 h-12 flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <div class="text-gray-700 font-medium">Drag and drop files here or click to browse</div>
                        <p class="text-xs text-gray-400">PDF, DOC, DOCX up to 10MB</p>
                        <div class="mt-4 inline-block">
                            <button type="button" class="bg-blue-50 text-blue-600 font-semibold px-4 py-2 rounded-lg border border-blue-100 pointer-events-none" id="file-btn-text">Choose Files</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Privacy Notice -->
            <div class="bg-[#f0f7ff] border border-blue-100 rounded-xl p-4 flex items-start space-x-3 mb-2">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div class="text-sm text-blue-800">
                    <span class="font-bold">Privacy Notice:</span> This request may contain sensitive student data. All approvals are logged and comply with privacy-by-design requirements.
                </div>
            </div>
            
        </div>
        
        <!-- Footer / Action -->
        <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors shadow-sm inline-block">
                Submit Request
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('file-input').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            document.getElementById('file-btn-text').textContent = e.target.files[0].name;
            document.getElementById('file-btn-text').classList.add('bg-blue-600', 'text-white');
            document.getElementById('file-btn-text').classList.remove('bg-blue-50', 'text-blue-600');
        } else {
            document.getElementById('file-btn-text').textContent = 'Choose Files';
            document.getElementById('file-btn-text').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('file-btn-text').classList.add('bg-blue-50', 'text-blue-600');
        }
    });

    const workflowSelect = document.getElementById('workflow_type');
    const dynamicContainer = document.getElementById('dynamic-container');
    const fieldsWaiver = document.getElementById('fields_1');
    const fieldsClearance = document.getElementById('fields_3');
    const fieldsLetters = document.getElementById('fields_letters');

    workflowSelect.addEventListener('change', function() {
        const val = this.value;
        const text = this.options[this.selectedIndex].text.toLowerCase();
        
        // Hide all custom fields
        dynamicContainer.innerHTML = '';
        
        if (text.includes('waiver')) {
            dynamicContainer.appendChild(fieldsWaiver);
            fieldsWaiver.classList.remove('hidden');
        } else if (text.includes('clearance')) {
            dynamicContainer.appendChild(fieldsClearance);
            fieldsClearance.classList.remove('hidden');
        } else if (text.includes('letter') || text.includes('transcript')) {
            dynamicContainer.appendChild(fieldsLetters);
            fieldsLetters.classList.remove('hidden');
        }
    });
</script>

