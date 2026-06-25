<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — InJourney Airports Recruitment</title>

    {{-- Core libraries --}}
    <script src="https://cdn.tailwindcss.com"></script> <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    {{-- CSS Kustom Ringan --}}
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Clean Utilities */
        .clean-panel { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1.25rem; box-shadow: 0 4px 20px -10px rgba(15, 23, 42, 0.05); }
        .stat-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.25rem; position: relative; overflow: hidden; transition: all 0.2s ease; }
        .stat-card:hover { border-color: #cbd5e1; box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.05); transform: translateY(-2px); }
        
        /* Toast Fix */
        .toast { animation: slideIn 0.3s ease forwards; background: white; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); margin-bottom: 10px; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

        /* =========================================
           👇 TAMBAHKAN CSS MODAL INI 👇
           ========================================= */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.6); /* Overlay gelap dengan opacity */
            backdrop-filter: blur(4px); /* Efek blur background */
            z-index: 100;
            display: none; /* SEMBUNYIKAN MODAL SECARA DEFAULT */
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        /* Class penanda saat modal dibuka via JavaScript */
        .modal-backdrop.open, 
        .modal-backdrop[style*="display: flex"], 
        .modal-backdrop[style*="display: block"] {
            display: flex !important;
            opacity: 1;
        }

        .modal-box {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.95);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .modal-backdrop.open .modal-box {
            transform: scale(1); /* Efek pop-up */
        }

        .modal-hdr {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .modal-body {
            padding: 1.5rem;
            overflow-y: auto;
        }
        .modal-ftr {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
            display: flex;
            gap: 0.75rem;
            border-bottom-left-radius: 1.5rem;
            border-bottom-right-radius: 1.5rem;
        }

        /* Styling Form dalam Modal */
        .form-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; display: block; }
        .form-input { width: 100%; padding: 0.625rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem; color: #334155; outline: none; transition: all 0.2s; }
        .form-input:focus { border-color: #0284c7; background: #ffffff; box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1); }
        .btn-primary { background: #0f172a; color: white; padding: 0.625rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s; cursor: pointer; border: none; }
        .btn-primary:hover { background: #1e293b; }
        .btn-icon { padding: 8px; border-radius: 10px; color: #64748b; transition: all 0.2s; background: transparent; border: none; cursor: pointer; }
        .btn-icon:hover { background: #f1f5f9; color: #0f172a; }
        /* ========================================= */
    </style>

    {{-- Dynamic config passed to JS --}}
    <script>
        (function() {
            var configUrl = "{{ rtrim(config('services.recruitment_api.base_url', ''), '/') }}";
            window.APP_CONFIG = {
                apiBaseUrl   : (configUrl && configUrl !== '') ? configUrl : '/api',
                loginUrl     : "{{ url('admin') }}",
                dashboardUrl : "{{ url('admin/dashboard') }}",
            };
        })();
    </script>

    @stack('styles')
</head>

<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 antialiased selection:bg-sky-100">

    <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 w-80 pointer-events-none"></div>

    @yield('content')

    {{-- JS — urutan ini penting --}}
    <script src="{{ asset('js/admin/candidates.js') }}"></script>
    <script src="{{ asset('js/admin/university-data.js') }}"></script>
    <script src="{{ asset('js/admin/document-manager.js') }}"></script>
    <script src="{{ asset('js/admin/doc-review.js') }}"></script>
    <script src="{{ asset('js/admin/report.js') }}"></script>
    <script src="{{ asset('js/admin/export.js') }}"></script>
    <script src="{{ asset('js/admin/import.js') }}"></script>

    <script>lucide.createIcons();</script>
    @stack('scripts')
</body>
</html>