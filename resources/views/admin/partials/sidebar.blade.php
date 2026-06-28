{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar" id="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <img src="{{ asset('img/logo-injourney.png') }}"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                 alt="IJ" style="height:18px;object-fit:contain;filter:brightness(0) invert(1);">
            <i data-lucide="plane-takeoff" style="display:none;width:18px;height:18px;color:#fff;"></i>
        </div>
        <div class="sidebar-logo-text">
            <div class="sidebar-logo-name">HRD<span style="color:#60A5FA">PRO</span></div>
            <div class="sidebar-logo-sub">Recruitment System</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <span class="nav-section-label">Menu Utama</span>

        <a href="#" onclick="switchView('dashboard');closeSidebarMobile();return false"
           class="nav-item active" id="nav-dashboard">
            <i data-lucide="layout-dashboard" class="nav-icon"></i>
            Dashboard
        </a>

        <a href="#" onclick="switchView('candidates');closeSidebarMobile();return false"
           class="nav-item" id="nav-candidates">
            <i data-lucide="users" class="nav-icon"></i>
            Kandidat
        </a>

        <a href="#" onclick="switchView('documents');closeSidebarMobile();return false"
           class="nav-item" id="nav-documents">
            <i data-lucide="folder-open" class="nav-icon"></i>
            Dokumen
            <span class="nav-badge" id="doc-badge" style="display:none">0</span>
        </a>

        <a href="#" onclick="switchView('report');closeSidebarMobile();return false"
           class="nav-item" id="nav-report">
            <i data-lucide="bar-chart-2" class="nav-icon"></i>
            Laporan
        </a>

        <div class="nav-separator"></div>
        <span class="nav-section-label">Sistem</span>

        <a href="#" onclick="showToast('Dibatasi','Fitur ini hanya untuk Super Admin.','warning');return false"
           class="nav-item">
            <i data-lucide="database" class="nav-icon"></i>
            Reset Data
        </a>
    </nav>

    {{-- User Footer --}}
    <div class="sidebar-footer">
        <button class="sidebar-user w-full text-left" onclick="openLogoutModal()">
            <div class="sidebar-avatar">AD</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">Admin HRD</div>
                <div class="sidebar-user-role">Klik untuk logout</div>
            </div>
            <i data-lucide="log-out" class="sidebar-logout-icon"></i>
        </button>
    </div>

</aside>

{{-- Mobile overlay --}}
<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebarMobile()"></div>

<script>
function openSidebarMobile() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebar-overlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeSidebarMobile() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('show');
    document.body.style.overflow = '';
}
</script>