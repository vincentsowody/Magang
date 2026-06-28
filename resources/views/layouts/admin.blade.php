<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — InJourney Airports Recruitment</title>

    {{-- Core libraries --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

    {{-- Design System CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    {{-- Tailwind config for custom tokens --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

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

<body>

    {{-- Toast container --}}
    <div id="toast-container"></div>

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