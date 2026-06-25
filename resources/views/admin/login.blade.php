<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — InJourney Airports</title>
    
    {{-- Core libraries --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        .animate-fade-in { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 font-sans min-h-screen flex flex-col items-center justify-center antialiased relative overflow-hidden selection:bg-sky-100">

    {{-- Background Pattern (Dot Grid) --}}
    <div class="fixed inset-0 pointer-events-none z-0 opacity-30" style="background-image: radial-gradient(#94a3b8 1px, transparent 1px); background-size: 20px 20px;"></div>
    
    <div class="relative z-10 w-full max-w-[400px] px-4 animate-fade-in flex flex-col items-center">
        
        {{-- Logo Area --}}
        <div class="text-center mb-8">
            <img src="{{ asset('img/logo-injourney.png') }}" alt="InJourney Airports" 
                 class="h-10 mx-auto object-contain mb-3"
                 onerror="this.outerHTML='<div class=\\'text-3xl font-black text-slate-800 tracking-tighter mb-3\\'>in<span class=\\'text-sky-500\\'>journey</span></div>'">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Administrator Portal</div>
        </div>

        {{-- Main Login Card --}}
        <div class="w-full bg-white rounded-[2rem] shadow-[0_8px_30px_-10px_rgba(15,23,42,0.08)] border border-slate-100 relative overflow-hidden p-8 sm:p-10">
            
            {{-- Top Accent Line --}}
            <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-500"></div>

            {{-- Status & Clock --}}
            <div class="flex items-center justify-between mb-8">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">System Live</span>
                </div>
                <div id="clock" class="font-mono text-[11px] font-bold text-slate-400">--.--.--</div>
            </div>

            {{-- Title Section --}}
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                    <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Human Capital System</h1>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">Masuk Sebagai Administrator</p>
            </div>

            {{-- Error Alert (Hidden by default) --}}
            <div id="login-err" class="hidden mb-5 bg-rose-50 border border-rose-100 text-rose-500 text-xs font-bold px-4 py-3 rounded-xl text-center"></div>

            {{-- Form --}}
            <form id="login-form" class="space-y-5" onsubmit="event.preventDefault(); doAdminLogin();">
                
                {{-- ID Pegawai --}}
                <div>
                    <label for="admin_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">ID Pegawai</label>
                    <div class="relative group">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        </span>
                        <input type="text" id="admin_id" name="admin_id" required placeholder="Contoh: INJ-89012"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all uppercase">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative group">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl pl-10 pr-10 py-3 text-sm font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        
                        <button type="button" onclick="togglePassword()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" title="Tampilkan Password">
                            <i data-lucide="eye" id="eye-icon" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" id="login-btn" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm py-3.5 rounded-xl flex items-center justify-center gap-2 mt-4 transition-all shadow-[0_4px_12px_rgba(15,23,42,0.15)] active:translate-y-0.5">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Masuk ke Sistem
                </button>

            </form>
        </div>

        {{-- Footer --}}
        <div class="mt-8 text-center">
            <p class="text-[10px] font-semibold text-slate-400">InJourney Airports · HRD Management System</p>
        </div>

    </div>

    {{-- Script Interaktif --}}
    <script>
        lucide.createIcons();

        // Real-time Clock
        function tickClock() {
            const el = document.getElementById('clock');
            if (el) {
                const now = new Date();
                el.textContent = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'}).replace(/:/g, '.');
            }
        }
        setInterval(tickClock, 1000); 
        tickClock();

        // Toggle Password Visibility
        function togglePassword() {
            const pwdInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                pwdInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons(); // Re-render the icon
        }

        // Dummy Login handler (Silakan sambungkan dengan endpoint API Login Admin Anda)
        function doAdminLogin() {
            const btn = document.getElementById('login-btn');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memverifikasi...';
            lucide.createIcons();

            // Simulasi proses login (hapus setTimeout ini dan ganti dengan fetch() ke backend Anda)
            setTimeout(() => {
                // Contoh jika berhasil, redirect ke dashboard:
                window.location.href = "{{ url('admin/dashboard') }}";
            }, 1000);
        }
    </script>
</body>
</html>