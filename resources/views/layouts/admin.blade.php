<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Control Tower — HRD InJourney')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#0a0f1e',
                            surface: '#111827',
                            card: '#1a2235',
                            border: 'rgba(255,255,255,0.08)',
                            primary: '#3b82f6',
                            glow: 'rgba(59,130,246,0.15)',
                        }
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    {{-- Nilai dinamis dari Laravel yang dibutuhkan file JS statis di public/js/admin --}}
    <script>
        window.APP_CONFIG = {
            apiBaseUrl: "{{ rtrim(config('services.recruitment_api.base_url', 'http://127.0.0.1:8000/api'), '/') }}",
            loginUrl: "{{ url('admin') }}",
            dashboardUrl: "{{ url('admin/dashboard') }}",
        };
    </script>

    @stack('styles')
</head>

<body class="flex overflow-hidden" style="height:100vh">
<div id="toast-container"></div>

@yield('content')

{{-- Urutan ini PENTING — beberapa file saling bergantung (lihat README di folder js/admin) --}}
<script src="{{ asset('js/admin/candidates.js') }}"></script>
<script src="{{ asset('js/admin/university-data.js') }}"></script>
<script src="{{ asset('js/admin/document-manager.js') }}"></script>
<script src="{{ asset('js/admin/doc-review.js') }}"></script>
<script src="{{ asset('js/admin/report.js') }}"></script>
<script src="{{ asset('js/admin/export.js') }}"></script>

@stack('scripts')
</body>
</html>
