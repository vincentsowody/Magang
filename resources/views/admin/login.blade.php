<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRD Login — InJourney Airports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">

    <script>
        window.APP_CONFIG = {
            dashboardUrl: "{{ url('admin/dashboard') }}",
        };
    </script>
</head>
<body>

    <!-- Background Layers -->
    <div class="radar-bg"></div>
    <div class="grid-lines"></div>

    <!-- Decorative radar rings -->
    <div class="radar-ring" style="width:600px;height:600px;bottom:-200px;left:-200px;"></div>
    <div class="radar-ring" style="width:900px;height:900px;bottom:-350px;left:-350px;"></div>
    <div class="radar-ring" style="width:300px;height:300px;top:-80px;right:80px;"></div>

    <!-- Plane flyby -->
    <div class="plane-flyby top-[30%]">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
    </div>
    <div class="plane-flyby top-[55%]" style="animation-delay:-7s;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-[400px]">

            <!-- Logo & Header -->
            <div class="text-center mb-8 fade-up">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl border border-white/10 bg-white/05 mb-5" style="background:rgba(0,185,232,0.08);">
                    <img src="{{ asset('img/logo.png') }}"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
                         alt="InJourney" class="h-7 brightness-0 invert">
                    <svg xmlns="http://www.w3.org/2000/svg" style="display:none" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#00b9e8" stroke-width="2"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Human Capital System</h1>
                <p class="text-slate-400 text-sm mt-1.5">InJourney Airports · Recruitment Portal</p>
            </div>

            <!-- Card -->
            <div class="login-card p-8 fade-up delay-1" id="login-card">

                <!-- Live status bar -->
                <div class="flex items-center justify-between mb-7 pb-6 border-b border-white/06" style="border-color:rgba(255,255,255,0.06)">
                    <div class="flex items-center gap-2.5">
                        <div class="status-dot"></div>
                        <span class="text-xs text-slate-400 font-medium">System Live</span>
                    </div>
                    <span class="text-xs font-mono text-slate-500" id="auth-clock">--:--</span>
                </div>

                <!-- Error Box -->
                <div class="error-box mb-5" id="error-alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>Kredensial tidak valid. Coba lagi.</span>
                </div>

                <form onsubmit="handleAdminLogin(event)" class="space-y-5" autocomplete="off">
                    <!-- Username -->
                    <div class="fade-up delay-2">
                        <label class="field-label">ID Pegawai</label>
                        <div class="field-wrap">
                            <svg class="field-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" id="username" required placeholder="Masukkan ID Pegawai" autocomplete="off">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="fade-up delay-3">
                        <label class="field-label">Password</label>
                        <div class="field-wrap" style="padding-right: 48px;">
                            <svg class="field-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" id="password" required placeholder="••••••••" style="padding-right:48px;">
                            <button type="button" onclick="togglePwd()" class="absolute right-14px top-0 bottom-0 flex items-center pr-4 text-slate-500 hover:text-slate-300 transition-colors" style="right:0">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between fade-up delay-3">
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <div class="w-4 h-4 rounded border border-white/15 flex items-center justify-center" style="background:rgba(255,255,255,0.04)">
                                <input type="checkbox" class="sr-only peer" id="remember">
                                <svg class="hidden peer-checked:block w-3 h-3 text-cyan-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <span class="text-sm text-slate-400 group-hover:text-slate-300 transition-colors">Ingat saya</span>
                        </label>
                        <a href="#" class="text-sm text-cyan-400 hover:text-cyan-300 font-medium transition-colors">Lupa password?</a>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2 fade-up delay-4">
                        <button type="submit" class="btn-login" id="btn-login">
                            <span id="btn-text">Masuk Dashboard</span>
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <p class="text-center text-xs text-slate-600 mt-7 pt-6" style="border-top:1px solid rgba(255,255,255,0.05)">
                    © 2025 PT Angkasa Pura Indonesia · Authorized Personnel Only
                </p>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/admin/login.js') }}"></script>
</body>
</html>
