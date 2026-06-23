<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — InJourney Airports Recruitment</title>

    {{-- Core libraries --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    {{-- Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

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

<body style="display:flex;height:100vh;overflow:hidden;margin:0">

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

@stack('scripts')
</body>
</html>