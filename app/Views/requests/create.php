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
                <?php if(auth_user()['role'] === 'Student'): ?>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Student ID</label>
                    <input type="text" name="metadata[student_id]" placeholder="e.g., PAU2024001" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Student Name</label>
                    <input type="text" name="metadata[student_name]" value="<?= htmlspecialchars(auth_user()['name']) ?>" readonly class="appearance-none block w-full bg-gray-50 text-gray-800 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white shadow-sm cursor-not-allowed" required>
                </div>
                <?php else: ?>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Staff ID / Department Code</label>
                    <input type="text" name="metadata[staff_id]" placeholder="e.g., HOD-5501" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Staff Name</label>
                    <input type="text" name="metadata[staff_name]" value="<?= htmlspecialchars(auth_user()['name']) ?>" readonly class="appearance-none block w-full bg-gray-50 text-gray-800 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white shadow-sm cursor-not-allowed" required>
                </div>
                <?php endif; ?>
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
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
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
            
            <!-- Dynamic Fields: Budget -->
            <div id="fields_budget" class="dynamic-fields hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 w-full">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Department Code / Account</label>
                    <input type="text" name="metadata[budget_department]" placeholder="e.g., COMP-SCI" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Fiscal Year</label>
                    <input type="text" name="metadata[budget_fiscal_year]" placeholder="e.g., 2026/2027" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Amount Requested (GHS)</label>
                    <input type="number" step="0.01" name="metadata[budget_amount]" placeholder="0.00" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:col-span-2">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Target Procurement Item 1</label>
                        <input type="text" name="metadata[budget_item_1]" placeholder="First expected purchase" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Target Procurement Item 2</label>
                        <input type="text" name="metadata[budget_item_2]" placeholder="Second expected purchase" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Budget Justification <span class="text-blue-500 font-normal italic">(Include two items that will be on your procurement form)</span></label>
                    <textarea name="metadata[budget_justification]" rows="3" placeholder="Explain the need for this budget and list at least two specific items intended for procurement..." class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:border-blue-400 focus:ring-blue-100"></textarea>
                </div>
            </div>

            <!-- Dynamic Fields: Procurement -->
            <div id="fields_procurement" class="dynamic-fields hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 w-full">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Item Descriptions</label>
                    <textarea name="metadata[procurement_items]" id="procurement_items" rows="3" placeholder="List items, quantities, and specific requirements..." class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:border-blue-400 focus:ring-blue-100"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Estimated Total Cost (GHS)</label>
                    <input type="number" step="0.01" name="metadata[procurement_cost]" id="procurement_cost" placeholder="0.00" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Vendor Preferences (if any)</label>
                    <input type="text" name="metadata[procurement_vendor]" placeholder="e.g., Office Supplies Ltd" class="appearance-none block w-full bg-white border border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-gray-700">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Budget Reference (Source of Funding)</label>
                    <div class="relative">
                        <select name="metadata[budget_reference_id]" id="budget_reference_select" class="block appearance-none w-full bg-white border border-gray-200 text-gray-700 py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                            <option value="">-- No linked budget --</option>
                            <?php foreach ($approvedBudgets as $budget): 
                                $bMeta = json_decode($budget['metadata'], true);
                                $items = array_filter([$bMeta['budget_item_1'] ?? '', $bMeta['budget_item_2'] ?? '']);
                                $itemsStr = !empty($items) ? " [" . implode(', ', $items) . "]" : "";
                                $displayLabel = "Budget #" . $budget['request_id'] . " - GHS " . number_format(($bMeta['budget_amount'] ?? 0), 2) . " (" . ($bMeta['budget_fiscal_year'] ?? 'N/A') . ")" . $itemsStr;
                            ?>
                                <option value="<?= $budget['request_id'] ?>"><?= htmlspecialchars($displayLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>

                <!-- Budget Details Snapshot -->
                <div id="budget-snapshot" class="md:col-span-2 hidden bg-blue-50/50 border border-blue-100 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-blue-800 font-bold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Approved Budget Snapshot (<span id="snap-id"></span>)
                        </h4>
                        <span id="snap-year" class="text-xs font-bold bg-blue-100 text-blue-700 px-2 py-1 rounded uppercase tracking-wider"></span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded-lg border border-blue-50 shadow-sm">
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Approved Amount</span>
                            <span id="snap-amount" class="text-xl font-black text-gray-900"></span>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-blue-50 shadow-sm">
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</span>
                            <span class="inline-flex items-center text-green-600 font-bold text-sm">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Fully Approved
                            </span>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-blue-50 shadow-sm">
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Department</span>
                            <span id="snap-dept" class="text-sm font-bold text-gray-800"></span>
                        </div>
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white p-3 rounded-lg border border-blue-50 shadow-sm">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Target Item 1</span>
                                <span id="snap-item-1" class="text-sm font-bold text-blue-900 italic"></span>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-blue-50 shadow-sm">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Target Item 2</span>
                                <span id="snap-item-2" class="text-sm font-bold text-blue-900 italic"></span>
                            </div>
                        </div>
                        <div class="md:col-span-2 bg-white/50 p-3 rounded-lg border border-blue-50">
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Justification & Procurement Items</span>
                            <p id="snap-justification" class="text-sm text-gray-700 italic leading-relaxed"></p>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Urgency Level</label>
                    <div class="relative">
                        <select name="metadata[procurement_urgency]" class="block appearance-none w-full bg-white border border-gray-200 text-gray-700 py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 shadow-sm transition">
                            <option value="routine" selected>Routine (1-2 Weeks)</option>
                            <option value="urgent">Urgent (Within 48 Hours)</option>
                            <option value="critical">Critical (Immediate approval needed)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>
            </div>
            
            <!-- File Upload -->
            <div class="my-8">
                <label class="block text-gray-700 font-semibold mb-3">Supporting Documents (Optional)</label>
                <div class="w-full flex justify-center px-6 py-10 border-2 border-blue-200 border-dashed rounded-xl bg-[#f8fbff] hover:bg-[#f0f6ff] transition-colors relative cursor-pointer" id="drop-area">
                    <input type="file" name="attachment" id="file-input" class="absolute w-full h-full opacity-0 cursor-pointer" accept=".pdf" />
                    <div class="space-y-2 text-center flex flex-col items-center">
                        <div class="text-blue-500 bg-white shadow-sm rounded-full w-12 h-12 flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <div class="text-gray-700 font-medium">Drag and drop files here or click to browse</div>
                        <p class="text-xs text-gray-400">PDF only up to 10MB</p>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('file-input').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            const file = e.target.files[0];
            const ext = file.name.split('.').pop().toLowerCase();
            
            if (ext !== 'pdf') {
                Swal.fire({
                    title: 'Invalid File Type',
                    text: 'Supporting documents must be in PDF format for in-app viewing. Please convert your file and try again.',
                    icon: 'error',
                    confirmButtonColor: '#3B82F6'
                });
                this.value = '';
                document.getElementById('file-btn-text').textContent = 'Choose Files';
                document.getElementById('file-btn-text').classList.remove('bg-blue-600', 'text-white');
                document.getElementById('file-btn-text').classList.add('bg-blue-50', 'text-blue-600');
                return;
            }

            document.getElementById('file-btn-text').textContent = file.name;
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
    const fieldsBudget = document.getElementById('fields_budget');
    const fieldsProcurement = document.getElementById('fields_procurement');

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
        } else if (text.includes('budget')) {
            dynamicContainer.appendChild(fieldsBudget);
            fieldsBudget.classList.remove('hidden');
        } else if (text.includes('procurement')) {
            dynamicContainer.appendChild(fieldsProcurement);
            fieldsProcurement.classList.remove('hidden');
        }
    });

    // Budget Linkage Logic
    const budgetsData = <?= json_encode($approvedBudgets) ?>;
    const budgetSelect = document.getElementById('budget_reference_select');
    const budgetSnapshot = document.getElementById('budget-snapshot');
    const snapId = document.getElementById('snap-id');
    const snapYear = document.getElementById('snap-year');
    const snapAmount = document.getElementById('snap-amount');
    const snapDept = document.getElementById('snap-dept');
    const snapItem1 = document.getElementById('snap-item-1');
    const snapItem2 = document.getElementById('snap-item-2');
    const snapJustification = document.getElementById('snap-justification');

    budgetSelect.addEventListener('change', function() {
        const id = this.value;
        if (!id) {
            budgetSnapshot.classList.add('hidden');
            return;
        }

        const budget = budgetsData.find(b => b.request_id == id);
        if (budget) {
            const meta = JSON.parse(budget.metadata);
            snapId.textContent = '#' + budget.request_id;
            snapYear.textContent = meta.budget_fiscal_year || 'N/A';
            snapDept.textContent = budget.department || 'N/A';
            snapItem1.textContent = meta.budget_item_1 || 'N/A';
            snapItem2.textContent = meta.budget_item_2 || 'N/A';
            snapAmount.textContent = 'GHS ' + (parseFloat(meta.budget_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2}));
            snapJustification.textContent = meta.budget_justification || 'No justification provided.';
            budgetSnapshot.classList.remove('hidden');
        }
    });

    // Integrated Submission Guard
    const requestForm = document.querySelector('form');
    requestForm.addEventListener('submit', function(e) {
        const typeSelect = document.getElementById('workflow_type');
        if (!typeSelect.value) {
            e.preventDefault();
            Swal.fire('Error', 'Please select a workflow type.', 'error');
            return;
        }

        const workflowName = typeSelect.options[typeSelect.selectedIndex].text.toLowerCase();

        // 1. Validation based on active fields
        let errors = [];
        if (workflowName.includes('budget')) {
            if (!document.querySelector('input[name="metadata[budget_department]"]').value) errors.push('Department Code');
            if (!document.querySelector('input[name="metadata[budget_fiscal_year]"]').value) errors.push('Fiscal Year');
            if (!document.querySelector('input[name="metadata[budget_amount]"]').value) errors.push('Budget Amount');
            if (!document.querySelector('input[name="metadata[budget_item_1]"]').value) errors.push('Target Item 1');
            if (!document.querySelector('input[name="metadata[budget_item_2]"]').value) errors.push('Target Item 2');
        } else if (workflowName.includes('procurement')) {
            if (!document.getElementById('procurement_items').value) errors.push('Item Descriptions');
            if (!document.getElementById('procurement_cost').value) errors.push('Estimated Cost');
            if (!document.getElementById('budget_reference_select').value) errors.push('Budget Reference');
        } else if (workflowName.includes('waiver')) {
            if (!document.querySelector('input[name="metadata[fee_current_fee]"]').value) errors.push('Current Fee');
            if (!document.querySelector('input[name="metadata[fee_requested_adjustment]"]').value) errors.push('Requested Adjustment');
        }

        if (errors.length > 0) {
            e.preventDefault();
            Swal.fire('Required Fields', 'Please fill in: ' + errors.join(', '), 'warning');
            return;
        }

        // 2. Item Matching logic for Procurement
        if (workflowName.includes('procurement')) {
            const budgetId = budgetSelect.value;
            if (budgetId) {
                const budget = budgetsData.find(b => b.request_id == budgetId);
                const bMeta = JSON.parse(budget.metadata);
                const procItems = document.getElementById('procurement_items').value.toLowerCase();
                
                const item1 = (bMeta.budget_item_1 || "").toLowerCase();
                const item2 = (bMeta.budget_item_2 || "").toLowerCase();

                const hasItem1 = procItems.includes(item1);
                const hasItem2 = procItems.includes(item2);

                if (!hasItem1 || !hasItem2) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Budget Verification Failed',
                        text: "This request cannot be submitted because there's no approved budget for it.",
                        confirmButtonColor: '#3B82F6'
                    });
                }
            }
        }
    });
</script>

