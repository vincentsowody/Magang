<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Portal PKL — InJourney Airports</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--bg:#080f1d;--surface:#0f1829;--card:#162035;--border:rgba(255,255,255,0.07);--border2:rgba(255,255,255,0.12);--primary:#3b82f6;--primary-dim:rgba(59,130,246,0.12);--text:#f1f5f9;--muted:#64748b;--green:#22c55e;--amber:#f59e0b;--red:#ef4444}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{min-height:100vh}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text)}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-track{background:transparent}::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.1);border-radius:4px}

/* BG */
.bg-glow{position:fixed;inset:0;pointer-events:none;z-0;background:radial-gradient(ellipse 60% 50% at 50% 100%,rgba(59,130,246,0.06) 0%,transparent 70%)}
.bg-grid{position:fixed;inset:0;pointer-events:none;z:0;background-image:linear-gradient(rgba(59,130,246,0.025) 1px,transparent 1px),linear-gradient(90deg,rgba(59,130,246,0.025) 1px,transparent 1px);background-size:52px 52px}

/* HEADER */
.site-header{position:sticky;top:0;z-index:50;height:60px;background:rgba(8,15,29,0.92);backdrop-filter:blur(16px);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 24px}
.brand{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:700}
.brand-pill{background:var(--primary-dim);border:1px solid rgba(59,130,246,0.2);padding:3px 10px;border-radius:6px;font-size:10px;font-weight:700;color:var(--primary);letter-spacing:.06em}
#clock{font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted)}

/* NAV TABS (when logged in) */
.nav-tabs{display:flex;gap:2px;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:10px;padding:3px}
.nav-tab{padding:6px 14px;border-radius:7px;font-size:12px;font-weight:500;color:var(--muted);cursor:pointer;transition:all .18s;display:flex;align-items:center;gap:6px;border:none;background:none}
.nav-tab:hover{color:var(--text)}
.nav-tab.active{background:var(--card);color:var(--text);box-shadow:0 1px 3px rgba(0,0,0,0.3)}
.nav-tab .notif-dot{width:6px;height:6px;border-radius:50%;background:var(--red);display:none}
.nav-tab .notif-dot.show{display:block;animation:pulse 1.5s infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.4)}}

/* MAIN */
main{min-height:calc(100vh - 60px);display:flex;align-items:center;justify-content:center;padding:24px 16px;position:relative;z-index:1}
.view{display:none;width:100%;max-width:480px;animation:fadeUp .4s cubic-bezier(.22,1,.36,1) both}
.view.wide{max-width:760px}
.view.active{display:block}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}

/* LOGIN CARD */
.login-card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:36px;box-shadow:0 32px 64px rgba(0,0,0,0.5)}
.inp-wrap{position:relative;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:12px;transition:all .2s}
.inp-wrap:focus-within{border-color:rgba(59,130,246,0.4);background:rgba(59,130,246,0.04);box-shadow:0 0 0 3px rgba(59,130,246,0.08)}
.inp-wrap input{background:transparent;outline:none;width:100%;padding:13px 14px 13px 44px;font-family:'JetBrains Mono',monospace;font-size:14px;font-weight:500;color:var(--text);letter-spacing:.08em}
.inp-wrap input::placeholder{font-family:'Inter',sans-serif;letter-spacing:0;color:rgba(100,116,139,.5)}
.inp-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:rgba(100,116,139,.5)}
.btn-login{width:100%;padding:14px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);border-radius:12px;font-weight:700;font-size:14px;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .25s;border:none}
.btn-login:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(59,130,246,0.35)}
.btn-login:disabled{opacity:.5;cursor:not-allowed;transform:none}

