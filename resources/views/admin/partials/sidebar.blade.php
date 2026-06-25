<aside class="w-64 bg-white border-r border-slate-200 flex flex-col pt-6 pb-6 shadow-[4px_0_24px_-10px_rgba(0,0,0,0.02)] z-20 shrink-0">

    {{-- Logo --}}
    <div class="px-6 mb-8 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">
            <img src="{{ asset('img/logo-injourney.png') }}"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
                 alt="IJ" class="h-4 object-contain">
            <i data-lucide="plane-takeoff" style="display:none;" class="w-5 h-5"></i>
        </div>
        <div>
            <div class="text-[15px] font-black text-slate-800 tracking-tight leading-none">
                HRD<span class="text-sky-600">PRO</span>
            </div>
            <div class="text-[9px] text-slate-400 font-bold tracking-[0.15em] uppercase mt-1">Recruitment</div>
        </div>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-y-auto px-4 flex flex-col gap-1.5" style="-ms-overflow-style:none;scrollbar-width:none;">
        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-2 mb-2 mt-2">Menu Utama</div>

        <a href="#" onclick="switchView('dashboard');return false" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-sky-700 bg-sky-50 transition-all" id="nav-dashboard">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
        </a>
        <a href="#" onclick="switchView('candidates');return false" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-all" id="nav-candidates">
            <i data-lucide="users" class="w-4 h-4"></i> Kandidat
        </a>
        <a href="#" onclick="switchView('documents');return false" class="nav-item flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-all" id="nav-documents">
            <div class="flex items-center gap-3"><i data-lucide="folder" class="w-4 h-4"></i> Dokumen</div>
            <span class="bg-rose-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-md" id="doc-badge" style="display:none">0</span>
        </a>
        <a href="#" onclick="switchView('report');return false" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-all" id="nav-report">
            <i data-lucide="bar-chart-2" class="w-4 h-4"></i> Laporan
        </a>

        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-2 mb-2 mt-6">Sistem</div>
        <a href="#" onclick="showToast('Dibatasi','Fitur ini hanya untuk Super Admin.','warning');return false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-all">
            <i data-lucide="database" class="w-4 h-4"></i> Reset Data
        </a>
    </nav>

    {{-- User Profile --}}
    <div class="px-4 mt-auto pt-4 border-t border-slate-100">
        <button class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors text-left" onclick="openLogoutModal()">
            <div class="w-10 h-10 rounded-xl bg-slate-800 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-inner">AD</div>
            <div class="flex-1 min-w-0">
                <div class="text-xs font-bold text-slate-800 truncate">Admin HRD</div>
                <div class="text-[10px] text-slate-400 font-medium mt-0.5">Klik untuk logout</div>
            </div>
            <i data-lucide="log-out" class="w-4 h-4 text-slate-400 shrink-0"></i>
        </button>
    </div>

</aside>