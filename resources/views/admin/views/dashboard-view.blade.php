{{-- ══ VIEW: DASHBOARD ══ --}}
<div id="view-dashboard" class="flex-1 overflow-y-auto p-4 lg:p-6 flex-col gap-4 bg-slate-50/50" style="display: flex;">

    {{-- Welcome Banner --}}
    <div class="shrink-0 relative overflow-hidden bg-slate-900 rounded-2xl p-5 lg:p-6 shadow-md flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 border border-slate-800">
        <div class="absolute top-0 right-0 bottom-0 w-1/2 bg-gradient-to-l from-sky-500/20 to-transparent pointer-events-none"></div>
        <div class="absolute -right-4 -top-4 opacity-10 pointer-events-none transform rotate-12">
            <i data-lucide="plane-takeoff" class="w-48 h-48 text-white"></i>
        </div>
        
        <div class="relative z-10 text-white flex-1 w-full">
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded border border-white/10 bg-white/10 text-sky-300 text-[9px] font-bold uppercase tracking-widest mb-2 backdrop-blur-md">
                <span class="w-1 h-1 rounded-full bg-emerald-400 animate-pulse"></span> HRD System
            </div>
            <h1 class="text-xl md:text-2xl font-black mb-1.5 tracking-tight">Selamat Datang, Admin! 👋</h1>
            <p class="text-xs text-slate-300 max-w-lg leading-relaxed">
                Pantau statistik pelamar, kelola penempatan divisi, dan verifikasi dokumen peserta magang InJourney Airports.
            </p>
        </div>
        
        <button onclick="switchView('candidates')" class="relative z-10 w-full lg:w-auto bg-white hover:bg-sky-50 text-slate-900 font-bold text-xs px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm shrink-0 active:scale-95 group">
            <i data-lucide="layout-list" class="w-4 h-4 text-sky-600"></i> Kelola Kandidat
        </button>
    </div>

    {{-- Stat Cards --}}
    <div class="shrink-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mt-2">
        <!-- Card 1 -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow hover:border-sky-200 transition-all group relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100 group-hover:scale-110 transition-transform shrink-0">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
                <span class="bg-slate-100 text-slate-500 text-[8px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wider">Total</span>
            </div>
            <div class="font-mono text-2xl font-black text-slate-800 mb-0.5" id="stat-total">0</div>
            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pelamar Masuk</div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-sky-400 to-sky-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow hover:border-amber-200 transition-all group relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center border border-amber-100 group-hover:scale-110 transition-transform shrink-0">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <span class="bg-amber-50 text-amber-600 text-[8px] font-bold px-1.5 py-0.5 rounded border border-amber-100 uppercase tracking-wider">Pending</span>
            </div>
            <div class="font-mono text-2xl font-black text-slate-800 mb-0.5" id="stat-pending">0</div>
            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Menunggu Review</div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 to-amber-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow hover:border-emerald-200 transition-all group relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 group-hover:scale-110 transition-transform shrink-0">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <span class="bg-emerald-50 text-emerald-600 text-[8px] font-bold px-1.5 py-0.5 rounded border border-emerald-100 uppercase tracking-wider">Lulus</span>
            </div>
            <div class="font-mono text-2xl font-black text-slate-800 mb-0.5" id="stat-kantor">0</div>
            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Head Office</div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow hover:border-teal-200 transition-all group relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100 group-hover:scale-110 transition-transform shrink-0">
                    <i data-lucide="plane" class="w-4 h-4"></i>
                </div>
                <span class="bg-teal-50 text-teal-600 text-[8px] font-bold px-1.5 py-0.5 rounded border border-teal-100 uppercase tracking-wider">Lulus</span>
            </div>
            <div class="font-mono text-2xl font-black text-slate-800 mb-0.5" id="stat-terminal">0</div>
            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Terminal Ops</div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-teal-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
    </div>

    {{-- VIEW: KANDIDAT (Digabung di sini agar transisi mulus) --}}
    <div class="shrink-0 mt-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-black text-slate-800 tracking-tight">Data Kandidat</h2>
            <p class="text-[11px] font-medium text-slate-500 mt-0.5">Kelola data pelamar dan tentukan penempatan divisi.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openImportModal()" class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-emerald-600"></i> Import
            </button>
            <button onclick="openRegModal()" class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-white bg-slate-800 hover:bg-slate-900 border border-slate-800 rounded-lg transition-all shadow-sm">
                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Tambah Pelamar
            </button>
        </div>
    </div>

    {{-- Toolbar Table --}}
    <div class="shrink-0 bg-white border border-slate-200 rounded-xl p-2.5 flex flex-wrap items-center gap-2 shadow-sm">
        <div class="relative flex-1 min-w-[180px]">
            <i data-lucide="search" class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
            <input type="text" id="searchInput" placeholder="Cari nama atau NIM..." onkeyup="renderTable()"
                class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-xs font-medium focus:bg-white focus:border-sky-400 focus:ring-2 focus:ring-sky-100 transition-all">
        </div>
        
        <select id="filterStatus" onchange="renderTable()" class="pl-3 pr-8 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-xs font-bold text-slate-600 focus:bg-white focus:border-sky-400 transition-all cursor-pointer">
            <option value="all">Semua Status</option>
            <option value="pending">Menunggu Review</option>
            <option value="accepted">Diterima</option>
            <option value="rejected">Ditolak</option>
        </select>

        <div class="h-5 w-px bg-slate-200 mx-1 hidden sm:block"></div>

        <button id="btn-bulk-delete" onclick="bulkDeleteApps()" class="hidden items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-bold text-rose-600 bg-rose-50 border border-rose-200 rounded-md hover:bg-rose-100">
            <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus (<span id="bulk-count">0</span>)
        </button>
    </div>

    {{-- Tabel Panel --}}
    <div class="shrink-0 bg-white border border-slate-200 rounded-xl flex-1 flex flex-col min-h-[300px] overflow-hidden shadow-sm">
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left whitespace-nowrap border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="pl-4 pr-2 py-2.5 w-10 text-center"><input type="checkbox" id="check-all" onchange="toggleCheckAll(this)" class="w-3.5 h-3.5 rounded border-slate-300 text-sky-600 cursor-pointer"></th>
                        <th class="px-3 py-2.5 text-[9px] font-bold uppercase tracking-widest text-slate-500">Kandidat</th>
                        <th class="px-3 py-2.5 text-[9px] font-bold uppercase tracking-widest text-slate-500">Kode</th>
                        <th class="px-3 py-2.5 text-[9px] font-bold uppercase tracking-widest text-slate-500">Pendidikan</th>
                        <th class="px-3 py-2.5 text-[9px] font-bold uppercase tracking-widest text-slate-500">Status</th>
                        <th class="px-3 py-2.5 text-[9px] font-bold uppercase tracking-widest text-slate-500">Penempatan</th>
                        <th class="px-4 py-2.5 text-[9px] font-bold uppercase tracking-widest text-slate-500 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
        <div id="empty-state" class="hidden flex-col items-center justify-center p-10 text-center">
            <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center mb-2 border border-slate-100"><i data-lucide="search-x" class="w-5 h-5 text-slate-400"></i></div>
            <div class="text-xs font-bold text-slate-700">Data Tidak Ditemukan</div>
        </div>
    </div>
</div>