// ─────────────────────────────────────────
//  InJourney Airports — Internship Portal
//  Client-side logic (script.js)
// ─────────────────────────────────────────

const API_CHECK_STATUS_URL = 'http://127.0.0.1:8000/api/check-status';
const LOGO_URL = '/img/logo.png';

let currentUser = null;

// ── DOCUMENT REQUIREMENTS ──────────────────
const DOCUMENTS_REQ = {
    kantor: [
        { label: 'Surat Keputusan (SK)', type: 'PDF' },
        { label: 'Pakta Integritas', type: 'PDF' },
    ],
    terminal: [
        { label: 'Surat Keputusan (SK)', type: 'PDF' },
        { label: 'Pakta Integritas', type: 'PDF' },
        { label: 'Scan SKCK Asli', type: 'PDF' },
        { label: 'Formulir PAS Bandara', type: 'PDF' },
        { label: 'CV Standar Bandara', type: 'DOCX' },
    ],
};

// ── MODAL HELPERS ───────────────────────────
function openConfirmModal() { document.getElementById('confirm-modal').classList.add('open'); }

function closeConfirmModal() { document.getElementById('confirm-modal').classList.remove('open'); }

function doLogout() {
    closeConfirmModal();
    currentUser = null;
    renderView('login');
}

// ── TOAST ───────────────────────────────────
function showNotification(title, message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const colors = {
        success: { accent: '#4ade80', icon: `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>` },
        error: { accent: '#f87171', icon: `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>` },
        info: { accent: '#00b9e8', icon: `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#00b9e8" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>` },
    };
    const c = colors[type] || colors.info;
    toast.className = 'toast';
    toast.style.borderLeft = `3px solid ${c.accent}`;
    toast.innerHTML = `
        <div style="margin-top:1px;flex-shrink:0">${c.icon}</div>
        <div style="flex:1">
            <div class="toast-title">${title}</div>
            <div class="toast-msg">${message}</div>
        </div>
        <button onclick="this.closest('.toast').remove()" style="color:rgba(100,116,139,0.5);cursor:pointer;flex-shrink:0" onmouseover="this.style.color='#94a3b8'" onmouseout="this.style.color='rgba(100,116,139,0.5)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('out');
        setTimeout(() => toast.remove(), 350);
    }, 4500);
}

// ── DOCUMENT LIST ────────────────────────────
// ── BUILD DOC LIST ─────────────────────────────────────────────────────
// Reads live from window.PORTAL_DOCS (synced by admin dashboard).
// Falls back to DOCUMENTS_REQ (hardcoded) if admin hasn't uploaded yet.
function buildDocList(location) {
    // Prefer live data set by admin document manager
    const liveDocs = (window.PORTAL_DOCS && window.PORTAL_DOCS[location]) || null;
    const docs = liveDocs && liveDocs.length ? liveDocs : (DOCUMENTS_REQ[location] || []);

    if (!docs.length) {
        return `<div style="padding:20px;text-align:center;font-size:12px;color:rgba(100,116,139,0.5)">
            Belum ada dokumen tersedia. Hubungi HRD untuk informasi lebih lanjut.
        </div>`;
    }

    const typeIcon = {
        PDF: { bg: 'rgba(239,68,68,0.12)', color: '#f87171', icon: '📄' },
        DOCX: { bg: 'rgba(59,130,246,0.12)', color: '#60a5fa', icon: '📝' },
        XLSX: { bg: 'rgba(34,197,94,0.12)', color: '#4ade80', icon: '📊' },
        IMG: { bg: 'rgba(168,85,247,0.12)', color: '#c084fc', icon: '🖼️' },
        ZIP: { bg: 'rgba(245,158,11,0.12)', color: '#fbbf24', icon: '🗜️' },
    };

    return docs.map(doc => {
        const t = typeIcon[doc.type] || typeIcon.PDF;
        const name = doc.label || doc.name || 'Dokumen';
        return `
        <div class="doc-item" onclick="handleDocDownload(${doc.id || 0}, '${name}', '${doc.type}')">
            <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0">
                <div style="width:32px;height:32px;border-radius:8px;background:${t.bg};display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0">${t.icon}</div>
                <div style="min-width:0">
                    <div class="doc-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${name}</div>
                    <div style="font-size:10px;color:rgba(100,116,139,0.5);margin-top:2px">${doc.type} · Klik untuk unduh</div>
                </div>
            </div>
            <div class="btn-dl">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </div>
        </div>`;
    }).join('');
}

// ── HANDLE DOC DOWNLOAD FROM PORTAL ────────────────────────────────────
function handleDocDownload(docId) {

    const allDocs = [
        ...(window.PORTAL_DOCS ? .kantor || []),
        ...(window.PORTAL_DOCS ? .terminal || [])
    ];

    const doc = allDocs.find(d => d.id === docId);

    if (!doc || !doc.url) {
        showNotification(
            'File Tidak Ditemukan',
            'Dokumen belum tersedia',
            'error'
        );
        return;
    }

    window.open(
        'http://127.0.0.1:8000' + doc.url,
        '_blank'
    );
}
// Fallback: show info
showNotification('Mengunduh', name + ' (' + type + ')', 'info');
}

// ── VIEW TEMPLATES ───────────────────────────

/* LOGIN */
const loginView = `
    <div class="view active">
        <div class="login-card">
            <!-- Brand -->
            <div style="text-align:center;margin-bottom:28px">
                <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:rgba(0,185,232,0.1);border:1px solid rgba(0,185,232,0.2);margin-bottom:14px">
                    <img src="${LOGO_URL}" style="height:26px;filter:brightness(0) invert(1)" onerror="this.style.display='none'">
                </div>
                <h1 style="font-size:20px;font-weight:700;color:#e2e8f0;margin-bottom:4px">Portal Magang</h1>
                <p style="font-size:13px;color:rgba(100,116,139,0.7)">Masukkan kode registrasi untuk check-in</p>
            </div>

            <!-- Form -->
            <form onsubmit="handleLogin(event)">
                <div class="input-group">
                    <label class="input-label">Kode Pendaftaran</label>
                    <div class="input-wrap">
                        <svg class="inp-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><path d="M9 9h6v6H9z"/><path d="M9 1v2M15 1v2M9 21v2M15 21v2M1 9h2M1 15h2M21 9h2M21 15h2"/></svg>
                        <input type="text" id="login-code" required placeholder="Contoh: MAG-2025-XXX" autocomplete="off">
                    </div>
                </div>

                <button type="submit" class="btn-checkin" id="btn-checkin">
                    <span id="checkin-text">CHECK STATUS SAYA</span>
                    <svg id="checkin-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </form>

            <!-- Footer note -->
            <div style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:20px">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="rgba(100,116,139,0.4)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span style="font-size:11px;color:rgba(100,116,139,0.4)">Official Recruitment Portal · InJourney Airports</span>
            </div>
        </div>
    </div>
`;

/* ACCEPTED — BOARDING PASS */
function getAcceptedTemplate(user) {
    const loc = user.location || 'kantor';
    const locLabel = loc === 'terminal' ? 'Terminal Ops' : 'Head Office';
    const locCode = loc === 'terminal' ? 'TRM' : 'HQ';

    return `
    <div class="view active" style="max-width:640px">
        <div class="boarding-pass">

            <!-- Header section -->
            <div class="bp-header">
                <div class="bp-watermark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="180" height="180" viewBox="0 0 24 24" fill="currentColor" style="color:#fff"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
                </div>

                <div style="position:relative;z-index:1">
                    <div class="bp-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Boarding Pass · Accepted
                    </div>
                    <div class="bp-title">MAGANG DITERIMA</div>
                    <div class="bp-subtitle">InJourney Airports Internship Program 2025</div>

                    <div class="bp-meta">
                        <div class="bp-field">
                            <label>Passenger Name</label>
                            <div class="val">${user.name.toUpperCase()}</div>
                        </div>
                        <div class="bp-field">
                            <label>Registration Code</label>
                            <div class="val code">${user.code}</div>
                        </div>
                        <div class="bp-field">
                            <label>Placement</label>
                            <div style="display:flex;align-items:center;gap:8px">
                                <span class="val loc">${locCode}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(100,116,139,0.5)" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                <span style="font-size:13px;color:rgba(148,163,184,0.7);font-weight:500">${locLabel}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="bp-body">
                <!-- Notices -->
                <div>
                    <div class="bp-section-title">
                        <i><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></i>
                        Important Notice
                    </div>
                    <div>
                        <div class="notice-item"><div class="notice-dot"></div><span>Unduh dan cetak semua dokumen yang diperlukan.</span></div>
                        <div class="notice-item"><div class="notice-dot"></div><span>Bawa dokumen fisik saat hari pertama lapor diri.</span></div>
                        <div class="notice-item"><div class="notice-dot"></div><span>Jam operasional: <strong style="color:#e2e8f0">08:00 – 17:00 WIB</strong></span></div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;padding:12px;border-radius:10px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);margin-top:12px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="rgba(0,185,232,0.4)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><path d="M9 9h6v6H9z"/></svg>
                        <span style="font-size:11px;color:rgba(100,116,139,0.6);line-height:1.5">Scan QR ini di lobby kedatangan untuk akses masuk gedung.</span>
                    </div>
                </div>

                <!-- Documents -->
                <div>
                    <div class="bp-section-title">
                        <i><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></i>
                        Flight Documents
                    </div>
                    ${buildDocList(loc)}
                    <button class="btn-print" onclick="showNotification('Dokumen Siap', 'Semua berkas berhasil diunduh.', 'success')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Print All Documents
                    </button>
                </div>
            </div>
        </div>
    </div>`;
}

/* PENDING */
function getPendingTemplate(user) {
    return `
    <div class="view active">
        <div class="status-card">
            <div class="status-icon pulse-anim" style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2)">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="status-title">Status: Dalam Antrian</div>
            <div class="status-desc">Berkas Anda sedang dalam antrian verifikasi oleh tim HRD InJourney.</div>
            <div class="eta-box">ESTIMASI VERIFIKASI: 1–2 HARI KERJA</div>
            <button class="btn-back" onclick="doLogout()">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali ke Login
            </button>
        </div>
    </div>`;
}

/* REJECTED */
function getRejectedTemplate(user) {
    return `
    <div class="view active">
        <div class="status-card">
            <div class="status-icon" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2)">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div class="status-title">Belum Berhasil</div>
            <div class="status-desc">Mohon maaf, Anda belum lolos seleksi tahap ini. Tetap semangat dan coba lagi di periode berikutnya.</div>
            <button class="btn-back" onclick="doLogout()">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali ke Login
            </button>
        </div>
    </div>`;
}

// ── RENDER VIEW ───────────────────────────────
function renderView(viewName) {
    const container = document.getElementById('app-container');
    const header = document.getElementById('main-header');
    const footer = document.getElementById('main-footer');

    if (viewName === 'login') {
        header.classList.add('hidden');
        footer.classList.add('hidden');
        container.innerHTML = loginView;
    } else {
        header.classList.remove('hidden');
        footer.classList.remove('hidden');

        if (currentUser.status === 'accepted') {
            container.innerHTML = getAcceptedTemplate(currentUser);
        } else if (currentUser.status === 'rejected') {
            container.innerHTML = getRejectedTemplate(currentUser);
        } else {
            container.innerHTML = getPendingTemplate(currentUser);
        }
    }
}

// ── LOGIN HANDLER ────────────────────────────
async function handleLogin(event) {
    event.preventDefault();
    const code = document.getElementById('login-code').value.toUpperCase().trim();
    const btn = document.getElementById('btn-checkin');
    const text = document.getElementById('checkin-text');
    const icon = document.getElementById('checkin-icon');

    // Loading state
    btn.disabled = true;
    btn.style.opacity = '0.8';
    text.textContent = 'Memeriksa...';
    icon.outerHTML = `<svg id="checkin-icon" class="spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>`;

    try {
        const response = await fetch(API_CHECK_STATUS_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ code }),
        });

        const result = await response.json();

        if (response.ok) {
            currentUser = result.data;
            renderView('dashboard');
        } else {
            showNotification('Kode Tidak Ditemukan', 'Pastikan kode registrasi sudah benar.', 'error');
            resetLoginBtn();
        }
    } catch (err) {
        showNotification('Koneksi Gagal', 'Tidak dapat menghubungi server.', 'error');
        console.error(err);
        resetLoginBtn();
    }
}

function resetLoginBtn() {
    const btn = document.getElementById('btn-checkin');
    if (!btn) return;
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.innerHTML = `<span id="checkin-text">CHECK STATUS SAYA</span>
    <svg id="checkin-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>`;
}

// ── INIT ────────────────────────────────────
document.addEventListener('DOMContentLoaded', async() => {

    await loadPortalDocuments();

    renderView('login');

});

async function loadPortalDocuments() {
    try {

        const response = await fetch(
            'http://127.0.0.1:8000/api/admin/documents'
        );

        const result = await response.json();

        if (result.success) {

            window.PORTAL_DOCS = {
                kantor: result.data.kantor || [],
                terminal: result.data.terminal || []
            };

            if (currentUser) {
                renderView('dashboard');
            }
        }

    } catch (error) {
        console.error('Gagal mengambil dokumen:', error);
    }
};