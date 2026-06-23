<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRD Login — InJourney Airports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .animate-fade-in { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
    window.APP_CONFIG = {
        dashboardUrl: "{{ url('admin/dashboard') }}",
        apiUrl: "{{ url('api') }}"
    };
    
    const API = "{{ url('api') }}";
    </script>
</head>
<body class="bg-slate-200 text-slate-800 font-sans min-h-screen flex flex-col items-center justify-center antialiased relative overflow-hidden">

    <div class="fixed inset-0 pointer-events-none z-0 opacity-40" 
         style="background-image: radial-gradient(#94a3b8 1.5px, transparent 1.5px); background-size: 24px 24px;">
    </div>
    <div class="fixed inset-0 pointer-events-none z-0 bg-gradient-to-tr from-cyan-500/10 via-transparent to-amber-500/10"></div>

    <div class="relative z-10 w-full max-w-md px-4 sm:px-0 flex flex-col items-center animate-fade-in">
        
        <img src="{{ asset('img/logo-injourney.png') }}" alt="InJourney Airports" 
             class="h-20 md:h-24 object-contain mix-blend-multiply mb-8 drop-shadow-sm"
             onerror="this.outerHTML='<div class=\\'text-4xl font-black text-slate-800 tracking-tighter mb-8\\'>HRD<span class=\\'text-cyan-600\\'>SYSTEM</span></div>'">

        <div class="w-full bg-white border-[3px] border-slate-200 rounded-[2.5rem] p-8 sm:p-10 shadow-[8px_16px_0px_rgba(15,23,42,0.05)] relative overflow-hidden">
            
            <div class="flex items-center justify-between mb-8 pb-5 border-b-2 border-slate-100">
                <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">System Live</span>
                </div>
                <span class="text-xs font-mono font-bold text-slate-400" id="auth-clock">--:--:--</span>
            </div>

            <div class="text-center mb-8">
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Human Capital System</h1>
                <p class="text-xs font-bold text-slate-500 mt-2 uppercase tracking-widest">Administrator Portal</p>
            </div>

            <div id="error-alert" style="display: none;" class="mb-6 bg-rose-50 border-2 border-rose-200 rounded-xl p-4 flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500 shrink-0"></i>
                <span class="text-xs font-bold text-rose-600 mt-0.5">Kredensial tidak valid. Silakan periksa kembali ID Pegawai dan Password Anda.</span>
            </div>

            <form onsubmit="handleAdminLogin(event)" class="space-y-5" autocomplete="off">
                
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ID Pegawai</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-cyan-600 transition-colors">
                            <i data-lucide="badge-check" class="w-5 h-5"></i>
                        </span>
                        <input type="text" id="username" required placeholder="Contoh: INJ-89012" autocomplete="off"
                            class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl pl-12 pr-4 py-3.5 text-sm font-bold text-slate-800 placeholder-slate-300 focus:outline-none focus:border-cyan-500 focus:bg-white transition-all shadow-inner">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-cyan-600 transition-colors">
                            <i data-lucide="lock-keyhole" class="w-5 h-5"></i>
                        </span>
                        <input type="password" id="password" required placeholder="••••••••"
                            class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl pl-12 pr-12 py-3.5 text-sm font-bold text-slate-800 placeholder-slate-300 focus:outline-none focus:border-cyan-500 focus:bg-white transition-all shadow-inner">
                        <button type="button" onclick="togglePwd()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-cyan-600 transition-colors">
                            <i id="eye-icon" data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 pb-2">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <div class="relative flex items-center justify-center w-5 h-5">
                            <input type="checkbox" id="remember" class="peer appearance-none w-5 h-5 border-2 border-slate-300 rounded bg-slate-50 checked:bg-cyan-600 checked:border-cyan-600 transition-all cursor-pointer">
                            <i data-lucide="check" class="w-3.5 h-3.5 text-white absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-500 group-hover:text-slate-800 transition-colors">Ingat Sesi Saya</span>
                    </label>
                    <a href="#" class="text-xs font-bold text-cyan-600 hover:text-cyan-800 transition-colors">Lupa Password?</a>
                </div>

                <button type="submit" id="btn-login" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-black text-sm py-4 rounded-2xl flex items-center justify-center gap-2 transition-all shadow-[4px_4px_0px_rgba(0,0,0,0.2)] active:translate-y-1 active:shadow-none border-2 border-slate-800 mt-2">
                    <span id="btn-text">Login</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t-2 border-slate-100 text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    © 2026 PT Angkasa Pura Indonesia<br>Authorized Personnel Only
                </p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function tickClock() {
            const el = document.getElementById('auth-clock');
            if (el) el.textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
        setInterval(tickClock, 1000); tickClock();

        function togglePwd() {
            const pwdInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                pwdInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>

    <script src="{{ asset('js/admin/login.js') }}?v={{ time() }}"></script>
</body>
</html>