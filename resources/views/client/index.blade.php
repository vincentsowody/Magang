<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Magang — InJourney Airports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .animate-fade-in { animation: fadeInUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Boarding pass dashed divider */
        .ticket-divider {
            background-image: linear-gradient(to right, #cbd5e1 50%, rgba(255,255,255,0) 0%);
            background-position: bottom;
            background-size: 12px 2px;
            background-repeat: repeat-x;
        }

        /* Toast */
        .toast { animation: toastIn 0.28s ease forwards; }
        .toast.out { animation: toastOut 0.25s ease forwards; }
        @keyframes toastIn  { from { opacity:0; transform:translateX(16px); } to { opacity:1; transform:translateX(0); } }
        @keyframes toastOut { from { opacity:1; transform:translateX(0); }   to { opacity:0; transform:translateX(16px); } }

        /* Nav tab active state */
        .nav-tab.active {
            background: white;
            color: #0e7490 !important;
            box-shadow: 2px 2px 0 rgba(0,0,0,0.06);
        }

        /* Document pill chips */
        .doc-pill {
            cursor: pointer;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            border: 2px solid #e2e8f0;
            background: white;
            color: #475569;
            transition: all .15s;
            white-space: nowrap;
        }
        .doc-pill.active, .doc-pill:hover {
            border-color: #0891b2;
            background: #ecfeff;
            color: #0e7490;
        }

        /* Upload zone hover */
        #upload-zone:hover { border-color: #06b6d4; background: #f0fdfe; }
        #upload-zone.drag-over { border-color: #06b6d4; background: #ecfeff; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans min-h-screen flex flex-col antialiased relative overflow-x-hidden">

    {{-- Background pattern --}}
    <div class="fixed inset-0 pointer-events-none z-0 opacity-30" style="background-image: radial-gradient(#94a3b8 1px, transparent 1px); background-size: 20px 20px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0 bg-gradient-to-br from-cyan-400/8 via-transparent to-blue-500/8"></div>

    {{-- Header / Navbar --}}
    <header class="fixed top-0 w-full z-50 px-4 sm:px-6 py-3 flex items-center justify-between">

        {{-- Logo / Clock --}}
        <div class="relative z-10 flex items-center gap-3">
            <div class="bg-white/70 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/80 shadow-sm hidden md:flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-md bg-cyan-600 flex items-center justify-center">
                    <i data-lucide="plane" class="w-3.5 h-3.5 text-white"></i>
                </div>
                <span id="clock" class="font-mono text-xs font-bold text-slate-500">--:--:--</span>
            </div>
        </div>

        {{-- Nav tabs + logout --}}
        <div class="flex items-center gap-3 relative z-10">
            <div id="nav-tabs-wrap" style="display:none" class="overflow-x-auto hide-scrollbar">
                <div class="flex gap-1 bg-white/70 backdrop-blur-md p-1 rounded-2xl border border-white/80 shadow-sm w-max">
                    <button class="nav-tab px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-cyan-700 flex items-center gap-1.5 transition-all" id="tab-status" onclick="showTab('status')">
                        <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Status
                    </button>
                    <button class="nav-tab px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-cyan-700 flex items-center gap-1.5 transition-all relative" id="tab-dokumen" onclick="showTab('dokumen')">
                        <i data-lucide="folder-open" class="w-3.5 h-3.5"></i> Dokumen
                        <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-rose-500 border border-white hidden" id="doc-dot"></span>
                    </button>
                    <button class="nav-tab px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-cyan-700 flex items-center gap-1.5 transition-all relative" id="tab-notifikasi" onclick="showTab('notifikasi')">
                        <i data-lucide="bell" class="w-3.5 h-3.5"></i> Notif
                        <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-rose-500 border border-white hidden" id="notif-dot"></span>
                    </button>
                </div>
            </div>
            <button id="logout-btn" style="display:none" onclick="openLogoutModal()"
                class="w-10 h-10 rounded-xl border border-white/80 bg-white/70 backdrop-blur-md text-slate-500 hover:text-rose-600 hover:bg-white flex items-center justify-center transition-all shadow-sm shrink-0" title="Keluar">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </button>
        </div>
    </header>

    {{-- Toast container --}}
    <div id="toast-container" class="fixed top-20 right-4 sm:right-6 z-[9999] flex flex-col gap-2.5 w-[calc(100%-2rem)] sm:w-80 pointer-events-none"></div>

    {{-- Main content --}}
    <main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center pt-20 relative z-10 pb-12">

        {{-- ── LOGIN VIEW ── --}}
        <div id="view-login" class="w-full max-w-sm animate-fade-in flex flex-col items-center">
            {{-- Logo --}}
            <div class="mb-6 text-center">
                <img src="{{ asset('img/logo-injourney.png') }}" alt="InJourney Airports"
                     class="h-16 object-contain mix-blend-multiply mx-auto mb-3"
                     onerror="this.outerHTML='<div class=\'text-3xl font-black text-slate-800 tracking-tighter mb-3\'>HRD<span class=\'text-cyan-600\'>PRO</span></div>\'">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Portal Magang</div>
            </div>

            <div class="w-full bg-white border border-slate-200 rounded-3xl p-7 sm:p-8 shadow-lg shadow-slate-200/60 relative overflow-hidden">
                {{-- Decorative accent --}}
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-t-3xl"></div>

                <div class="text-center mb-7">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-600 shadow-lg shadow-cyan-200 flex items-center justify-center mx-auto mb-4 text-white">
                        <i data-lucide="fingerprint" class="w-7 h-7"></i>
                    </div>
                    <h1 class="text-xl font-black text-slate-800 mb-1 tracking-tight">Verifikasi Identitas</h1>
                    <p class="text-xs text-slate-400 font-semibold">Masukkan kode akses yang diberikan panitia</p>
                </div>

                <div class="mb-5">
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"><i data-lucide="key-round" class="w-4 h-4"></i></span>
                        <input type="text" id="code-input" placeholder="MAG-2025-XXX" autocomplete="off"
                            class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl pl-11 pr-4 py-3.5 text-base font-mono font-black text-slate-700 placeholder-slate-300 focus:outline-none focus:border-cyan-400 focus:bg-white transition-all uppercase tracking-wider"
                            onkeydown="if(event.key==='Enter') doLogin()">
                    </div>
                </div>

                <button onclick="doLogin()" id="login-btn"
                    class="w-full bg-slate-800 hover:bg-slate-900 text-white font-black text-sm py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md active:scale-[.98]">
                    Masuk <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>

                <div id="login-err" class="mt-4 hidden text-center text-xs font-bold text-rose-500 bg-rose-50 border border-rose-200 py-2.5 px-4 rounded-xl"></div>
            </div>
        </div>

        {{-- ── STATUS: DITERIMA ── --}}
        <div id="view-accepted" style="display:none" class="w-full max-w-4xl animate-fade-in mt-4">
            {{-- Boarding pass card --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/80 overflow-hidden border border-slate-200 flex flex-col relative">
                {{-- Notch circles --}}
                <div class="absolute left-[-20px] top-[172px] sm:top-[152px] w-10 h-10 bg-slate-100 rounded-full border border-slate-200 z-20"></div>
                <div class="absolute right-[-20px] top-[172px] sm:top-[152px] w-10 h-10 bg-slate-100 rounded-full border border-slate-200 z-20"></div>

                {{-- Header section --}}
                <div class="bg-gradient-to-br from-cyan-700 to-blue-800 p-7 sm:p-10 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-56 h-56 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute right-6 bottom-6 opacity-5">
                        <i data-lucide="plane" style="width:80px;height:80px"></i>
                    </div>
                    <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start gap-5 sm:gap-0 mb-7">
                        <div class="inline-flex items-center gap-2 bg-white/15 border border-white/25 backdrop-blur-sm px-3.5 py-1.5 rounded-lg text-xs font-black tracking-widest uppercase">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span> TERKONFIRMASI
                        </div>
                        <div class="bg-black/20 border border-white/15 backdrop-blur-sm p-3 rounded-xl">
                            <div class="text-[9px] font-bold text-cyan-200 uppercase tracking-widest mb-1">Kode Boarding</div>
                            <div class="font-mono text-xl font-black tracking-widest" id="bp-code">—</div>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <div class="text-[10px] font-bold text-cyan-200 uppercase tracking-widest mb-1.5">Nama Peserta</div>
                        <div class="text-3xl sm:text-4xl font-black mb-3 tracking-tight drop-shadow" id="bp-name">—</div>
                        <div class="text-xs font-bold text-cyan-100 flex items-center gap-2 bg-black/10 inline-flex px-3 py-1.5 rounded-lg border border-white/10">
                            <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> <span id="bp-univ">—</span>
                        </div>
                    </div>
                </div>

                {{-- Dashed divider --}}
                <div class="relative h-px ticket-divider mx-8 z-10 mt-5"></div>

                {{-- Body --}}
                <div class="p-7 sm:p-10 pt-8 grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white relative z-10">
                    <div class="lg:col-span-7 space-y-6">
                        <div class="grid grid-cols-2 gap-x-5 gap-y-5 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                            <div>
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">NIM / ID</div>
                                <div class="font-bold text-sm text-slate-800" id="bp-nim">—</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Program Studi</div>
                                <div class="font-bold text-sm text-slate-800" id="bp-major">—</div>
                            </div>
                            <div class="col-span-2 pt-4 border-t border-slate-100">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i> Penempatan (Divisi)
                                </div>
                                <div class="font-black text-slate-800 text-xl sm:text-2xl" id="bp-loc">—</div>
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-black tracking-widest uppercase text-slate-400 mb-3 flex items-center gap-2">
                                <i data-lucide="info" class="w-3.5 h-3.5"></i> Instruksi Penting
                            </div>
                            <div class="space-y-3 text-xs font-semibold text-slate-600 bg-amber-50 p-5 rounded-2xl border border-amber-100" id="bp-notices">
                                <div class="flex gap-2.5 items-start"><i data-lucide="check-circle-2" class="w-4 h-4 text-amber-500 shrink-0 mt-0.5"></i> Hadir tepat waktu pada hari pertama di lokasi penempatan.</div>
                                <div class="flex gap-2.5 items-start"><i data-lucide="check-circle-2" class="w-4 h-4 text-amber-500 shrink-0 mt-0.5"></i> Unduh dan cetak dokumen resmi sebagai bukti lapor diri.</div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5 flex flex-col justify-between gap-6">
                        <div>
                            <div class="text-[10px] font-black tracking-widest uppercase text-slate-400 mb-3 flex items-center gap-2">
                                <i data-lucide="file-badge" class="w-3.5 h-3.5"></i> Dokumen Resmi
                            </div>
                            <div id="admin-doc-list" class="space-y-2.5"></div>
                        </div>
                        <div class="pt-6 border-t border-slate-100 text-center opacity-30">
                            <div style="font-family:monospace;font-size:40px;line-height:1;color:#0f172a;letter-spacing:.1em">INJOURNEY</div>
                            <div class="text-[8px] font-mono font-black tracking-[0.4em] mt-2">SISTEM TERVALIDASI</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── STATUS: PENDING ── --}}
        <div id="view-pending" style="display:none" class="w-full max-w-sm animate-fade-in mt-4">
            <div class="bg-white border border-slate-200 rounded-3xl p-7 sm:p-9 text-center shadow-lg shadow-slate-200/60">
                <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-5 border-2 border-amber-100">
                    <i data-lucide="clock" class="w-8 h-8"></i>
                </div>
                <h2 class="text-xl font-black text-slate-800 mb-2">Sedang Direview</h2>
                <p class="text-sm text-slate-500 font-semibold leading-relaxed mb-6">
                    Berkas <strong id="pend-name" class="text-slate-800">Anda</strong> sedang dalam antrean verifikasi HRD.
                </p>
                <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-2.5 font-mono text-xs font-bold text-slate-400 mb-6 inline-block">
                    Status: <span class="text-amber-500 ml-1">PENDING_REVIEW</span>
                </div>
                <button onclick="showTab('dokumen')"
                    class="w-full bg-slate-800 hover:bg-slate-900 text-white font-black text-sm py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md active:scale-[.98]">
                    <i data-lucide="folder-open" class="w-4 h-4"></i> Cek Dokumen
                </button>
            </div>
        </div>

        {{-- ── STATUS: DITOLAK ── --}}
        <div id="view-rejected" style="display:none" class="w-full max-w-sm animate-fade-in mt-4">
            <div class="bg-white border border-slate-200 rounded-3xl p-7 sm:p-9 text-center shadow-lg shadow-slate-200/60">
                <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-5 border-2 border-rose-100">
                    <i data-lucide="x-circle" class="w-8 h-8"></i>
                </div>
                <h2 class="text-xl font-black text-slate-800 mb-2">Belum Berhasil</h2>
                <p class="text-sm text-slate-500 font-semibold leading-relaxed mb-6">
                    Mohon maaf <strong id="rej-name" class="text-slate-800">Anda</strong>, lamaran magang belum dapat kami terima untuk batch ini.
                </p>
                <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-2.5 font-mono text-xs font-bold text-slate-400 inline-block">
                    Status: <span class="text-rose-500 ml-1">REJECTED</span>
                </div>
            </div>
        </div>

        {{-- ── DOKUMEN VIEW ── --}}
        <div id="view-dokumen" style="display:none" class="w-full grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 animate-fade-in mt-4">

            {{-- Upload panel --}}
            <div class="lg:col-span-5 xl:col-span-4 bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 shadow-lg shadow-slate-200/60 h-fit">
                <div class="flex items-center gap-3 mb-5 pb-5 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-cyan-50 border-2 border-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i data-lucide="cloud-upload" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 text-base">Unggah Berkas</h3>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">PDF / JPG / PNG · Maks 5 MB</p>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Pilih Kategori</div>
                    <div id="doc-type-pills" class="flex flex-wrap gap-1.5 mb-2">
                        <div class="text-xs font-semibold text-slate-400 italic">Memuat opsi...</div>
                    </div>
                    <div id="doc-name-wrap" style="display:none" class="mt-3">
                        <input type="text" id="doc-name" placeholder="Nama dokumen spesifik..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold focus:outline-none focus:border-cyan-400 transition-all placeholder-slate-300">
                    </div>
                    <input type="hidden" id="doc-type" value="">
                    <input type="hidden" id="doc-name-from-admin" value="">
                </div>

                <div id="upload-zone" onclick="document.getElementById('file-input').click()"
                    ondragover="event.preventDefault();this.classList.add('drag-over')"
                    ondragleave="this.classList.remove('drag-over')"
                    class="border-2 border-dashed border-slate-200 rounded-2xl p-7 text-center cursor-pointer transition-all group mb-4">
                    <input type="file" id="file-input" style="display:none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="handleFileSelect(event)">
                    <div id="upload-zone-inner">
                        <div class="w-12 h-12 rounded-full bg-slate-50 border-2 border-slate-200 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 group-hover:bg-cyan-50 group-hover:border-cyan-200 transition-all">
                            <i data-lucide="plus" class="w-5 h-5 text-slate-400 group-hover:text-cyan-600"></i>
                        </div>
                        <div class="text-sm font-black text-slate-600">Pilih atau Seret Dokumen</div>
                        <div class="text-[10px] font-semibold text-slate-400 mt-1">Klik di sini untuk membuka file manager</div>
                    </div>
                </div>

                <div id="file-preview" style="display:none" class="mb-4 p-3.5 bg-cyan-50 border border-cyan-200 rounded-xl flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-lg bg-white border border-cyan-200 flex items-center justify-center shrink-0">
                            <i data-lucide="file-text" class="w-4 h-4 text-cyan-600"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div id="file-name-preview" class="text-xs font-black text-slate-800 truncate">—</div>
                            <div id="file-size-preview" class="text-[10px] font-semibold text-slate-400 mt-0.5">—</div>
                        </div>
                    </div>
                    <button onclick="clearFile()" class="w-7 h-7 rounded-lg text-slate-400 hover:bg-rose-100 hover:text-rose-500 flex items-center justify-center transition-colors shrink-0">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>

                <button id="upload-btn" onclick="uploadDoc()" disabled
                    class="w-full bg-slate-800 hover:bg-slate-900 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white font-black text-sm py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md disabled:shadow-none active:scale-[.98]">
                    <i data-lucide="send" class="w-4 h-4"></i> Kirim Berkas
                </button>
            </div>

            {{-- Document history --}}
            <div class="lg:col-span-7 xl:col-span-8 bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 shadow-lg shadow-slate-200/60 flex flex-col">
                <div class="flex justify-between items-center mb-5 pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="font-black text-slate-800 text-base">Riwayat Berkas</h3>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Status dokumen yang telah dikirim</p>
                    </div>
                    <button onclick="loadDocs()"
                        class="text-xs font-bold text-cyan-700 bg-cyan-50 border border-cyan-200 hover:bg-cyan-100 px-3.5 py-2 rounded-xl transition-colors flex items-center gap-1.5">
                        <i data-lucide="refresh-cw" class="w-3 h-3"></i> <span class="hidden sm:inline">Refresh</span>
                    </button>
                </div>
                <div id="my-doc-list" class="space-y-3 flex-1">
                    <div class="text-center py-16">
                        <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="folder-search" class="w-5 h-5 text-slate-300"></i>
                        </div>
                        <div class="text-sm font-semibold text-slate-400">Memuat data...</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── NOTIFIKASI VIEW ── --}}
        <div id="view-notifikasi" style="display:none" class="w-full max-w-2xl animate-fade-in mt-4">
            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-lg shadow-slate-200/60">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 p-6 sm:p-7 border-b border-slate-100">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                            <i data-lucide="inbox" class="w-5 h-5 text-slate-600"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 text-base">Kotak Masuk</h3>
                            <p class="text-[10px] font-black text-cyan-600 uppercase tracking-widest mt-0.5" id="unread-label">—</p>
                        </div>
                    </div>
                    <button onclick="readAllNotifs()"
                        class="text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2.5 rounded-xl flex items-center gap-2 transition-all w-full sm:w-auto justify-center">
                        <i data-lucide="check-check" class="w-3.5 h-3.5"></i> Tandai Semua Dibaca
                    </button>
                </div>
                <div id="notif-list" class="divide-y divide-slate-100">
                    <div class="p-16 text-center text-sm font-semibold text-slate-400">Memuat notifikasi...</div>
                </div>
            </div>
        </div>

    </main>

    {{-- LOGOUT MODAL --}}
    <div id="logout-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-7 max-w-xs w-full shadow-2xl text-center border border-slate-200">
            <div class="w-14 h-14 bg-rose-50 text-rose-500 border-2 border-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="log-out" class="w-6 h-6"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Akhiri Sesi?</h3>
            <p class="text-xs text-slate-500 font-semibold mb-6">Anda memerlukan kode akses untuk masuk kembali.</p>
            <div class="flex gap-3">
                <button onclick="closeLogoutModal()" class="flex-1 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition-all">Batal</button>
                <button onclick="doLogout()" class="flex-1 py-3 text-sm font-black text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-all shadow-md">Keluar</button>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div id="delete-modal" style="display:none" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-7 max-w-xs w-full shadow-2xl text-center border border-slate-200">
            <div class="w-14 h-14 bg-rose-50 text-rose-500 border-2 border-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="trash-2" class="w-6 h-6"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Hapus Berkas?</h3>
            <p class="text-xs text-slate-500 font-semibold mb-6">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition-all">Batal</button>
                <button onclick="confirmDelete()" class="flex-1 py-3 text-sm font-black text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-all shadow-md">Hapus</button>
            </div>
        </div>
    </div>

