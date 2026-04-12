<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Submitted Document #<?= $request['request_id'] ?></h1>
            <p class="text-sm text-gray-500 mt-1">Full form details provided during submission</p>
        </div>
        <a href="<?= url('/requests/' . $request['request_id']) ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow transition font-medium whitespace-nowrap">
            &larr; Back to Process View
        </a>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-lg border border-gray-100">
        <!-- Render Actual Form Data -->
        <?php if(!empty($request['metadata'])): 
            $meta = json_decode($request['metadata'], true); 
            if(is_array($meta)): ?>
            <div id="form-details" class="">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center border-b pb-3">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Form Payload Data
                </h3>
                
                <div class="space-y-6">
                    <?php foreach($meta as $key => $val): ?>
                        <?php if(!empty($val) && $key !== 'workflow_type'): ?>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <span class="block text-sm font-bold text-blue-600 uppercase tracking-wide mb-1"><?= htmlspecialchars(str_replace('_', ' ', $key)) ?></span>
                            <span class="block text-gray-900 font-medium whitespace-pre-wrap leading-relaxed"><?= is_array($val) ? implode(', ', array_map('htmlspecialchars', $val)) : htmlspecialchars($val) ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-500 font-medium">Invalid or corrupted payload structure detected.</p>
            </div>
        <?php endif; else: ?>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-gray-500 font-medium text-lg">No form details available.</p>
                <p class="text-sm text-gray-400 mt-1">This request does not contain any submittable JSON payload.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
