<?php 
    $meta = !empty($request['metadata']) ? json_decode($request['metadata'], true) : []; 
    $status = $request['status'];
?>

<div class="print:hidden mb-8 max-w-4xl mx-auto flex justify-between items-center px-4">
    <div class="flex items-center space-x-3">
        <a href="<?= url('/requests/' . $request['request_id']) ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-200 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Process
        </a>
        <?php if(in_array($request['status'], ['Approved', 'Rejected'])): ?>
        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-black rounded-lg hover:bg-indigo-700 transition-all shadow-md group">
            <svg class="w-4 h-4 mr-2 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path></svg>
            Download Document (PDF)
        </button>
        <?php endif; ?>
    </div>
    <div class="text-[10px] font-black text-gray-300 uppercase tracking-[0.3em]">Official Record Specification</div>
</div>

<!-- Official Document Container -->
<div id="official-document" class="max-w-4xl mx-auto bg-white shadow-2xl border border-gray-100 min-h-[1100px] p-12 md:p-20 relative overflow-hidden print:shadow-none print:border-none print:p-0 print:m-0">
    
    <!-- Professional Letterhead -->
    <div class="flex justify-between items-start border-b-8 border-gray-900 pb-10 mb-12">
        <div class="flex items-center">
            <!-- Simulated Logo Slot -->
            <div class="w-16 h-16 bg-gray-900 rounded-lg flex items-center justify-center text-white mr-6 shadow-xl">
                <span class="text-3xl font-black"><?= strtoupper(substr($request['workflow_name'], 0, 1)) ?></span>
            </div>
            <div>
                <div class="text-3xl font-black text-gray-900 tracking-tighter uppercase leading-none"><?= htmlspecialchars($request['workflow_name']) ?></div>
                <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mt-2">Official Digital Request Specification</div>
                <div class="mt-3 text-xs text-gray-400 font-medium">Ref: WAS/RECORDS/<?= strtoupper(substr($request['workflow_name'], 0, 3)) ?>/<?= date('Y') ?></div>
            </div>
        </div>
        <div class="text-right">
            <div class="inline-block border-2 border-gray-900 p-4 rounded-sm text-center">
                <div class="text-[8px] font-black uppercase tracking-widest text-gray-400 mb-1">Archive Identifer</div>
                <div class="text-xl font-black font-mono text-gray-900 leading-none">REQ-<?= str_pad($request['request_id'], 5, '0', STR_PAD_LEFT) ?></div>
            </div>
            <p class="mt-3 text-[9px] font-black text-gray-400 uppercase tracking-widest italic">Generated: <?= date('d M Y, H:i') ?></p>
        </div>
    </div>

    <!-- Section I: Submission Context -->
    <div class="mb-14">
        <div class="flex items-center mb-6">
            <div class="h-px bg-gray-200 flex-1"></div>
            <h3 class="mx-4 text-[10px] font-black text-gray-900 uppercase tracking-[0.4em]">Section I: Origin Data</h3>
            <div class="h-px bg-gray-200 flex-1"></div>
        </div>
        
        <div class="grid grid-cols-2 gap-px bg-gray-200 border border-gray-200 overflow-hidden">
            <div class="bg-gray-50/80 p-5 flex flex-col justify-center">
                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Full Name of Submitter</span>
                <span class="text-sm font-bold text-gray-800"><?= htmlspecialchars($request['submitter_name']) ?></span>
            </div>
            <div class="bg-white p-5 flex flex-col justify-center">
                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Affiliation / Dept.</span>
                <span class="text-sm font-bold text-gray-800"><?= htmlspecialchars($request['department'] ?? 'Registry Unit') ?></span>
            </div>
            <div class="bg-white p-5 flex flex-col justify-center">
                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Identification Number</span>
                <span class="text-sm font-bold text-gray-800"><?= htmlspecialchars($meta['staff_id'] ?? $meta['student_id'] ?? 'N/A') ?></span>
            </div>
            <div class="bg-gray-50/80 p-5 flex flex-col justify-center">
                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Submission Timestamp</span>
                <span class="text-sm font-bold text-gray-800"><?= date('F d, Y - H:i', strtotime($request['submission_date'])) ?></span>
            </div>
        </div>
    </div>

    <!-- Section II: Formal Specifications (The Refined Grid) -->
    <div class="mb-14">
        <div class="flex items-center mb-6">
            <div class="h-px bg-gray-200 flex-1"></div>
            <h3 class="mx-4 text-[10px] font-black text-gray-900 uppercase tracking-[0.4em]">Section II: Workflow Payload</h3>
            <div class="h-px bg-gray-200 flex-1"></div>
        </div>

        <div class="border border-gray-200 divide-y divide-gray-200">
            <!-- Header Row -->
            <div class="flex bg-gray-900 text-white font-black text-[9px] uppercase tracking-widest">
                <div class="w-1/3 p-3 border-r border-gray-800">Field Specification</div>
                <div class="w-2/3 p-3">Verified Information / Metadata</div>
            </div>
            
            <?php 
                $excluded = ['staff_id', 'student_id', 'workflow_type', 'submitted_by'];
                foreach($meta as $key => $val): 
                    if(!in_array($key, $excluded) && !empty($val)):
            ?>
                <div class="flex group hover:bg-gray-50 transition-colors">
                    <!-- Label Column (Spine) -->
                    <div class="w-1/3 p-5 bg-gray-50/50 border-r border-gray-200 flex items-center">
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest"><?= str_replace('_', ' ', $key) ?></span>
                    </div>
                    <!-- Data Column -->
                    <div class="w-2/3 p-5 text-sm text-gray-800 leading-relaxed font-medium">
                        <?php if(is_array($val)): ?>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach($val as $item): ?>
                                    <span class="px-2 py-1 bg-gray-100 rounded text-[11px] font-bold"><?= htmlspecialchars($item) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <?= nl2br(htmlspecialchars($val)) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; endforeach; ?>
        </div>
    </div>

    <!-- Official Validation Status -->
    <div class="mb-14 break-inside-avoid">
        <div class="flex bg-gray-50 border-2 border-gray-900 p-8 rounded-lg items-center">
            <div class="mr-8">
                <?php if($status === 'Approved'): ?>
                    <div class="w-20 h-20 bg-emerald-600 rounded-full flex items-center justify-center text-white shadow-lg">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                <?php elseif($status === 'Rejected'): ?>
                    <div class="w-20 h-20 bg-rose-600 rounded-full flex items-center justify-center text-white shadow-lg">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                <?php else: ?>
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg">
                        <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Final Clearance Condition</div>
                <div class="text-2xl font-black text-gray-900 uppercase tracking-tight"><?= $status ?> STATUS VERIFIED</div>
                <p class="mt-1 text-xs text-gray-500 font-medium italic">This document is a true reflection of the digital workflow state as of <?= date('H:i') ?>.</p>
            </div>
        </div>
    </div>

    <!-- Signatory Section & Appendix -->
    <div class="mt-auto pt-20 flex justify-between items-end border-t border-gray-100">
        <div class="w-1/3 text-center">
            <div class="text-xs font-serif italic text-gray-300 mb-2">Electronically Recorded</div>
            <div class="border-b-2 border-gray-900 mb-3 px-4 py-2">
                <span class="text-sm font-bold font-mono text-gray-800 opacity-20"><?= strtoupper(substr($request['submitter_name'], 0, 15)) ?></span>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Digital Submitter Proof</p>
        </div>
        <div class="px-8 pb-4 opacity-10">
            <!-- Simulated System Stamp -->
            <div class="w-24 h-24 border-[6px] border-gray-900 rounded-full flex items-center justify-center rotate-12">
                <span class="text-[10px] font-black text-gray-900 text-center leading-none uppercase">Workflow<br>Auth<br>SYS</span>
            </div>
        </div>
        <div class="w-1/3 text-center">
            <div class="h-8 mb-2"></div>
            <div class="border-b-2 border-gray-900 mb-3"></div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Office of Final Approval</p>
        </div>
    </div>

    <!-- Page Shadow ID for Background -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[200px] font-black text-gray-50/50 pointer-events-none -rotate-12 z-0">
        WAS
    </div>
</div>

<style>
/* Pure Document Export Styles */
@media print {
    @page {
        size: A4;
        margin: 1.5cm;
    }
    body {
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: visible !important;
    }
    .print\:hidden {
        display: none !important;
    }
    #official-document {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
        margin: 0 !important;
        min-height: auto !important;
    }
    /* Ensure background-colors print correctly */
    .bg-gray-50 { background-color: #f9fafb !important; -webkit-print-color-adjust: exact; }
    .bg-gray-200 { background-color: #e5e7eb !important; -webkit-print-color-adjust: exact; }
    .bg-gray-900 { background-color: #111827 !important; -webkit-print-color-adjust: exact; }
    .bg-indigo-600 { background-color: #4f46e5 !important; -webkit-print-color-adjust: exact; }
    .bg-emerald-600 { background-color: #059669 !important; -webkit-print-color-adjust: exact; }
    .bg-rose-600 { background-color: #e11d48 !important; -webkit-print-color-adjust: exact; }
    .bg-blue-600 { background-color: #2563eb !important; -webkit-print-color-adjust: exact; }
    .text-white { color: white !important; -webkit-print-color-adjust: exact; }
}
</style>