<script>
// Pastikan URL API sudah disesuaikan dengan environment Anda (misal gunakan relative '/api' jika sudah dionlinekan)
const API = '/api';
let currentCode  = null;
let currentUser  = null;
let selectedFile = null;
let deleteDocId  = null;
let notifPollTimer = null;

// ── CLOCK ─────────────────────────────────────────────
function tickClock() {
    const el = document.getElementById('clock');
    if (el) el.textContent = new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(tickClock, 1000); tickClock();

// ── TOAST ─────────────────────────────────────────────
function showToast(title, msg, type='info') {
    const c = document.getElementById('toast-container');
    const colors = {success:'#059669',error:'#e11d48',info:'#0284c7',warning:'#d97706'};
    const icons  = {success:'check-circle-2',error:'x-circle',info:'info',warning:'alert-triangle'};
    const bgs    = {success:'bg-emerald-50 border-emerald-200',error:'bg-rose-50 border-rose-200',info:'bg-sky-50 border-sky-200',warning:'bg-amber-50 border-amber-200'};
    
    const t = document.createElement('div');
    t.className = `toast ${bgs[type]} border-2 rounded-2xl p-4 flex items-start gap-4 shadow-[4px_4px_0px_rgba(0,0,0,0.05)] relative overflow-hidden`;
    t.innerHTML = `
        <div class="flex-shrink-0 mt-0.5">
            <i data-lucide="${icons[type]}" class="w-6 h-6" style="color:${colors[type]}"></i>
        </div>
        <div>
            <div class="font-black text-sm text-slate-800">${title}</div>
            <div class="text-xs font-bold text-slate-600 mt-1">${msg}</div>
        </div>`;
    
    c.appendChild(t);
    lucide.createIcons();
    setTimeout(() => { 
        t.classList.add('out'); 
        setTimeout(() => t.remove(), 300); 
    }, 3800);
}

// ── LOGIN ─────────────────────────────────────────────
async function doLogin() {
    const code = document.getElementById('code-input').value.trim().toUpperCase();
    const err  = document.getElementById('login-err');
    const btn  = document.getElementById('login-btn');
    
    if (!code) { 
        err.textContent = 'Masukkan kode akses terlebih dahulu.'; 
        err.style.display = 'block'; 
        return; 
    }
    
    err.style.display = 'none';
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="animate-spin w-5 h-5"></i> Memverifikasi...';
    lucide.createIcons();
    
    try {
        const res = await fetch(`${API}/check-status`, {
            method: 'POST', 
            headers: {'Content-Type': 'application/json'}, 
            body: JSON.stringify({code})
        });
        const data = await res.json();
        
        if (!res.ok) throw new Error(data.message || 'Kode tidak valid');
        
        currentCode = code;
        currentUser = data.data;
        
        enterPortal();

    } catch(e) {
        err.textContent = e.message;
        err.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = 'Masuk Sekarang <i data-lucide="arrow-right" class="w-5 h-5"></i>';
        lucide.createIcons();
    }
}

function enterPortal() {
    // Sembunyikan halaman login
    const loginView = document.getElementById('view-login');
    if (loginView) loginView.style.display = 'none';

    // Tampilkan navigasi & tombol logout
    const navTabs = document.getElementById('nav-tabs-wrap');
    if (navTabs) navTabs.style.display = 'block';

    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) logoutBtn.style.display = 'flex';

    // Aktifkan tab "status" di navbar
    ['status','dokumen','notifikasi'].forEach(t => {
        const btn = document.getElementById(`tab-${t}`);
        if (btn) btn.classList.remove('active');
    });
    const statusTab = document.getElementById('tab-status');
    if (statusTab) statusTab.classList.add('active');

    // Tampilkan view status yang sesuai (accepted / pending / rejected)
    renderStatus();
    startNotifPoll();
}