/* BOARDING PASS */
.bp-card{background:var(--surface);border:1px solid var(--border);border-radius:20px;overflow:hidden;box-shadow:0 40px 80px rgba(0,0,0,.5)}
.bp-hdr{background:linear-gradient(135deg,#050f1e,#0d2040);padding:28px 28px 24px;position:relative;overflow:hidden;border-bottom:1px dashed rgba(255,255,255,0.07)}
.bp-hdr::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 80% 50%,rgba(59,130,246,0.1) 0%,transparent 60%)}
.bp-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(59,130,246,0.12);border:1px solid rgba(59,130,246,0.2);border-radius:6px;padding:3px 10px;font-size:10px;font-weight:700;letter-spacing:.1em;color:var(--primary);margin-bottom:12px}
.bp-name{font-size:24px;font-weight:700;color:#fff;line-height:1;margin-bottom:4px}
.bp-sub{font-size:12px;color:rgba(148,163,184,.6)}
.bp-meta{display:flex;flex-wrap:wrap;gap:24px;margin-top:20px;position:relative;z-index:1}
.bp-field label{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(100,116,139,.6);display:block;margin-bottom:3px}
.bp-field .val{font-size:14px;font-weight:600;color:var(--text)}
.bp-field .mono{font-family:'JetBrains Mono',monospace;color:#fbbf24}
.bp-field .loc{font-size:16px;font-weight:700;color:var(--primary)}

/* STATUS CARD (pending/rejected) */
.status-card{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:40px 32px;text-align:center;box-shadow:0 32px 64px rgba(0,0,0,.4)}
.status-icon{width:68px;height:68px;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 18px}

/* PANEL (dokumen & notif) */
.panel{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.panel-hdr{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.panel-title{font-size:13px;font-weight:600;color:var(--text)}

/* UPLOAD ZONE */
.upload-zone{border:2px dashed rgba(255,255,255,0.1);border-radius:12px;padding:28px;text-align:center;cursor:pointer;transition:all .2s}
.upload-zone:hover,.upload-zone.over{border-color:var(--primary);background:var(--primary-dim)}

/* DOC ROW */
.doc-row{display:flex;align-items:center;gap:12px;padding:11px 16px;border-bottom:1px solid rgba(255,255,255,0.04);transition:background .15s}
.doc-row:hover{background:rgba(255,255,255,0.02)}
.doc-row:last-child{border:none}
.doc-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.doc-name{font-size:13px;font-weight:500;color:var(--text)}
.doc-meta{font-size:11px;color:var(--muted);margin-top:2px}
.doc-status{font-size:10px;font-weight:700;padding:2px 7px;border-radius:5px;flex-shrink:0}
.ds-pending{background:rgba(245,158,11,.12);color:var(--amber)}
.ds-approved{background:rgba(34,197,94,.12);color:var(--green)}
.ds-rejected{background:rgba(239,68,68,.12);color:var(--red)}
.type-badge{font-size:9px;font-weight:700;padding:2px 6px;border-radius:4px;background:var(--primary-dim);color:var(--primary);margin-left:6px}

/* NOTIF ROW */
.notif-row{display:flex;gap:12px;padding:13px 16px;border-bottom:1px solid rgba(255,255,255,0.04);transition:background .15s;cursor:pointer}
.notif-row:hover{background:rgba(255,255,255,0.02)}
.notif-row:last-child{border:none}
.notif-row.unread{background:rgba(59,130,246,0.03)}
.notif-dot-left{width:6px;height:6px;border-radius:50%;background:var(--primary);margin-top:6px;flex-shrink:0}
.notif-title{font-size:13px;font-weight:600;color:var(--text)}
.notif-msg{font-size:12px;color:var(--muted);margin-top:3px;line-height:1.5}
.notif-time{font-size:10px;color:rgba(100,116,139,.5);margin-top:4px}

/* BUTTONS */
.btn-primary{background:var(--primary);color:#fff;font-size:13px;font-weight:600;padding:9px 16px;border-radius:9px;display:inline-flex;align-items:center;gap:7px;cursor:pointer;transition:all .2s;border:none}
.btn-primary:hover{background:#2563eb;transform:translateY(-1px)}
.btn-primary:disabled{opacity:.5;cursor:not-allowed;transform:none}
.btn-ghost{background:rgba(255,255,255,0.05);border:1px solid var(--border);color:var(--muted);font-size:12px;font-weight:500;padding:7px 13px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;cursor:pointer;transition:all .2s}
.btn-ghost:hover{border-color:var(--border2);color:var(--text)}
.btn-icon{background:rgba(255,255,255,0.04);border:1px solid var(--border);color:var(--muted);width:30px;height:30px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;flex-shrink:0}
.btn-icon:hover{border-color:rgba(59,130,246,.3);color:var(--primary);background:var(--primary-dim)}
.btn-danger:hover{border-color:rgba(239,68,68,.3)!important;color:var(--red)!important;background:rgba(239,68,68,.08)!important}

/* FORM */
.form-label{font-size:11px;font-weight:600;color:rgba(148,163,184,.8);display:block;margin-bottom:5px}
.form-select{width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:9px;color:var(--text);padding:9px 12px;font-size:13px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s}
.form-select:focus{border-color:rgba(59,130,246,.4)}
.form-input{width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:9px;color:var(--text);padding:9px 12px;font-size:13px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s}
.form-input:focus{border-color:rgba(59,130,246,.4)}
.form-input::placeholder{color:rgba(100,116,139,.5)}

/* MODAL */
.modal-bd{position:fixed;inset:0;background:rgba(8,15,29,.85);backdrop-filter:blur(6px);z-index:100;display:none;align-items:center;justify-content:center;padding:16px}
.modal-bd.open{display:flex}
.modal-box{background:var(--card);border:1px solid var(--border2);border-radius:16px;max-width:420px;width:100%;padding:24px;box-shadow:0 24px 60px rgba(0,0,0,.6);animation:popIn .2s ease}
@keyframes popIn{from{opacity:0;transform:scale(.96) translateY(8px)}to{opacity:1;transform:none}}

/* TOAST */
#toast-container{position:fixed;top:70px;right:16px;z-index:9999;display:flex;flex-direction:column;gap:8px;width:300px;pointer-events:none}
.toast{background:var(--card);border:1px solid var(--border2);border-radius:12px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px;box-shadow:0 8px 24px rgba(0,0,0,.4);pointer-events:all;animation:slideIn .25s ease}
@keyframes slideIn{from{opacity:0;transform:translateX(12px)}to{opacity:1;transform:none}}
.toast.out{opacity:0;transform:translateX(12px);transition:all .3s}
.toast-t{font-size:12px;font-weight:600;color:var(--text)}
.toast-m{font-size:11px;color:var(--muted);margin-top:2px}

/* PROGRESS */
.progress-steps{display:flex;gap:4px;padding:16px 18px;background:rgba(255,255,255,0.02);border-bottom:1px solid var(--border)}
.prog-step{flex:1;height:3px;border-radius:3px;background:rgba(255,255,255,0.08);transition:background .3s}
.prog-step.done{background:var(--green)}
.prog-step.active{background:var(--amber);animation:shimmer 1.5s infinite}
@keyframes shimmer{0%,100%{opacity:1}50%{opacity:.6}}

.spin{animation:spin 1s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}

/* DOC TYPE PILLS */
.doc-pill{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;border-radius:20px;font-size:12px;font-weight:500;cursor:pointer;border:1px solid var(--border);background:rgba(255,255,255,0.03);color:var(--muted);transition:all .18s;white-space:nowrap}
.doc-pill:hover{border-color:rgba(59,130,246,.3);color:var(--text);background:rgba(59,130,246,.05)}
.doc-pill.selected{background:var(--primary-dim);border-color:rgba(59,130,246,.4);color:var(--primary);font-weight:600}
.doc-pill.pill-extra{border-style:dashed;border-color:rgba(100,116,139,.3)}
.doc-pill.pill-extra.selected{background:rgba(139,92,246,.1);border-color:rgba(139,92,246,.4);color:#a78bfa;border-style:solid}
</style>
</head>
<body>
<div class="bg-glow"></div>
<div class="bg-grid"></div>

<!-- HEADER -->
<header class="site-header">
    <div class="brand">
        <div style="width:30px;height:30px;border-radius:8px;background:var(--primary-dim);border:1px solid rgba(59,130,246,.25);display:flex;align-items:center;justify-content:center">
            <i data-lucide="plane" style="width:15px;height:15px;color:var(--primary)"></i>
        </div>
        <span style="color:var(--text)">HRD<span style="color:var(--primary)">PRO</span></span>
        <span class="brand-pill">INTERNSHIP PORTAL</span>
    </div>
    <div style="display:flex;align-items:center;gap:12px">
        <div id="nav-tabs-wrap" style="display:none">
            <div class="nav-tabs">
                <button class="nav-tab active" id="tab-status" onclick="showTab('status')">
                    <i data-lucide="layout-dashboard" style="width:13px;height:13px"></i> Status
                </button>
                <button class="nav-tab" id="tab-dokumen" onclick="showTab('dokumen')">
                    <i data-lucide="upload-cloud" style="width:13px;height:13px"></i> Dokumen
                    <span class="notif-dot" id="doc-dot"></span>
                </button>
                <button class="nav-tab" id="tab-notifikasi" onclick="showTab('notifikasi')">
                    <i data-lucide="bell" style="width:13px;height:13px"></i> Notifikasi
                    <span class="notif-dot" id="notif-dot"></span>
                </button>
            </div>
        </div>
        <span id="clock"></span>
        <button class="btn-icon" id="logout-btn" style="display:none" onclick="openLogoutModal()" title="Sign Out">
            <i data-lucide="log-out" style="width:13px;height:13px"></i>
        </button>
    </div>
</header>

<div id="toast-container"></div>

<main>
    <!-- ── VIEW: LOGIN ── -->
    <div id="view-login" class="view active">
        <div class="login-card">
            <div style="text-align:center;margin-bottom:28px">
                <div style="width:52px;height:52px;border-radius:14px;background:var(--primary-dim);border:1px solid rgba(59,130,246,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                    <i data-lucide="key-round" style="width:22px;height:22px;color:var(--primary)"></i>
                </div>
                <h1 style="font-size:20px;font-weight:700;color:var(--text);margin-bottom:6px">Check-in PKL</h1>
                <p style="font-size:13px;color:var(--muted)">Masukkan kode akses yang diberikan HRD</p>
            </div>
            <div style="margin-bottom:16px">
                <label class="form-label">Kode Akses</label>
                <div class="inp-wrap">
                    <span class="inp-icon"><i data-lucide="hash" style="width:16px;height:16px"></i></span>
                    <input type="text" id="code-input" placeholder="Contoh: MAG-2025-001"
                        autocomplete="off" autocapitalize="characters"
                        oninput="this.value=this.value.toUpperCase()"
                        onkeydown="if(event.key==='Enter') doLogin()">
                </div>
            </div>
            <button class="btn-login" onclick="doLogin()" id="login-btn">
                <i data-lucide="log-in" style="width:16px;height:16px"></i>
                Masuk ke Portal
            </button>
            <p id="login-err" style="margin-top:12px;text-align:center;font-size:12px;color:var(--red);display:none"></p>
        </div>
    </div>

    <!-- ── VIEW: STATUS (accepted - boarding pass) ── -->
    <div id="view-accepted" class="view wide">
        <div class="bp-card">
            <!-- Progress steps -->
            <div class="progress-steps">
                <div class="prog-step done"></div>
                <div class="prog-step done"></div>
                <div class="prog-step" id="prog-docs"></div>
                <div class="prog-step" style="background:rgba(255,255,255,0.08)"></div>
            </div>
            <!-- Header -->
            <div class="bp-hdr">
                <div class="bp-badge"><span>●</span> DITERIMA</div>
                <div class="bp-name" id="bp-name">—</div>
                <div class="bp-sub" id="bp-univ">—</div>
                <div class="bp-meta">
                    <div class="bp-field">
                        <label>Kode Akses</label>
                        <div class="val mono" id="bp-code">—</div>
                    </div>
                    <div class="bp-field">
                        <label>NIM</label>
                        <div class="val" id="bp-nim">—</div>
                    </div>
                    <div class="bp-field">
                        <label>Program Studi</label>
                        <div class="val" id="bp-major">—</div>
                    </div>
                    <div class="bp-field">
                        <label>Penempatan</label>
                        <div class="val loc" id="bp-loc">—</div>
                    </div>
                </div>
            </div>
            <!-- Body: info + dokumen admin -->
            <div style="padding:20px 24px;display:grid;grid-template-columns:1fr 1fr;gap:20px">
                <div>
                    <div style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(100,116,139,.6);margin-bottom:12px">Informasi Penting</div>
                    <div id="bp-notices">
                        <div style="display:flex;gap:8px;align-items:flex-start;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.04);font-size:12px;color:rgba(148,163,184,.7)">
                            <div style="width:5px;height:5px;border-radius:50%;background:var(--primary);margin-top:5px;flex-shrink:0"></div>
                            Bawa dokumen identitas asli pada hari pertama
                        </div>
                        <div style="display:flex;gap:8px;align-items:flex-start;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.04);font-size:12px;color:rgba(148,163,184,.7)">
                            <div style="width:5px;height:5px;border-radius:50%;background:var(--primary);margin-top:5px;flex-shrink:0"></div>
                            Pastikan semua dokumen persyaratan sudah diupload
                        </div>
                        <div style="display:flex;gap:8px;align-items:flex-start;padding:8px 0;font-size:12px;color:rgba(148,163,184,.7)">
                            <div style="width:5px;height:5px;border-radius:50%;background:var(--primary);margin-top:5px;flex-shrink:0"></div>
                            Hubungi HRD jika ada pertanyaan
                        </div>
                    </div>
                </div>
                <div>
                    <div style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(100,116,139,.6);margin-bottom:12px">Dokumen dari HRD</div>
                    <div id="admin-doc-list">
                        <div style="font-size:12px;color:var(--muted);padding:12px 0">Memuat dokumen...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── VIEW: STATUS (pending) ── -->
    <div id="view-pending" class="view">
        <div class="status-card">
            <div class="status-icon" style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2)">
                <i data-lucide="clock" style="width:30px;height:30px;color:var(--amber)"></i>
            </div>
            <h2 style="font-size:20px;font-weight:700;margin-bottom:8px">Sedang Direview</h2>
            <p style="font-size:13px;color:var(--muted);line-height:1.7;max-width:300px;margin:0 auto 20px">
                Lamaran <strong id="pend-name" style="color:var(--text)">Anda</strong> sedang dalam proses review oleh tim HRD. Mohon bersabar dan pastikan dokumen sudah dilengkapi.
            </p>
            <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:10px;padding:12px 16px;font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted);margin-bottom:20px">
                Status: <span style="color:var(--amber);font-weight:600">PENDING REVIEW</span>
            </div>
            <button onclick="showTab('dokumen')" class="btn-primary" style="font-size:13px">
                <i data-lucide="upload-cloud" style="width:14px;height:14px"></i>
                Upload Dokumen Persyaratan
            </button>
        </div>
    </div>

    <!-- ── VIEW: STATUS (rejected) ── -->
    <div id="view-rejected" class="view">
        <div class="status-card">
            <div class="status-icon" style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2)">
                <i data-lucide="x-circle" style="width:30px;height:30px;color:var(--red)"></i>
            </div>
            <h2 style="font-size:20px;font-weight:700;margin-bottom:8px">Belum Berhasil</h2>
            <p style="font-size:13px;color:var(--muted);line-height:1.7;max-width:300px;margin:0 auto 20px">
                Terima kasih atas minat <strong id="rej-name" style="color:var(--text)">Anda</strong>. Mohon maaf, lamaran PKL Anda belum dapat kami terima untuk batch ini.
            </p>
            <button onclick="showTab('notifikasi')" class="btn-ghost">
                <i data-lucide="bell" style="width:13px;height:13px"></i>
                Lihat Detail Notifikasi
            </button>
        </div>
    </div>

    <!-- ── VIEW: DOKUMEN UPLOAD ── -->
    <div id="view-dokumen" class="view wide" style="display:none">
        <div style="display:flex;flex-direction:column;gap:14px">
            <!-- Upload section -->
            <div class="panel">
                <div class="panel-hdr">
                    <div>
                        <div class="panel-title">Upload Dokumen Persyaratan</div>
                        <div style="font-size:11px;color:var(--muted);margin-top:2px">Max 5MB per file · PDF, DOC, JPG, PNG</div>
                    </div>
                </div>
                <div style="padding:16px">

                    <!-- Jenis Dokumen: pilih dari daftar HRD atau tambahan -->
                    <div style="margin-bottom:14px">
                        <label class="form-label">Jenis Dokumen</label>
                        <div id="doc-type-pills" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px">
                            <!-- diisi oleh buildDocTypePills() -->
                            <div style="font-size:12px;color:var(--muted)">Memuat daftar dokumen...</div>
                        </div>
                        <!-- Nama dokumen — muncul hanya saat pilih "lainnya" -->
                        <div id="doc-name-wrap" style="display:none;margin-top:8px">
                            <label class="form-label">Nama Dokumen</label>
                            <input type="text" id="doc-name" class="form-input" placeholder="e.g. Sertifikat Pelatihan 2025">
                        </div>
                        <!-- Input hidden untuk value type yang dikirim ke API -->
                        <input type="hidden" id="doc-type" value="">
                        <input type="hidden" id="doc-name-from-admin" value="">
                    </div>

                    <div class="upload-zone" id="upload-zone" onclick="document.getElementById('file-input').click()"
                        ondragover="event.preventDefault();this.classList.add('over')"
                        ondragleave="this.classList.remove('over')"
                        ondrop="handleDrop(event)">
                        <input type="file" id="file-input" style="display:none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="handleFileSelect(event)">
                        <div id="upload-zone-inner">
                            <i data-lucide="upload-cloud" style="width:28px;height:28px;color:var(--muted);margin:0 auto 10px;display:block"></i>
                            <div style="font-size:13px;font-weight:600;color:var(--text);margin-bottom:4px">Klik atau drag & drop file</div>
                            <div style="font-size:11px;color:var(--muted)">PDF · DOC · DOCX · JPG · PNG (maks 5MB)</div>
                        </div>
                    </div>
                    <div id="file-preview" style="display:none;margin-top:10px;padding:10px 12px;background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15);border-radius:10px;align-items:center;justify-content:space-between">
                        <div style="display:flex;align-items:center;gap:10px">
                            <i data-lucide="file-check" style="width:16px;height:16px;color:var(--primary)"></i>
                            <div>
                                <div id="file-name-preview" style="font-size:12px;font-weight:600;color:var(--text)">—</div>
                                <div id="file-size-preview" style="font-size:11px;color:var(--muted)">—</div>
                            </div>
                        </div>
                        <button onclick="clearFile()" class="btn-icon btn-danger">
                            <i data-lucide="x" style="width:12px;height:12px"></i>
                        </button>
                    </div>
                    <button id="upload-btn" onclick="uploadDoc()" class="btn-primary" style="margin-top:12px;width:100%;justify-content:center" disabled>
                        <i data-lucide="upload" style="width:14px;height:14px"></i>
                        Upload Dokumen
                    </button>
                </div>
            </div>
            <!-- Daftar dokumen peserta -->
            <div class="panel">
                <div class="panel-hdr">
                    <div class="panel-title">Dokumen Saya</div>
                    <button onclick="loadDocs()" class="btn-icon">
                        <i data-lucide="refresh-cw" style="width:12px;height:12px"></i>
                    </button>
                </div>
                <div id="my-doc-list">
                    <div style="padding:32px;text-align:center;color:var(--muted);font-size:12px">Memuat...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── VIEW: NOTIFIKASI ── -->
    <div id="view-notifikasi" class="view wide" style="display:none">
        <div class="panel">
            <div class="panel-hdr">
                <div>
                    <div class="panel-title">Notifikasi</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:1px" id="unread-label">—</div>
                </div>
                <button onclick="readAllNotifs()" class="btn-ghost" style="font-size:11px">
                    <i data-lucide="check-check" style="width:12px;height:12px"></i>
                    Tandai Semua Dibaca
                </button>
            </div>
            <div id="notif-list">
                <div style="padding:40px;text-align:center;color:var(--muted);font-size:12px">Memuat notifikasi...</div>
            </div>
        </div>
    </div>
</main>

<!-- MODAL Logout -->
<div id="logout-modal" class="modal-bd">
    <div class="modal-box" style="text-align:center">
        <div style="width:48px;height:48px;border-radius:12px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
            <i data-lucide="log-out" style="width:20px;height:20px;color:var(--red)"></i>
        </div>
        <h3 style="font-size:16px;font-weight:700;margin-bottom:7px">Keluar dari Portal?</h3>
        <p style="font-size:13px;color:var(--muted);margin-bottom:20px;line-height:1.5">Sesi Anda akan berakhir dan perlu login ulang.</p>
        <div style="display:flex;gap:10px">
            <button onclick="closeLogoutModal()" class="btn-ghost" style="flex:1;justify-content:center">Batal</button>
            <button onclick="doLogout()" style="flex:1;padding:9px;border-radius:9px;background:#dc2626;color:#fff;font-size:13px;font-weight:700;cursor:pointer;border:none" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">Keluar</button>
        </div>
    </div>
</div>

<!-- MODAL Delete Confirm -->
<div id="delete-modal" class="modal-bd">
    <div class="modal-box" style="text-align:center">
        <div style="width:48px;height:48px;border-radius:12px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
            <i data-lucide="trash-2" style="width:20px;height:20px;color:var(--red)"></i>
        </div>
        <h3 style="font-size:16px;font-weight:700;margin-bottom:7px">Hapus Dokumen?</h3>
        <p style="font-size:13px;color:var(--muted);margin-bottom:20px">Dokumen yang sudah diverifikasi tidak bisa dihapus.</p>
        <div style="display:flex;gap:10px">
            <button onclick="closeDeleteModal()" class="btn-ghost" style="flex:1;justify-content:center">Batal</button>
            <button onclick="confirmDelete()" style="flex:1;padding:9px;border-radius:9px;background:#dc2626;color:#fff;font-size:13px;font-weight:700;cursor:pointer;border:none">Hapus</button>
        </div>
    </div>
</div>

<footer id="main-footer" style="display:none;text-align:center;padding:16px;font-size:11px;color:rgba(100,116,139,.4);border-top:1px solid var(--border);position:relative;z-index:1">
    © 2025 PT Angkasa Pura Indonesia (InJourney Airports). All rights reserved.
</footer>

<script>
const API = 'http://127.0.0.1:8000/api';
let currentCode  = null;
let currentUser  = null;
let selectedFile = null;
let deleteDocId  = null;
let notifPollTimer = null;

// ── CLOCK ─────────────────────────────────────────────
function tickClock() {
    const el = document.getElementById('clock');
    if (el) el.textContent = new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(tickClock, 1000); tickClock();

// ── TOAST ─────────────────────────────────────────────
function showToast(title, msg, type='info') {
    const c = document.getElementById('toast-container');
    const colors = {success:'#22c55e',error:'#ef4444',info:'#3b82f6',warning:'#f59e0b'};
    const icons  = {success:'check-circle-2',error:'x-circle',info:'bell',warning:'alert-triangle'};
    const bgs    = {success:'rgba(34,197,94,.1)',error:'rgba(239,68,68,.1)',info:'rgba(59,130,246,.1)',warning:'rgba(245,158,11,.1)'};
    const t = document.createElement('div');
    t.className = 'toast';
    t.innerHTML = `
        <div style="width:28px;height:28px;border-radius:7px;background:${bgs[type]};display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i data-lucide="${icons[type]}" style="width:14px;height:14px;color:${colors[type]}"></i>
        </div>
        <div><div class="toast-t">${title}</div><div class="toast-m">${msg}</div></div>`;
    c.appendChild(t);
    lucide.createIcons();
    setTimeout(() => { t.classList.add('out'); setTimeout(() => t.remove(), 300); }, 3800);
}

// ── LOGIN ─────────────────────────────────────────────
async function doLogin() {
    const code = document.getElementById('code-input').value.trim().toUpperCase();
    const err  = document.getElementById('login-err');
    const btn  = document.getElementById('login-btn');
    if (!code) { err.textContent = 'Masukkan kode akses terlebih dahulu.'; err.style.display='block'; return; }
    err.style.display='none';
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="spin" style="width:16px;height:16px"></i> Memverifikasi...';
    lucide.createIcons();
    try {
        const res = await fetch(`${API}/check-status`, {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({code})});
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Kode tidak valid');
        currentCode = code;
        currentUser = data.data;
        enterPortal();
    } catch(e) {
        err.textContent = e.message;
        err.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="log-in" style="width:16px;height:16px"></i> Masuk ke Portal';
        lucide.createIcons();
    }
}

function enterPortal() {
    document.getElementById('nav-tabs-wrap').style.display = 'block';
    document.getElementById('logout-btn').style.display = 'flex';
    document.getElementById('main-footer').style.display = 'block';
    showTab('status');
    startNotifPoll();
}

// ── TABS ─────────────────────────────────────────────
function showTab(tab) {
    ['status','dokumen','notifikasi'].forEach(t => {
        document.getElementById(`tab-${t}`)?.classList.remove('active');
    });
    document.getElementById(`tab-${tab}`)?.classList.add('active');

    // Hide all views
    ['view-login','view-accepted','view-pending','view-rejected','view-dokumen','view-notifikasi'].forEach(v => {
        const el = document.getElementById(v);
        if (el) { el.style.display='none'; el.classList.remove('active'); }
    });

    if (tab === 'status') {
        renderStatus();
    } else if (tab === 'dokumen') {
        showView('view-dokumen');
        buildDocTypePills(); // tampilkan pilihan standar dulu
        loadDocs();
    } else if (tab === 'notifikasi') {
        showView('view-notifikasi');
        loadNotifs();
    }
}

function showView(id) {
    const el = document.getElementById(id);
    if (el) { el.style.display='block'; el.classList.add('active'); }
    lucide.createIcons();
}

function renderStatus() {
    const u = currentUser;
    if (!u) return;
    if (u.status === 'accepted') {
        document.getElementById('bp-name').textContent  = u.name;
        document.getElementById('bp-univ').textContent  = u.univ + ' · ' + u.major;
        document.getElementById('bp-code').textContent  = u.code;
        document.getElementById('bp-nim').textContent   = u.nim;
        document.getElementById('bp-major').textContent = u.major;
        document.getElementById('bp-loc').textContent   = u.location === 'kantor' ? '🏢 Head Office' : '✈ Terminal Ops';
        showView('view-accepted');
        loadAdminDocs();
    } else if (u.status === 'pending') {
        document.getElementById('pend-name').textContent = u.name;
        showView('view-pending');
    } else {
        document.getElementById('rej-name').textContent = u.name;
        showView('view-rejected');
    }
}

// ── DOKUMEN ADMIN (sudah diterima) ───────────────────
let adminDocsList = []; // simpan untuk dipakai buildDocTypePills

async function loadAdminDocs() {
    const loc = currentUser?.location;
    const el  = document.getElementById('admin-doc-list');
    if (!loc) {
        el.innerHTML = '<div style="font-size:12px;color:var(--muted)">Penempatan belum ditentukan.</div>';
        return;
    }
    el.innerHTML = '<div style="font-size:12px;color:var(--muted);padding:8px 0">Memuat dokumen...</div>';
    try {
        const res  = await fetch(`${API}/admin/documents?location=${loc}`);
        const data = await res.json();
        if (!res.ok) throw new Error('Gagal memuat dokumen HRD');

        const docs = (data.data?.[loc] || []);
        adminDocsList = docs; // simpan untuk pills
        buildDocTypePills();  // update pilihan upload

        if (!docs.length) {
            el.innerHTML = '<div style="font-size:12px;color:var(--muted);padding:8px 0">Belum ada dokumen dari HRD.</div>';
            return;
        }
        el.innerHTML = docs.map(d => `
            <div class="doc-row" style="padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.04)">
                <div style="flex:1;min-width:0">
                    <div style="font-size:12px;font-weight:500;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${d.name}</div>
                    <div style="font-size:10px;color:var(--muted);margin-top:2px">${d.file_size} · ${d.uploaded_at}</div>
                </div>
                <a href="${d.url}" target="_blank" rel="noopener" class="btn-icon" title="Download" style="flex-shrink:0">
                    <i data-lucide="download" style="width:12px;height:12px"></i>
                </a>
            </div>`).join('');
        lucide.createIcons();
    } catch(e) {
        el.innerHTML = `<div style="font-size:12px;color:var(--red);padding:8px 0">Gagal memuat: ${e.message}</div>`;
    }
}

// ── BUILD PILIHAN JENIS DOKUMEN DARI ADMIN + OPSI LAIN ──
function buildDocTypePills() {
    const wrap = document.getElementById('doc-type-pills');
    if (!wrap) return;

    // Dokumen wajib standar (selalu tampil)
    const standard = [
        { value: 'cv',              label: 'CV / Resume' },
        { value: 'transkrip',       label: 'Transkrip Nilai' },
        { value: 'ktm',             label: 'Kartu Tanda Mahasiswa' },
        { value: 'surat_pengantar', label: 'Surat Pengantar' },
    ];

    // Dokumen dari admin (nama dokumen yang diupload HRD untuk lokasi ini)
    const fromAdmin = adminDocsList.map(d => ({
        value: 'lainnya',
        label: d.name,
        adminName: d.name,
        isAdmin: true,
    }));

    // Gabungkan: standar + dari admin + opsi bebas
    const pills = [
        ...standard,
        ...fromAdmin,
        { value: 'lainnya', label: '+ Dokumen Lainnya', isExtra: true },
    ];

    wrap.innerHTML = pills.map((p, i) => `
        <button type="button"
            class="doc-pill ${p.isExtra ? 'pill-extra' : ''}"
            data-value="${p.value}"
            data-admin-name="${p.adminName || ''}"
            data-index="${i}"
            onclick="selectDocPill(this)">
            ${p.isAdmin ? '<i data-lucide="file-check" style="width:11px;height:11px"></i>' : ''}
            ${p.label}
        </button>`).join('');

    // Pilih pill pertama secara default
    const first = wrap.querySelector('.doc-pill');
    if (first) selectDocPill(first);
    lucide.createIcons();
}

function selectDocPill(btn) {
    // Hapus selected dari semua
    document.querySelectorAll('#doc-type-pills .doc-pill').forEach(p => p.classList.remove('selected'));
    btn.classList.add('selected');

    const value     = btn.dataset.value;
    const adminName = btn.dataset.adminName;
    const isExtra   = btn.classList.contains('pill-extra');

    // Set hidden inputs
    document.getElementById('doc-type').value           = value;
    document.getElementById('doc-name-from-admin').value = adminName || '';

    // Tampilkan input nama hanya jika "Dokumen Lainnya" bebas
    const nameWrap = document.getElementById('doc-name-wrap');
    if (isExtra) {
        nameWrap.style.display = 'block';
        document.getElementById('doc-name').value = '';
        document.getElementById('doc-name').focus();
    } else {
        nameWrap.style.display = 'none';
        // Isi nama otomatis dari label pill (atau nama admin)
        document.getElementById('doc-name').value = adminName || btn.textContent.trim();
    }
}

// ── UPLOAD DOKUMEN ────────────────────────────────────
function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) setFile(file);
}
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('upload-zone').classList.remove('over');
    const file = e.dataTransfer.files[0];
    if (file) setFile(file);
}
function setFile(file) {
    if (file.size > 5 * 1024 * 1024) { showToast('File Terlalu Besar', 'Maksimum ukuran file 5MB.', 'error'); return; }
    selectedFile = file;
    document.getElementById('file-name-preview').textContent = file.name;
    const kb = (file.size / 1024).toFixed(1);
    document.getElementById('file-size-preview').textContent = kb + ' KB · ' + file.type;
    document.getElementById('file-preview').style.display = 'flex';
    document.getElementById('upload-zone-inner').style.opacity = '.5';
    document.getElementById('upload-btn').disabled = false;
    lucide.createIcons();
}
function clearFile() {
    selectedFile = null;
    document.getElementById('file-input').value = '';
    document.getElementById('file-preview').style.display = 'none';
    document.getElementById('upload-zone-inner').style.opacity = '1';
    document.getElementById('upload-btn').disabled = true;
}

async function uploadDoc() {
    if (!selectedFile) return;

    const type      = document.getElementById('doc-type').value;
    const adminName = document.getElementById('doc-name-from-admin').value;
    const manualName = document.getElementById('doc-name').value.trim();
    // Nama: pakai nama admin jika ada, kalau tidak pakai input manual
    const docName   = adminName || manualName;

    if (!type) { showToast('Pilih Jenis Dokumen', 'Pilih salah satu jenis dokumen terlebih dahulu.', 'error'); return; }
    if (!docName) { showToast('Nama Kosong', 'Masukkan nama dokumen untuk Dokumen Lainnya.', 'error'); return; }

    const btn = document.getElementById('upload-btn');
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="spin" style="width:14px;height:14px"></i> Mengupload...';
    lucide.createIcons();
    try {
        const fd = new FormData();
        fd.append('file', selectedFile);
        fd.append('type', type);
        fd.append('name', docName);
        const res = await fetch(`${API}/applicant/${currentCode}/documents`, {method:'POST', body: fd});
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Upload gagal');
        showToast('Upload Berhasil! ✅', `"${data.data?.name}" berhasil diunggah.`, 'success');
        clearFile();
        loadDocs();
    } catch(e) {
        showToast('Upload Gagal', e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="upload" style="width:14px;height:14px"></i> Upload Dokumen';
        lucide.createIcons();
    }
}

async function loadDocs() {
    const el = document.getElementById('my-doc-list');
    if (!el) return;
    el.innerHTML = '<div style="padding:24px;text-align:center;color:var(--muted);font-size:12px">Memuat...</div>';
    try {
        const res  = await fetch(`${API}/applicant/${currentCode}/documents`);
        const data = await res.json();
        const docs = data.data || [];
        if (!docs.length) {
            el.innerHTML = '<div style="padding:32px;text-align:center;color:var(--muted);font-size:12px"><i data-lucide="file-plus" style="width:24px;height:24px;opacity:.4;display:block;margin:0 auto 8px"></i>Belum ada dokumen diupload</div>';
            lucide.createIcons();
            return;
        }
        const statusMap = { pending:'ds-pending', approved:'ds-approved', rejected:'ds-rejected' };
        const statusLabel = { pending:'Menunggu', approved:'Disetujui', rejected:'Ditolak' };
        const typeColors = { cv:'#3b82f6', transkrip:'#8b5cf6', ktm:'#14b8a6', surat_pengantar:'#f59e0b', lainnya:'#64748b' };
        el.innerHTML = docs.map(d => `
            <div class="doc-row">
                <div class="doc-icon" style="background:${typeColors[d.type] || '#64748b'}18;border:1px solid ${typeColors[d.type] || '#64748b'}25">
                    <i data-lucide="file-text" style="width:15px;height:15px;color:${typeColors[d.type] || '#64748b'}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div class="doc-name">${d.name} <span class="type-badge">${d.type_label}</span></div>
                    <div class="doc-meta">${d.file_name} · ${d.file_size} · ${d.uploaded_at}</div>
                    ${d.notes ? `<div style="font-size:11px;color:var(--red);margin-top:3px">Catatan: ${d.notes}</div>` : ''}
                </div>
                <span class="doc-status ${statusMap[d.status]}">${statusLabel[d.status]}</span>
                ${d.status === 'pending' ? `<button onclick="openDeleteModal(${d.id})" class="btn-icon btn-danger"><i data-lucide="trash-2" style="width:12px;height:12px"></i></button>` : ''}
            </div>`).join('');
        // Update progress step
        const hasApproved = docs.some(d => d.status === 'approved');
        const progDocs = document.getElementById('prog-docs');
        if (progDocs) progDocs.style.background = hasApproved ? 'var(--green)' : (docs.length > 0 ? 'var(--amber)' : 'rgba(255,255,255,0.08)');
        lucide.createIcons();
    } catch(e) {
        el.innerHTML = '<div style="padding:24px;text-align:center;color:var(--red);font-size:12px">Gagal memuat dokumen</div>';
    }
}

// ── DELETE DOC ─────────────────────────────────────────
function openDeleteModal(id) { deleteDocId = id; document.getElementById('delete-modal').classList.add('open'); }
function closeDeleteModal() { deleteDocId = null; document.getElementById('delete-modal').classList.remove('open'); }
async function confirmDelete() {
    if (!deleteDocId) return;
    try {
        const res = await fetch(`${API}/applicant/${currentCode}/documents/${deleteDocId}`, {method:'DELETE'});
        const data = await res.json();
        if (!res.ok) throw new Error(data.message);
        showToast('Dihapus', 'Dokumen berhasil dihapus.', 'success');
        closeDeleteModal();
        loadDocs();
    } catch(e) {
        showToast('Gagal', e.message, 'error');
        closeDeleteModal();
    }
}

// ── NOTIFIKASI ─────────────────────────────────────────
async function loadNotifs() {
    const el = document.getElementById('notif-list');
    if (!el) return;
    try {
        const res  = await fetch(`${API}/applicant/${currentCode}/notifications`);
        const data = await res.json();
        const notifs = data.data || [];
        const unread = data.unread_count || 0;
        document.getElementById('unread-label').textContent = unread > 0 ? `${unread} belum dibaca` : 'Semua sudah dibaca';
        // Update dots
        const nd = document.getElementById('notif-dot');
        if (nd) { nd.classList.toggle('show', unread > 0); }
        if (!notifs.length) {
            el.innerHTML = '<div style="padding:40px;text-align:center;color:var(--muted);font-size:12px"><i data-lucide="bell-off" style="width:24px;height:24px;opacity:.4;display:block;margin:0 auto 8px"></i>Belum ada notifikasi</div>';
            lucide.createIcons();
            return;
        }
        const typeIcon = { status_change:'activity', document:'file-check', info:'info' };
        const typeColor = { status_change:'#3b82f6', document:'#8b5cf6', info:'#14b8a6' };
        el.innerHTML = notifs.map(n => `
            <div class="notif-row ${n.is_read ? '' : 'unread'}" onclick="markRead('${n.id}', this)">
                ${!n.is_read ? '<div class="notif-dot-left"></div>' : '<div style="width:6px;height:6px;flex-shrink:0"></div>'}
                <div style="width:30px;height:30px;border-radius:8px;background:${typeColor[n.type]}18;border:1px solid ${typeColor[n.type]}25;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i data-lucide="${typeIcon[n.type] || 'bell'}" style="width:13px;height:13px;color:${typeColor[n.type]}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div class="notif-title">${n.title}</div>
                    <div class="notif-msg">${n.message.replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>')}</div>
                    <div class="notif-time">${n.time} · ${n.created_at}</div>
                </div>
            </div>`).join('');
        lucide.createIcons();
    } catch(e) {
        el.innerHTML = '<div style="padding:24px;text-align:center;color:var(--red);font-size:12px">Gagal memuat notifikasi</div>';
    }
}

async function markRead(id, row) {
    if (row.classList.contains('unread')) {
        row.classList.remove('unread');
        row.querySelector('.notif-dot-left')?.remove();
        await fetch(`${API}/applicant/${currentCode}/notifications/${id}/read`, {method:'PATCH'}).catch(()=>{});
        checkUnread();
    }
}

async function readAllNotifs() {
    await fetch(`${API}/applicant/${currentCode}/notifications/read-all`, {method:'POST'}).catch(()=>{});
    showToast('Selesai', 'Semua notifikasi telah ditandai dibaca.', 'success');
    loadNotifs();
}

async function checkUnread() {
    try {
        const res  = await fetch(`${API}/applicant/${currentCode}/notifications`);
        const data = await res.json();
        const n = data.unread_count || 0;
        const nd = document.getElementById('notif-dot');
        if (nd) nd.classList.toggle('show', n > 0);
        const ul = document.getElementById('unread-label');
        if (ul) ul.textContent = n > 0 ? `${n} belum dibaca` : 'Semua sudah dibaca';
    } catch {}
}

function startNotifPoll() {
    checkUnread();
    notifPollTimer = setInterval(checkUnread, 30000); // poll tiap 30 detik
}

// ── LOGOUT ─────────────────────────────────────────────
function openLogoutModal()  { document.getElementById('logout-modal').classList.add('open'); }
function closeLogoutModal() { document.getElementById('logout-modal').classList.remove('open'); }
function doLogout() {
    clearInterval(notifPollTimer);
    currentCode = null; currentUser = null; selectedFile = null;
    ['nav-tabs-wrap','logout-btn','main-footer'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
    document.getElementById('code-input').value = '';
    document.getElementById('login-err').style.display = 'none';
    const loginBtn = document.getElementById('login-btn');
    loginBtn.disabled = false;
    loginBtn.innerHTML = '<i data-lucide="log-in" style="width:16px;height:16px"></i> Masuk ke Portal';
    // Reset all views
    ['view-accepted','view-pending','view-rejected','view-dokumen','view-notifikasi'].forEach(v => {
        const el = document.getElementById(v);
        if (el) { el.style.display='none'; el.classList.remove('active'); }
    });
    showView('view-login');
    closeLogoutModal();
    lucide.createIcons();
}

// ── INIT ───────────────────────────────────────────────
lucide.createIcons();
</script>
</body>
</html>