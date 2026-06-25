{{-- ══ VIEW: DOKUMEN ══ --}}
<div id="view-documents" class="flex-1 overflow-y-auto p-5 lg:p-8 flex-col gap-6 bg-slate-50/50" style="display:none;">

    {{-- Header --}}
    <div class="shrink-0">
        <h2 class="text-xl font-black text-slate-800 tracking-tight">Dokumen Penempatan</h2>
        <p class="text-xs font-medium text-slate-500 mt-0.5">Kelola berkas panduan dan tata tertib magang berdasarkan divisi penempatan peserta.</p>
    </div>

    {{-- Grid 2 Kolom --}}
    <div class="shrink-0 grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        {{-- Card: Head Office --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col h-[400px]">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 shrink-0 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 text-sm">Head Office</h3>
                        <p class="text-[10px] text-slate-500 font-medium">Kantor Pusat · <span id="kantor-count" class="font-bold">0</span> dokumen</p>
                    </div>
                </div>
                <button onclick="openUploadModal('kantor')" class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-lg transition-colors active:scale-95 shadow-sm">
                    <i data-lucide="upload-cloud" class="w-3.5 h-3.5"></i> Upload
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-2 relative">
                <div id="doc-list-kantor" class="flex flex-col gap-2 z-10 relative"></div>
                
                <div id="doc-empty-kantor" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-slate-50/30 z-0">
                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mb-3">
                        <i data-lucide="folder-open" class="w-6 h-6 text-slate-300"></i>
                    </div>
                    <div class="text-xs font-bold text-slate-600 mb-1">Folder Kosong</div>
                    <div class="text-[10px] text-slate-400 font-medium max-w-[200px]">Belum ada dokumen yang diunggah untuk divisi ini.</div>
                </div>
            </div>
        </div>

        {{-- Card: Terminal Ops --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col h-[400px]">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 shrink-0 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100 shrink-0">
                        <i data-lucide="plane" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 text-sm">Terminal Ops</h3>
                        <p class="text-[10px] text-slate-500 font-medium">Operasional Bandara · <span id="terminal-count" class="font-bold">0</span> dokumen</p>
                    </div>
                </div>
                <button onclick="openUploadModal('terminal')" class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-lg transition-colors active:scale-95 shadow-sm">
                    <i data-lucide="upload-cloud" class="w-3.5 h-3.5"></i> Upload
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-2 relative">
                <div id="doc-list-terminal" class="flex flex-col gap-2 z-10 relative"></div>
                
                <div id="doc-empty-terminal" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-slate-50/30 z-0">
                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mb-3">
                        <i data-lucide="folder-open" class="w-6 h-6 text-slate-300"></i>
                    </div>
                    <div class="text-xs font-bold text-slate-600 mb-1">Folder Kosong</div>
                    <div class="text-[10px] text-slate-400 font-medium max-w-[200px]">Belum ada dokumen yang diunggah untuk divisi ini.</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Info Box Bawah --}}
    <div class="shrink-0 bg-sky-50 border border-sky-100 rounded-xl p-4 flex items-start sm:items-center gap-3">
        <i data-lucide="info" class="w-4 h-4 text-sky-500 shrink-0 mt-0.5 sm:mt-0"></i>
        <p class="text-[11px] text-slate-600 font-medium leading-relaxed">
            Dokumen yang diunggah ke dalam folder di atas akan <strong class="text-slate-800 font-bold">otomatis tersedia dan dapat diunduh</strong> 
            oleh peserta melalui portal mereka masing-masing sesuai dengan lokasi penempatan yang telah ditetapkan.
        </p>
    </div>

</div>