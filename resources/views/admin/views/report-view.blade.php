{{-- ══ VIEW: LAPORAN & ANALITIK ══ --}}
<div id="view-report" class="flex-1 overflow-y-auto p-5 lg:p-8 flex-col gap-6 bg-slate-50/50" style="display:none;">

    {{-- Header & Toolbar --}}
    <div class="shrink-0 bg-white border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Laporan Analitik</h2>
            <p class="text-xs font-medium text-slate-500 mt-0.5">Rekapitulasi statistik peserta magang Batch 2025.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2 lg:gap-3">
            <select id="export-filter-status" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 focus:ring-2 focus:ring-sky-100 outline-none cursor-pointer">
                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="accepted">Diterima</option>
                <option value="rejected">Ditolak</option>
            </select>

            <select id="export-filter-loc" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 focus:ring-2 focus:ring-sky-100 outline-none cursor-pointer">
                <option value="all">Semua Lokasi</option>
                <option value="kantor">Head Office</option>
                <option value="terminal">Terminal Ops</option>
            </select>

            <div class="hidden sm:block w-px h-8 bg-slate-200 mx-1"></div>

            <div class="relative group">
                <button class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-lg transition-all shadow-sm">
                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Export <i data-lucide="chevron-down" class="w-3 h-3 opacity-50"></i>
                </button>
                
                <div class="absolute right-0 top-full mt-1 w-52 bg-white border border-slate-200 rounded-xl shadow-[0_10px_25px_rgba(0,0,0,0.1)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 flex flex-col overflow-hidden">
                    <button onclick="exportExcel()" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-left border-b border-slate-100 w-full transition-colors">
                        <i data-lucide="table-2" class="w-4 h-4 text-emerald-600"></i>
                        <div><div class="text-xs font-bold text-slate-800">Excel</div><div class="text-[9px] text-slate-400">Format .xlsx</div></div>
                    </button>
                    <button onclick="exportPdf()" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-left border-b border-slate-100 w-full transition-colors">
                        <i data-lucide="file-text" class="w-4 h-4 text-rose-600"></i>
                        <div><div class="text-xs font-bold text-slate-800">PDF</div><div class="text-[9px] text-slate-400">Dokumen Cetak</div></div>
                    </button>
                    <button onclick="exportCsv()" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-left w-full transition-colors">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4 text-sky-600"></i>
                        <div><div class="text-xs font-bold text-slate-800">CSV</div><div class="text-[9px] text-slate-400">Data Mentah</div></div>
                    </button>
                </div>
            </div>

            <button onclick="loadReport()" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:bg-sky-50 hover:text-sky-600 transition-colors shadow-sm" title="Refresh Data">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    {{-- 6 Stat Cards Kecil (Tinggi Disamakan) --}}
    <div class="shrink-0 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm flex flex-col justify-center h-24 hover:border-slate-300 transition-colors">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Total</div>
            <div class="font-mono text-3xl font-black text-slate-800" id="rep-total">0</div>
        </div>
        <div class="bg-white border border-amber-100 rounded-xl p-4 shadow-sm flex flex-col justify-center h-24 hover:border-amber-200 transition-colors">
            <div class="text-[10px] text-amber-500 font-bold uppercase tracking-widest mb-1">Pending</div>
            <div class="font-mono text-3xl font-black text-amber-500" id="rep-pending">0</div>
        </div>
        <div class="bg-white border border-emerald-100 rounded-xl p-4 shadow-sm flex flex-col justify-center h-24 hover:border-emerald-200 transition-colors">
            <div class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest mb-1">Diterima</div>
            <div class="font-mono text-3xl font-black text-emerald-500" id="rep-accepted">0</div>
        </div>
        <div class="bg-white border border-rose-100 rounded-xl p-4 shadow-sm flex flex-col justify-center h-24 hover:border-rose-200 transition-colors">
            <div class="text-[10px] text-rose-500 font-bold uppercase tracking-widest mb-1">Ditolak</div>
            <div class="font-mono text-3xl font-black text-rose-500" id="rep-rejected">0</div>
        </div>
        <div class="bg-white border border-indigo-100 rounded-xl p-4 shadow-sm flex flex-col justify-center h-24 hover:border-indigo-200 transition-colors">
            <div class="text-[10px] text-indigo-500 font-bold uppercase tracking-widest mb-1">Head Office</div>
            <div class="font-mono text-3xl font-black text-indigo-500" id="rep-kantor">0</div>
        </div>
        <div class="bg-white border border-teal-100 rounded-xl p-4 shadow-sm flex flex-col justify-center h-24 hover:border-teal-200 transition-colors">
            <div class="text-[10px] text-teal-500 font-bold uppercase tracking-widest mb-1">Terminal</div>
            <div class="font-mono text-3xl font-black text-teal-500" id="rep-terminal">0</div>
        </div>
    </div>

    {{-- Charts Row (Tinggi Dikunci 320px) --}}
    <div class="shrink-0 grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col items-center justify-between h-[320px]">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider w-full text-left">Distribusi Status</h3>
            <div class="relative w-32 h-32 flex items-center justify-center my-auto">
                <svg viewBox="0 0 140 140" class="w-full h-full transform -rotate-90">
                    <circle cx="70" cy="70" r="52" fill="none" stroke="#f1f5f9" stroke-width="20"/>
                    <circle id="donut-accepted" cx="70" cy="70" r="52" fill="none" stroke="#10b981" stroke-width="20" stroke-dasharray="0 327" stroke-dashoffset="0" class="transition-all duration-1000 ease-out"/>
                    <circle id="donut-rejected" cx="70" cy="70" r="52" fill="none" stroke="#f43f5e" stroke-width="20" stroke-dasharray="0 327" stroke-dashoffset="0" class="transition-all duration-1000 ease-out"/>
                    <circle id="donut-pending" cx="70" cy="70" r="52" fill="none" stroke="#f59e0b" stroke-width="20" stroke-dasharray="0 327" stroke-dashoffset="0" class="transition-all duration-1000 ease-out"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <div id="donut-center-num" class="font-mono text-xl font-black text-slate-800">0</div>
                    <div class="text-[8px] font-bold text-slate-400 tracking-widest mt-0.5 uppercase">Total</div>
                </div>
            </div>
            <div class="w-full space-y-2 mt-4">
                <div class="flex items-center justify-between text-xs border-b border-slate-100 pb-1.5"><div class="flex items-center gap-2 text-slate-600"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Diterima</div><div id="leg-accepted" class="font-mono font-bold text-slate-800">0</div></div>
                <div class="flex items-center justify-between text-xs border-b border-slate-100 pb-1.5"><div class="flex items-center gap-2 text-slate-600"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Pending</div><div id="leg-pending" class="font-mono font-bold text-slate-800">0</div></div>
                <div class="flex items-center justify-between text-xs"><div class="flex items-center gap-2 text-slate-600"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Ditolak</div><div id="leg-rejected" class="font-mono font-bold text-slate-800">0</div></div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm lg:col-span-2 flex flex-col h-[320px]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Registrasi per Bulan</h3>
                <div class="flex items-center gap-3 text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-sky-100 border border-sky-200"></span> Total</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-emerald-400"></span> Lulus</span>
                </div>
            </div>
            <div id="monthly-chart" class="flex-1 w-full flex items-end justify-between gap-1 sm:gap-3 px-2 border-b border-slate-100 overflow-hidden relative">
                <div class="text-[10px] text-slate-400 absolute inset-0 flex items-center justify-center font-medium">Memuat data grafik...</div>
            </div>
        </div>

    </div>

    {{-- Tables Row (Tinggi Dikunci 380px dengan Scroll Dalam) --}}
    <div class="shrink-0 grid grid-cols-1 lg:grid-cols-2 gap-5 pb-6">
        
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col h-[380px] overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50 shrink-0">
                <h3 class="text-sm font-black text-slate-800">Statistik Universitas</h3>
                <p class="text-[10px] text-slate-500 font-medium mt-0.5" id="univ-count">Memuat...</p>
            </div>
            <div class="flex-1 overflow-y-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="sticky top-0 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)] z-10">
                        <tr>
                            <th class="px-4 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 w-10">#</th>
                            <th class="px-3 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400">Universitas</th>
                            <th class="px-3 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-center">Total</th>
                            <th class="px-3 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-center text-emerald-600" title="Diterima"><i data-lucide="check" class="w-3 h-3 inline"></i></th>
                            <th class="px-4 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-right">Rasio</th>
                        </tr>
                    </thead>
                    <tbody id="univ-tbody" class="divide-y divide-slate-100 text-xs text-slate-700">
                        <tr><td colspan="5" class="text-center py-10 text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col h-[380px] overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50 shrink-0">
                <h3 class="text-sm font-black text-slate-800">Top 10 Program Studi</h3>
                <p class="text-[10px] text-slate-500 font-medium mt-0.5">Berdasarkan jumlah pelamar terbanyak</p>
            </div>
            <div class="flex-1 overflow-y-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="sticky top-0 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)] z-10">
                        <tr>
                            <th class="px-4 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 w-10">#</th>
                            <th class="px-3 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400">Program Studi</th>
                            <th class="px-3 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-center">Total</th>
                            <th class="px-3 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-center text-emerald-600" title="Diterima"><i data-lucide="check" class="w-3 h-3 inline"></i></th>
                            <th class="px-4 py-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-right">Proporsi</th>
                        </tr>
                    </thead>
                    <tbody id="major-tbody" class="divide-y divide-slate-100 text-xs text-slate-700">
                        <tr><td colspan="5" class="text-center py-10 text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>