// ── TABS & VIEWS ─────────────────────────────────────────────
function showTab(tab) {
    // Nonaktifkan semua tab button
    ['status','dokumen','notifikasi'].forEach(t => {
        const btn = document.getElementById(`tab-${t}`);
        if (btn) btn.classList.remove('active');
    });

    // Aktifkan tab yang dipilih
    const activeBtn = document.getElementById(`tab-${tab}`);
    if (activeBtn) activeBtn.classList.add('active');

    if (tab === 'status') {
        // Sembunyikan view lain
        ['view-dokumen','view-notifikasi','view-accepted','view-pending','view-rejected'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
        // Tampilkan view status sesuai data user
        renderStatus();
    } else if (tab === 'dokumen') {
        // Sembunyikan view status & notifikasi
        ['view-accepted','view-pending','view-rejected','view-notifikasi'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
        const dokView = document.getElementById('view-dokumen');
        if (dokView) dokView.style.display = 'grid';
        loadDocs();
    } else if (tab === 'notifikasi') {
        // Sembunyikan view status & dokumen
        ['view-accepted','view-pending','view-rejected','view-dokumen'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
        const notifView = document.getElementById('view-notifikasi');
        if (notifView) notifView.style.display = 'block';
        loadNotifs();
    }
}

function showView(id) {
    const el = document.getElementById(id);
    if (el) { el.style.display = ''; } // Dikosongkan agar Grid tailwind berfungsi
    lucide.createIcons();
}

function renderStatus() {
    const u = currentUser;
    if (!u) return;
    if (u.status === 'accepted') {
        document.getElementById('bp-name').textContent  = u.name;
        document.getElementById('bp-univ').textContent  = u.univ + ' · ' + u.major;
        document.getElementById('bp-code').textContent  = u.code;
        document.getElementById('bp-nim').textContent   = u.nim;
        document.getElementById('bp-major').textContent = u.major;
        document.getElementById('bp-loc').textContent   = u.location === 'kantor' ? '🏢 Head Office' : (u.location === 'terminal' ? '✈ Terminal Ops' : (u.lokasi_penempatan || u.location || '—'));

        // Tampilkan surat balasan jika ada
        const docList = document.getElementById('admin-doc-list');
        if (docList && u.reply_letter_url) {
            docList.innerHTML = `
                <a href="${u.reply_letter_url}" target="_blank" rel="noopener"
                   class="flex items-center gap-3 p-4 bg-cyan-50 border-2 border-cyan-200 rounded-2xl hover:bg-cyan-100 transition-colors no-underline">
                    <div class="w-10 h-10 rounded-xl bg-cyan-600 flex items-center justify-center shrink-0">
                        <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-black text-slate-800">${u.reply_letter_name || 'Surat Balasan Penerimaan'}</div>
                        <div class="text-[10px] font-bold text-cyan-600 mt-1">Klik untuk unduh ↗</div>
                    </div>
                </a>`;
            lucide.createIcons();
        }

        showView('view-accepted');
        loadAdminDocs();
    } else if (u.status === 'pending') {
        document.getElementById('pend-name').textContent = u.name;
        showView('view-pending');
    } else {
        const rejName = document.getElementById('rej-name');
        if(rejName) rejName.textContent = u.name;
        showView('view-rejected');
    }
}

// ── DOKUMEN ADMIN ───────────────────
let adminDocsList = [];

async function loadAdminDocs() {
    const el  = document.getElementById('admin-doc-list');
    const loc = (currentUser && currentUser.location) ? currentUser.location : null;

    if (!currentUser || !loc) {
        if (el && !el.querySelector('a')) {
            el.innerHTML = '<div class="text-xs font-bold text-slate-400 bg-slate-50 p-4 rounded-2xl border-2 border-dashed border-slate-200 text-center">Penempatan belum ditentukan.</div>';
        }
        return;
    }

    try {
        const res  = await fetch(`${API}/admin/documents?location=${loc}`);
        const data = await res.json();
        if (!res.ok) throw new Error('HTTP ' + res.status);

        const docs = (data.data && data.data[loc]) ? data.data[loc] : [];
        adminDocsList = docs;
        buildDocTypePills();

        // Hapus isi lama (selain surat balasan yang sudah ada), lalu append dokumen baru
        if (el) {
            // Pertahankan elemen surat balasan (tag <a>) jika ada
            const replyEl = el.querySelector('a');
            el.innerHTML = replyEl ? replyEl.outerHTML : '';

            if (!docs.length && !replyEl) {
                el.innerHTML = '<div class="text-xs font-bold text-slate-400 bg-slate-50 p-4 rounded-2xl border-2 border-dashed border-slate-200 text-center">Belum ada dokumen dari HRD.</div>';
                return;
            }

            el.innerHTML += docs.map(d => {
                const fileUrl = d.url || (d.file_path ? `/storage/${d.file_path}` : null);
                return `<div class="flex items-center gap-3 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center shrink-0">
                        <i data-lucide="file-check" class="w-5 h-5 text-cyan-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-black text-slate-800 truncate">${d.name}</div>
                        <div class="text-[10px] font-bold text-slate-500 mt-1">${d.file_size || ''} · ${d.uploaded_at || ''}</div>
                    </div>
                    ${fileUrl ? `<a href="${fileUrl}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center rounded-xl bg-cyan-100 text-cyan-700 hover:bg-cyan-200 transition-colors shrink-0">
                        <i data-lucide="download" class="w-4 h-4"></i>
                    </a>` : ''}
                </div>`;
            }).join('');

            lucide.createIcons();
        }
    } catch(e) {
        console.error('[loadAdminDocs]', e);
        if (el && !el.children.length) {
            el.innerHTML = '<div class="text-xs font-bold text-rose-500 bg-rose-50 p-4 rounded-2xl border-2 border-dashed border-rose-200 text-center">Gagal memuat dokumen.</div>';
        }
    }
}

// ── BUILD PILIHAN JENIS DOKUMEN ───────────────────────────
function buildDocTypePills() {
    const wrap = document.getElementById('doc-type-pills');
    if (!wrap) return;

    const standard = [
        { value: 'cv',              label: 'CV / Resume' },
        { value: 'transkrip',       label: 'Transkrip Nilai' },
        { value: 'ktm',             label: 'KTM / ID Card' },
        { value: 'surat_pengantar', label: 'Surat Kampus' },
    ];

    const fromAdmin = adminDocsList.map(d => ({
        value: 'lainnya', label: d.name, adminName: d.name, isAdmin: true,
    }));

    const pills = [...standard, ...fromAdmin, { value: 'lainnya', label: '+ Lainnya', isExtra: true }];

    wrap.innerHTML = pills.map((p, i) => `
        <button type="button"
            class="doc-pill px-4 py-2.5 rounded-xl text-xs font-black transition-all border-2 ${p.isExtra ? 'border-dashed border-slate-300 text-slate-500 bg-white' : 'border-slate-200 text-slate-500 bg-slate-50'}"
            data-value="${p.value}" data-admin-name="${p.adminName || ''}" data-index="${i}"
            onclick="selectDocPill(this)">
            ${p.label}
        </button>`).join('');

    const first = wrap.querySelector('.doc-pill');
    if (first) selectDocPill(first);
}

function selectDocPill(btn) {
    document.querySelectorAll('#doc-type-pills .doc-pill').forEach(p => {
        p.classList.remove('border-cyan-500', 'bg-cyan-50', 'text-cyan-700', 'shadow-[2px_2px_0px_rgba(6,182,212,0.2)]');
        if (!p.classList.contains('border-dashed')) {
            p.classList.add('border-slate-200', 'text-slate-500', 'bg-slate-50');
        }
    });
    
    btn.classList.remove('border-slate-200', 'text-slate-500', 'bg-slate-50');
    btn.classList.add('border-cyan-500', 'bg-cyan-50', 'text-cyan-700', 'shadow-[2px_2px_0px_rgba(6,182,212,0.2)]');

    const value     = btn.dataset.value;
    const adminName = btn.dataset.adminName;
    const isExtra   = btn.classList.contains('border-dashed');

    document.getElementById('doc-type').value = value;
    document.getElementById('doc-name-from-admin').value = adminName || '';

    const nameWrap = document.getElementById('doc-name-wrap');
    if (isExtra) {
        nameWrap.style.display = 'block';
        document.getElementById('doc-name').value = '';
        document.getElementById('doc-name').focus();
    } else {
        nameWrap.style.display = 'none';
        document.getElementById('doc-name').value = adminName || btn.textContent.trim();
    }
}

// ── UPLOAD DOKUMEN ────────────────────────────────────
function handleFileSelect(e) { const file = e.target.files[0]; if (file) setFile(file); }
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('upload-zone').classList.remove('border-cyan-500','bg-cyan-50');
    const file = e.dataTransfer.files[0];
    if (file) setFile(file);
}
function setFile(file) {
    if (file.size > 5 * 1024 * 1024) { showToast('File Terlalu Besar', 'Maksimal 5MB.', 'error'); return; }
    selectedFile = file;
    document.getElementById('file-name-preview').textContent = file.name;
    document.getElementById('file-size-preview').textContent = (file.size / 1024).toFixed(1) + ' KB';
    document.getElementById('file-preview').style.display = 'flex';
    document.getElementById('upload-zone').style.display = 'none';
    document.getElementById('upload-btn').disabled = false;
    lucide.createIcons();
}
function clearFile() {
    selectedFile = null;
    document.getElementById('file-input').value = '';
    document.getElementById('file-preview').style.display = 'none';
    document.getElementById('upload-zone').style.display = 'block';
    document.getElementById('upload-btn').disabled = true;
}

async function uploadDoc() {
    if (!selectedFile) return;

    const type       = document.getElementById('doc-type').value;
    const adminName  = document.getElementById('doc-name-from-admin').value;
    const manualName = document.getElementById('doc-name').value.trim();
    const docName    = adminName || manualName;

    if (!type) { showToast('Pilih Kategori', 'Pilih salah satu jenis dokumen.', 'error'); return; }
    if (!docName) { showToast('Nama Kosong', 'Masukkan nama dokumen.', 'error'); return; }

    const btn = document.getElementById('upload-btn');
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="animate-spin w-5 h-5"></i> Mengupload...';
    lucide.createIcons();
    
    try {
        const fd = new FormData();
        fd.append('file', selectedFile);
        fd.append('type', type);
        fd.append('name', docName);
        const res = await fetch(`${API}/applicant/${currentCode}/documents`, {method:'POST', body: fd});
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Upload gagal');
        
        showToast('Berhasil', 'Dokumen terkirim.', 'success');
        clearFile();
        loadDocs();
    } catch(e) {
        showToast('Gagal', e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="send" class="w-5 h-5"></i> Kirim Berkas';
        lucide.createIcons();
    }
}

async function loadDocs() {
    const el = document.getElementById('my-doc-list');
    if (!el) return;
    el.innerHTML = '<div class="text-center py-20"><i data-lucide="loader-2" class="animate-spin w-8 h-8 text-cyan-500 mx-auto mb-4"></i><div class="text-sm font-bold text-slate-500">Memuat data...</div></div>';
    lucide.createIcons();
    try {
        const res  = await fetch(`${API}/applicant/${currentCode}/documents`);
        const data = await res.json();
        const docs = data.data || [];
        
        if (!docs.length) {
            el.innerHTML = '<div class="text-center py-20"><div class="w-16 h-16 rounded-2xl bg-slate-50 border-2 border-slate-200 flex items-center justify-center mx-auto mb-4"><i data-lucide="folder-search" class="w-6 h-6 text-slate-400"></i></div><div class="text-sm font-bold text-slate-500">Belum ada dokumen diunggah</div></div>';
            lucide.createIcons();
            return;
        }

        const statusMap = { pending:'bg-amber-100 text-amber-700 border-amber-200', approved:'bg-emerald-100 text-emerald-700 border-emerald-200', rejected:'bg-rose-100 text-rose-700 border-rose-200' };
        const statusLabel = { pending:'Menunggu', approved:'Disetujui', rejected:'Ditolak' };
        
        el.innerHTML = docs.map(d => `
            <div class="flex items-center gap-4 p-5 bg-white border-2 border-slate-100 rounded-2xl hover:border-cyan-200 transition-colors">
                <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0">
                    <i data-lucide="file-text" class="w-6 h-6 text-slate-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="font-black text-slate-800 text-base truncate">${d.name}</div>
                        <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded border ${statusMap[d.status]}">${statusLabel[d.status]}</span>
                    </div>
                    <div class="text-xs font-bold text-slate-500">${d.file_name} · ${d.file_size}</div>
                    ${d.notes ? `<div class="text-[10px] font-bold text-rose-500 mt-2 bg-rose-50 p-2 rounded-lg border border-rose-100"><i data-lucide="info" class="w-3 h-3 inline mr-1"></i>${d.notes}</div>` : ''}
                </div>
                ${d.status === 'pending' ? `<button onclick="openDeleteModal(${d.id})" class="w-10 h-10 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 transition-colors shrink-0"><i data-lucide="trash-2" class="w-4 h-4"></i></button>` : ''}
            </div>`).join('');
        lucide.createIcons();
    } catch(e) {
        el.innerHTML = '<div class="text-center py-20 text-rose-500 font-bold">Gagal memuat dokumen</div>';
    }
}

// ── DELETE DOC ─────────────────────────────────────────
function openDeleteModal(id) { 
    deleteDocId = id; 
    const m = document.getElementById('delete-modal');
    if (m) m.style.display = 'flex'; 
}
function closeDeleteModal() { 
    deleteDocId = null; 
    const m = document.getElementById('delete-modal');
    if (m) m.style.display = 'none'; 
}
async function confirmDelete() {
    if (!deleteDocId) return;
    try {
        const res = await fetch(`${API}/applicant/${currentCode}/documents/${deleteDocId}`, {method:'DELETE'});
        const data = await res.json();
        if (!res.ok) throw new Error(data.message);
        showToast('Dihapus', 'Dokumen dihapus.', 'success');
        closeDeleteModal();
        loadDocs();
    } catch(e) {
        showToast('Gagal', e.message, 'error');
        closeDeleteModal();
    }
}

// ── NOTIFIKASI ─────────────────────────────────────────
async function loadNotifs() {
    const el = document.getElementById('notif-list');
    if (!el) return;
    try {
        const res  = await fetch(`${API}/applicant/${currentCode}/notifications`);
        const data = await res.json();
        const notifs = data.data || [];
        const unread = data.unread_count || 0;
        
        const ul = document.getElementById('unread-label');
        if(ul) ul.textContent = unread > 0 ? `${unread} Baru` : 'Semua Terbaca';
        
        const nd = document.getElementById('notif-dot');
        if (nd) { nd.classList.toggle('hidden', unread === 0); nd.classList.toggle('block', unread > 0); }
        
        if (!notifs.length) {
            el.innerHTML = '<div class="p-20 text-center text-sm font-bold text-slate-400">Belum ada notifikasi</div>';
            return;
        }
        
        const typeIcons = { status_change:'activity', document:'file-check', info:'info' };
        
        el.innerHTML = notifs.map(n => `
            <div class="p-6 sm:p-8 flex items-start gap-4 hover:bg-slate-50 transition-colors cursor-pointer ${n.is_read ? 'opacity-70' : ''}" onclick="markRead('${n.id}', this)">
                <div class="w-12 h-12 rounded-xl border-2 flex items-center justify-center shrink-0 ${n.is_read ? 'bg-slate-50 border-slate-200 text-slate-400' : 'bg-cyan-50 border-cyan-200 text-cyan-600'}">
                    <i data-lucide="${typeIcons[n.type] || 'bell'}" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="font-black text-slate-800 text-base ${n.is_read ? 'text-slate-600' : ''}">${n.title}</div>
                        ${!n.is_read ? '<span class="notif-badge w-2 h-2 rounded-full bg-rose-500"></span>' : ''}
                    </div>
                    <div class="text-sm font-semibold text-slate-500 leading-relaxed">${n.message.replace(/\*\*(.*?)\*\*/g,'<strong class="text-slate-700">$1</strong>')}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-3">${n.created_at}</div>
                </div>
            </div>`).join('');
        lucide.createIcons();
    } catch(e) {
        el.innerHTML = '<div class="p-20 text-center text-rose-500 font-bold">Gagal memuat notifikasi</div>';
    }
}

async function markRead(id, row) {
    const badge = row.querySelector('.notif-badge');
    if (badge) {
        badge.remove();
        row.classList.add('opacity-70');
        const iconBox = row.firstElementChild;
        iconBox.className = 'w-12 h-12 rounded-xl border-2 flex items-center justify-center shrink-0 bg-slate-50 border-slate-200 text-slate-400';
        
        await fetch(`${API}/applicant/${currentCode}/notifications/${id}/read`, {method:'PATCH'}).catch(()=>{});
        checkUnread();
    }
}

async function readAllNotifs() {
    await fetch(`${API}/applicant/${currentCode}/notifications/read-all`, {method:'POST'}).catch(()=>{});
    showToast('Berhasil', 'Semua notifikasi ditandai dibaca.', 'success');
    loadNotifs();
}

async function checkUnread() {
    try {
        const res  = await fetch(`${API}/applicant/${currentCode}/notifications`);
        const data = await res.json();
        const n = data.unread_count || 0;
        
        const nd = document.getElementById('notif-dot');
        if (nd) { nd.classList.toggle('hidden', n === 0); nd.classList.toggle('block', n > 0); }
        
        const ul = document.getElementById('unread-label');
        if (ul) ul.textContent = n > 0 ? `${n} Baru` : 'Semua Terbaca';
    } catch {}
}

function startNotifPoll() {
    checkUnread();
    notifPollTimer = setInterval(checkUnread, 30000); 
}

// ── LOGOUT ─────────────────────────────────────────────
function openLogoutModal()  { 
    const m = document.getElementById('logout-modal');
    if (m) m.style.display = 'flex'; 
}
function closeLogoutModal() { 
    const m = document.getElementById('logout-modal');
    if (m) m.style.display = 'none'; 
}
function doLogout() {
    clearInterval(notifPollTimer);
    currentCode = null; currentUser = null; selectedFile = null;
    
    ['nav-tabs-wrap','logout-btn'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
    
    document.getElementById('code-input').value = '';
    document.getElementById('login-err').style.display = 'none';
    const loginBtn = document.getElementById('login-btn');
    loginBtn.disabled = false;
    loginBtn.innerHTML = 'Masuk Sekarang <i data-lucide="arrow-right" class="w-5 h-5"></i>';
    
    ['view-accepted','view-pending','view-rejected','view-dokumen','view-notifikasi'].forEach(v => {
        const el = document.getElementById(v);
        if (el) el.style.display = 'none';
    });
    
    showView('view-login');
    closeLogoutModal();
    lucide.createIcons();
}

// ── INIT ───────────────────────────────────────────────

// ── COPY CODE ──────────────────────────────────────────
function copyCode() {
    const codeEl = document.getElementById('generated-code');
    const code = (codeEl && codeEl.textContent) ? codeEl.textContent.trim() : '';
    if (!code) return;
    navigator.clipboard.writeText(code).then(() => {
        const hint = document.getElementById('copy-hint');
        if (hint) { hint.style.display = 'block'; setTimeout(() => hint.style.display = 'none', 2000); }
    }).catch(() => {
        const el = document.createElement('textarea');
        el.value = code; document.body.appendChild(el);
        el.select(); document.execCommand('copy'); document.body.removeChild(el);
    });
}

// ── LOGOUT MODAL ───────────────────────────────────────
function openLogoutModal()  {
    const m = document.getElementById('logout-modal');
    if (m) m.style.display = 'flex';
}
function closeLogoutModal() {
    const m = document.getElementById('logout-modal');
    if (m) m.style.display = 'none';
}

// ── DELETE MODAL ───────────────────────────────────────
function openDeleteModal(docId) {
    deleteDocId = docId;
    const m = document.getElementById('delete-modal');
    if (m) m.style.display = 'flex';
}
function closeDeleteModal() {
    const m = document.getElementById('delete-modal');
    if (m) m.style.display = 'none';
    deleteDocId = null;
}

// ── CLOCK ──────────────────────────────────────────────
(function startClock() {
    function tick() {
        const now = new Date();
        const s = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit', hour12: false });
        const el = document.getElementById('clock');
        if (el) el.textContent = s;
    }
    tick();
    setInterval(tick, 1000);
})();

lucide.createIcons();
</script>