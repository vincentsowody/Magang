<aside class="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="logo-icon">
            <img src="{{ asset('img/logo-injourney.png') }}"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                 alt="IJ" style="height:16px;filter:brightness(0) invert(1)">
            <i data-lucide="plane" style="display:none;width:14px;height:14px;color:var(--accent)"></i>
        </div>
        <div>
            <div style="font-size:13px;font-weight:800;color:var(--text-1);letter-spacing:-.01em">
                HRD<span style="color:var(--accent)">PRO</span>
            </div>
            <div style="font-size:9px;color:var(--text-3);letter-spacing:.12em;text-transform:uppercase;margin-top:1px">Recruitment</div>
        </div>
    </div>

    {{-- Nav --}}
    <nav style="flex:1;padding:6px 0;overflow-y:auto">
        <div class="nav-section-label">Menu Utama</div>

        <a href="#" onclick="switchView('dashboard');return false" class="nav-item active" id="nav-dashboard">
            <i data-lucide="layout-dashboard" class="nav-icon"></i>
            Dashboard
        </a>
        <a href="#" onclick="switchView('candidates');return false" class="nav-item" id="nav-candidates">
            <i data-lucide="users" class="nav-icon"></i>
            Kandidat
        </a>
        <a href="#" onclick="switchView('documents');return false" class="nav-item" id="nav-documents">
            <i data-lucide="folder" class="nav-icon"></i>
            Dokumen
            <span class="nav-badge" id="doc-badge" style="display:none">0</span>
        </a>
        <a href="#" onclick="switchView('report');return false" class="nav-item" id="nav-report">
            <i data-lucide="bar-chart-2" class="nav-icon"></i>
            Laporan
        </a>

        <div class="nav-section-label">Sistem</div>
        <a href="#" onclick="showToast('Dibatasi','Fitur ini hanya untuk Super Admin.','error');return false" class="nav-item">
            <i data-lucide="database" class="nav-icon"></i>
            Reset Data
        </a>
    </nav>

    {{-- User --}}
    <div class="sidebar-bottom">
        <button class="user-btn" onclick="openLogoutModal()">
            <div class="user-avatar">AD</div>
            <div style="flex:1;min-width:0">
                <div style="font-size:12px;font-weight:600;color:var(--text-1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">Admin HRD</div>
                <div style="font-size:10px;color:var(--text-3);margin-top:1px">Klik untuk logout</div>
            </div>
            <i data-lucide="log-out" style="width:13px;height:13px;color:var(--text-3);flex-shrink:0"></i>
        </button>
    </div>

</aside>