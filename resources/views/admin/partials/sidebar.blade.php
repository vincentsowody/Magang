    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <img src="{{ asset('img/logo.png') }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" alt="IJ" style="height:18px;filter:brightness(0) invert(1)">
                <i data-lucide="plane" style="display:none;width:16px;height:16px;color:var(--accent)"></i>
            </div>
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--text-primary)">HRD<span style="color:var(--accent)">PRO</span></div>
                <div style="font-size:9px;color:var(--text-muted);letter-spacing:.1em;text-transform:uppercase">Recruitment</div>
            </div>
        </div>

        <nav style="flex:1;padding:8px 0;overflow-y:auto">
            <div class="nav-section-label">Menu Utama</div>
            <a href="#" onclick="switchView('dashboard');return false" class="nav-item active" id="nav-dashboard">
                <i data-lucide="layout-dashboard" class="nav-icon"></i> Dashboard
            </a>
            <a href="#" onclick="switchView('candidates');return false" class="nav-item" id="nav-candidates">
                <i data-lucide="users" class="nav-icon"></i> Kandidat
            </a>
            <a href="#" onclick="switchView('documents');return false" class="nav-item" id="nav-documents">
                <i data-lucide="folder" class="nav-icon"></i> Dokumen
                <span class="nav-badge" id="doc-badge" style="display:none">0</span>
            </a>
            <a href="#" onclick="switchView('report');return false" class="nav-item" id="nav-report">
                <i data-lucide="bar-chart-2" class="nav-icon"></i> Laporan
            </a>
            <div class="nav-section-label">Sistem</div>
            <a href="#" onclick="showToast('Dibatasi','Fitur ini untuk Super Admin.','error');return false" class="nav-item">
                <i data-lucide="database" class="nav-icon"></i> Reset Data
            </a>
        </nav>

        <div class="sidebar-bottom">
            <button class="user-btn" onclick="openLogoutModal()">
                <div class="user-avatar">AD</div>
                <div style="flex:1">
                    <div style="font-size:12px;font-weight:600;color:var(--text-primary)">Admin HRD</div>
                    <div style="font-size:10px;color:var(--text-muted)">Klik untuk logout</div>
                </div>
                <i data-lucide="log-out" style="width:13px;height:13px;color:var(--text-muted)"></i>
            </button>
        </div>
    </aside>
