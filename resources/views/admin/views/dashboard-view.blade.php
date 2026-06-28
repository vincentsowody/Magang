{{-- ══ VIEW: DASHBOARD ══ --}}
<div id="view-dashboard" class="view-area" style="display: flex; flex-direction: column; gap: 20px;">

    {{-- Welcome Banner --}}
    <div class="welcome-banner shrink-0">
        <div style="position:relative;z-index:1;">
            <div class="welcome-tag">
                <span style="width:6px;height:6px;border-radius:50%;background:#4ADE80;"></span>
                HRD System
            </div>
            <h1 class="welcome-title">Selamat Datang, Admin! 👋</h1>
            <p class="welcome-desc">
                Pantau statistik pelamar, kelola penempatan divisi, dan verifikasi dokumen peserta magang InJourney Airports.
            </p>
        </div>
        <button onclick="switchView('candidates')" class="welcome-btn shrink-0">
            <i data-lucide="layout-list" style="width:16px;height:16px;color:#2563EB;"></i>
            Kelola Kandidat
        </button>
    </div>

    {{-- Stat Cards --}}
    <div class="stats-grid shrink-0">

        <div class="stat-card blue">
            <div class="stat-card-header">
                <div class="stat-icon"><i data-lucide="users" style="width:20px;height:20px;"></i></div>
                <span class="stat-badge">Total</span>
            </div>
            <div class="stat-number" id="stat-total">0</div>
            <div class="stat-label">Pelamar Masuk</div>
            <div class="stat-accent-bar"></div>
        </div>

        <div class="stat-card amber">
            <div class="stat-card-header">
                <div class="stat-icon"><i data-lucide="clock" style="width:20px;height:20px;"></i></div>
                <span class="stat-badge">Pending</span>
            </div>
            <div class="stat-number" id="stat-pending">0</div>
            <div class="stat-label">Menunggu Review</div>
            <div class="stat-accent-bar"></div>
        </div>

        <div class="stat-card green">
            <div class="stat-card-header">
                <div class="stat-icon"><i data-lucide="building-2" style="width:20px;height:20px;"></i></div>
                <span class="stat-badge">Lulus</span>
            </div>
            <div class="stat-number" id="stat-kantor">0</div>
            <div class="stat-label">Head Office</div>
            <div class="stat-accent-bar"></div>
        </div>

        <div class="stat-card teal">
            <div class="stat-card-header">
                <div class="stat-icon" style="background:#F0FDFA;color:#0D9488;">
                    <i data-lucide="plane" style="width:20px;height:20px;"></i>
                </div>
                <span class="stat-badge" style="background:#F0FDFA;color:#0D9488;border:1px solid #99F6E4;">Lulus</span>
            </div>
            <div class="stat-number" id="stat-terminal">0</div>
            <div class="stat-label">Terminal Ops</div>
            <div class="stat-accent-bar" style="background:linear-gradient(90deg,#0D9488,#2DD4BF);"></div>
        </div>

    </div>

    {{-- Quick Overview Panel --}}
    <div class="shrink-0" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        {{-- Ringkasan Status --}}
        <div class="panel">
            <div class="panel-header">
                <div style="display:flex;align-items:center;gap:8px;">
                    <i data-lucide="pie-chart" style="width:15px;height:15px;color:var(--primary);"></i>
                    <span class="panel-title">Ringkasan Status</span>
                </div>
            </div>
            <div class="panel-body" style="padding:16px 18px;">
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--text-secondary);">
                            <span style="width:8px;height:8px;border-radius:50%;background:#D97706;display:inline-block;"></span>
                            Menunggu Review
                        </div>
                        <span id="ov-pending" style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;color:var(--text-primary);">0</span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--text-secondary);">
                            <span style="width:8px;height:8px;border-radius:50%;background:#16A34A;display:inline-block;"></span>
                            Diterima
                        </div>
                        <span id="ov-accepted" style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;color:var(--text-primary);">0</span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--text-secondary);">
                            <span style="width:8px;height:8px;border-radius:50%;background:#DC2626;display:inline-block;"></span>
                            Ditolak
                        </div>
                        <span id="ov-rejected" style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;color:var(--text-primary);">0</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Akses Cepat --}}
        <div class="panel">
            <div class="panel-header">
                <div style="display:flex;align-items:center;gap:8px;">
                    <i data-lucide="zap" style="width:15px;height:15px;color:var(--warning);"></i>
                    <span class="panel-title">Akses Cepat</span>
                </div>
            </div>
            <div class="panel-body" style="padding:12px 14px;display:flex;flex-direction:column;gap:6px;">
                <button onclick="switchView('candidates')" style="display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;background:var(--surface-muted);border:1px solid var(--border);cursor:pointer;transition:all .15s;text-align:left;width:100%;" onmouseover="this.style.background='var(--primary-light)';this.style.borderColor='var(--primary-mid)'" onmouseout="this.style.background='var(--surface-muted)';this.style.borderColor='var(--border)'">
                    <i data-lucide="users" style="width:15px;height:15px;color:var(--primary);flex-shrink:0;"></i>
                    <span style="font-size:13px;font-weight:600;color:var(--text-primary);">Kelola Kandidat</span>
                    <i data-lucide="chevron-right" style="width:13px;height:13px;color:var(--text-muted);margin-left:auto;"></i>
                </button>
                <button onclick="switchView('documents')" style="display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;background:var(--surface-muted);border:1px solid var(--border);cursor:pointer;transition:all .15s;text-align:left;width:100%;" onmouseover="this.style.background='var(--success-light)';this.style.borderColor='var(--success-mid)'" onmouseout="this.style.background='var(--surface-muted)';this.style.borderColor='var(--border)'">
                    <i data-lucide="folder-open" style="width:15px;height:15px;color:var(--success);flex-shrink:0;"></i>
                    <span style="font-size:13px;font-weight:600;color:var(--text-primary);">Dokumen Penempatan</span>
                    <i data-lucide="chevron-right" style="width:13px;height:13px;color:var(--text-muted);margin-left:auto;"></i>
                </button>
                <button onclick="switchView('report')" style="display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;background:var(--surface-muted);border:1px solid var(--border);cursor:pointer;transition:all .15s;text-align:left;width:100%;" onmouseover="this.style.background='var(--purple-light)';this.style.borderColor='rgba(124,58,237,.2)'" onmouseout="this.style.background='var(--surface-muted)';this.style.borderColor='var(--border)'">
                    <i data-lucide="bar-chart-2" style="width:15px;height:15px;color:var(--purple);flex-shrink:0;"></i>
                    <span style="font-size:13px;font-weight:600;color:var(--text-primary);">Laporan Analitik</span>
                    <i data-lucide="chevron-right" style="width:13px;height:13px;color:var(--text-muted);margin-left:auto;"></i>
                </button>
            </div>
        </div>

    </div>

</div>

<script>
// Sync overview counters setiap kali switchView ke dashboard
(function() {
    const _origSwitch = window.switchView;
    window.switchView = function(view) {
        _origSwitch && _origSwitch(view);
        if (view === 'dashboard') _syncDashboardOverview();
    };

    function _syncDashboardOverview() {
        if (typeof applicants === 'undefined') return;
        const pending  = applicants.filter(a => a.status === 'pending').length;
        const accepted = applicants.filter(a => a.status === 'accepted').length;
        const rejected = applicants.filter(a => a.status === 'rejected').length;
        const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
        set('ov-pending',  pending);
        set('ov-accepted', accepted);
        set('ov-rejected', rejected);
    }

    // Sync saat pertama kali load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(_syncDashboardOverview, 800);
    });
})();
</script>