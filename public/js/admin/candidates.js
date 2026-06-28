// ══════════════════════════════════════════════
//  CORE: config, clock, navigasi view, modal generik,
//  data kandidat (CRUD), tabel, toast, animasi angka
// ══════════════════════════════════════════════

function getApiBase() {
    if (window.APP_CONFIG && window.APP_CONFIG.apiBaseUrl) {
        return window.APP_CONFIG.apiBaseUrl;
    }
    return '/api';
}

function getApiCrudUrl() { return getApiBase() + '/applicants'; }

// Alias global agar file JS lain yang masih pakai API_BASE_URL tidak error
Object.defineProperty(window, 'API_BASE_URL', {
    get: function() { return getApiBase(); },
    configurable: true,
});

let applicants = [];
let selectedIds = new Set();

// ── CLOCK ──
function updateClock() {
    const now = new Date();
    const el = document.getElementById('dash-clock');
    if (el) el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}
setInterval(updateClock, 1000);
updateClock();

// ── NAVIGASI ──
function switchView(view) {
    const views = ['dashboard', 'candidates', 'documents', 'report'];

    views.forEach(v => {
        const page = document.getElementById(`view-${v}`);
        if (page) page.style.display = 'none';

        const nav = document.getElementById(`nav-${v}`);
        if (nav) {
            nav.classList.remove('active');
        }
    });

    const activePage = document.getElementById(`view-${view}`);
    if (activePage) activePage.style.display = 'flex';

    const activeNav = document.getElementById(`nav-${view}`);
    if (activeNav) {
        activeNav.classList.add('active');
    }

    const titles = {
        dashboard: ['Dashboard Analitik', 'Rekrutmen PKL Batch 2025'],
        candidates: ['Manajemen Kandidat', 'Verifikasi & Seleksi Pelamar'],
        documents: ['Dokumen Peserta', 'Pemberkasan Magang PKL'],
        report: ['Laporan', 'Analitik & Rekapitulasi']
    };

    if (titles[view]) {
        const titleEl = document.getElementById('page-title');
        const subEl = document.getElementById('page-sub');
        if (titleEl) titleEl.textContent = titles[view][0];
        if (subEl) subEl.textContent = titles[view][1];
    }

    if (view === 'report' && typeof loadReport === 'function') loadReport();
    if (view === 'documents' && typeof loadDocuments === 'function') loadDocuments();
    if (view === 'candidates') loadData();
}

// ── MODALS ──
function openLogoutModal() {
    const el = document.getElementById('logout-modal');
    if (el) el.classList.add('open');
}

function closeLogoutModal() {
    const el = document.getElementById('logout-modal');
    if (el) el.classList.remove('open');
}

function handleLogout() {
    window.location.href = (window.APP_CONFIG && window.APP_CONFIG.loginUrl) || '/login';
}

function openRegModal() {
    const form = document.getElementById('reg-form');
    if (form) form.reset();
    const el = document.getElementById('reg-modal');
    if (el) el.classList.add('open');
}

function closeRegModal() {
    const el = document.getElementById('reg-modal');
    if (el) el.classList.remove('open');
}

function closeSuccessModal() {
    const el = document.getElementById('success-modal');
    if (el) el.classList.remove('open');
}

function copyCode() {
    const codeEl = document.getElementById('generated-code');
    if (codeEl) {
        navigator.clipboard.writeText(codeEl.innerText);
        showToast('Disalin!', 'Kode akses berhasil disalin.', 'success');
    }
}

// ── REVIEW MODAL (STATUS & PENEMPATAN) ──
function openReviewModal(id) {
    const app = applicants.find(a => a.id === id);
    if (!app) return;

    const setVal = (elId, val) => { const el = document.getElementById(elId); if (el) el.value = val ? val : ''; };
    const setText = (elId, val) => { const el = document.getElementById(elId); if (el) el.innerText = val ? val : ''; };

    setVal('edit-id', app.id);
    setText('edit-subtitle', app.name);
    setVal('edit-status', app.status);

    document.querySelectorAll('input[name="edit-placement"]').forEach(r => r.checked = false);
    selectEditLocation(app.location || null);

    setVal('edit-start', app.internship_start ? app.internship_start.substring(0, 10) : '');
    setVal('edit-end', app.internship_end ? app.internship_end.substring(0, 10) : '');

    updateDurationInfo();
    toggleLoc();

    const el = document.getElementById('review-modal');
    if (el) el.classList.add('open');
}

function closeReviewModal() {
    const el = document.getElementById('review-modal');
    if (el) el.classList.remove('open');
}

function toggleLoc() {
    const statusEl = document.getElementById('edit-status');
    const isAccepted = statusEl ? statusEl.value === 'accepted' : false;
    const locBox = document.getElementById('edit-loc-box');
    if (locBox) {
        if (isAccepted) {
            locBox.style.display = 'flex';
        } else {
            locBox.style.display = 'none';
        }
    }
}

function selectEditLocation(val) {
    const kantor = document.getElementById('edit-loc-kantor-card');
    const terminal = document.getElementById('edit-loc-terminal-card');
    const radioKantor = document.getElementById('edit-placement-kantor');
    const radioTerminal = document.getElementById('edit-placement-terminal');
    if (!kantor || !terminal) return;

    kantor.style.borderColor = (val === 'kantor') ? '#0284c7' : '#e2e8f0';
    terminal.style.borderColor = (val === 'terminal') ? '#0d9488' : '#e2e8f0';
    kantor.style.background = (val === 'kantor') ? '#e0f2fe' : '';
    terminal.style.background = (val === 'terminal') ? '#ccfbf1' : '';

    if (radioKantor) radioKantor.checked = (val === 'kantor');
    if (radioTerminal) radioTerminal.checked = (val === 'terminal');
}

function setEditDuration(months) {
    const startEl = document.getElementById('edit-start');
    if (!startEl) return;
    if (!startEl.value) {
        const today = new Date();
        startEl.value = today.toISOString().split('T')[0];
    }
    const start = new Date(startEl.value);
    start.setMonth(start.getMonth() + months);
    const endEl = document.getElementById('edit-end');
    if (endEl) endEl.value = start.toISOString().split('T')[0];
    updateDurationInfo();
}

function updateDurationInfo() {
    const startEl = document.getElementById('edit-start');
    const endEl = document.getElementById('edit-end');
    const startVal = startEl ? startEl.value : '';
    const endVal = endEl ? endEl.value : '';
    const badge = document.getElementById('edit-duration-badge');
    const info = document.getElementById('edit-duration-info');

    if (!info) return;
    if (!startVal || !endVal) {
        info.textContent = '';
        if (badge) badge.style.display = 'none';
        return;
    }

    const start = new Date(startVal);
    const end = new Date(endVal);

    if (end <= start) {
        info.textContent = 'Tanggal selesai harus setelah tanggal mulai.';
        if (badge) {
            badge.style.display = 'flex';
            badge.style.background = '#ffe4e6';
            badge.style.color = '#e11d48';
            badge.style.borderColor = '#fecdd3';
        }
        return;
    }
    const months = Math.round((end - start) / (1000 * 60 * 60 * 24 * 30.44));
    info.textContent = `Durasi: ± ${months || 1} bulan`;
    if (badge) {
        badge.style.display = 'flex';
        badge.style.background = '#d1fae5';
        badge.style.color = '#059669';
        badge.style.borderColor = '#a7f3d0';
    }
}

// ── LOAD DATA ──
async function loadData() {
    selectedIds.clear();
    try {
        const res = await fetch(getApiCrudUrl());
        if (res.ok) {
            applicants = await res.json();
            renderTable();
        } else {
            throw new Error('API Error');
        }
    } catch {
        applicants = [];
        renderTable();
    }
}

// ── INITIALIZATION ──
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    loadData();
    if (typeof loadDocuments === 'function') loadDocuments();
    switchView('dashboard');
});

// ── REGISTRATION ──
async function handleRegistration(e) {
    e.preventDefault();
    const btn = document.getElementById('reg-btn');
    const orig = btn.innerHTML;
    btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Menyimpan...';
    btn.disabled = true;
    lucide.createIcons();

    const elName = document.getElementById('reg-name');
    const elNim = document.getElementById('reg-nim');
    const elUniv = document.getElementById('reg-univ');
    const elMajor = document.getElementById('reg-major');
    const elEmail = document.getElementById('reg-email');
    const elPhone = document.getElementById('reg-phone');

    const name = elName ? elName.value.trim() : '';
    const nim = elNim ? elNim.value.trim() : '';
    const univ = elUniv ? elUniv.value.trim() : '';
    const major = elMajor ? elMajor.value.trim() : '';

    if (!name || !nim || !univ || !major) {
        showToast('Validasi', 'Mohon lengkapi semua field wajib.', 'error');
        btn.innerHTML = orig;
        btn.disabled = false;
        lucide.createIcons();
        return;
    }

    const payload = {
        name: name,
        nim: nim,
        email: elEmail ? (elEmail.value.trim() || null) : null,
        phone: elPhone ? (elPhone.value.trim() || null) : null,
        univ: univ,
        major: major,
    };

    try {
        const res = await fetch(getApiCrudUrl(), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();

        if (res.ok) {
            closeRegModal();
            document.getElementById('generated-code').innerText = result.code;
            const successEl = document.getElementById('success-modal');
            if (successEl) successEl.classList.add('open');

            showToast('Registrasi Berhasil', 'Data peserta ditambahkan.', 'success');
            loadData();
        } else {
            showToast('Gagal', 'Terjadi kesalahan saat menyimpan.', 'error');
        }
    } catch {
        showToast('Koneksi Error', 'Tidak dapat menghubungi server.', 'error');
    } finally {
        btn.innerHTML = orig;
        btn.disabled = false;
        lucide.createIcons();
    }
}

// ── RENDER TABLE (TAILWIND UI) ──
function renderTable() {
    const tbody = document.getElementById('table-body');
    if (!tbody) return;

    const filterEl = document.getElementById('filterStatus');
    const filter = filterEl ? filterEl.value : 'all';

    const searchEl = document.getElementById('searchInput');
    const search = searchEl ? searchEl.value.toLowerCase() : '';

    tbody.innerHTML = '';

    let stats = { total: applicants.length, pending: 0, kantor: 0, terminal: 0 };

    const filtered = applicants.filter(a => {
        if (a.status === 'pending') stats.pending++;
        if (a.status === 'accepted' && (a.location === 'kantor' || a.location === 'Kantor Pusat')) stats.kantor++;
        if (a.status === 'accepted' && (a.location === 'terminal' || a.location === 'Terminal Ops')) stats.terminal++;
        return (filter === 'all' || a.status === filter) && (a.name.toLowerCase().includes(search) || (a.nim || '').toLowerCase().includes(search));
    });

    animateValue('stat-total', 0, stats.total, 500);
    animateValue('stat-pending', 0, stats.pending, 500);
    animateValue('stat-kantor', 0, stats.kantor, 500);
    animateValue('stat-terminal', 0, stats.terminal, 500);

    // Sync mini-stat strip di halaman Kandidat
    const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    setEl('cand-stat-total', stats.total);
    setEl('cand-stat-pending', stats.pending);
    setEl('cand-stat-accepted', stats.kantor + stats.terminal);
    setEl('cand-stat-rejected', applicants.filter(a => a.status === 'rejected').length);

    const emptyState = document.getElementById('empty-state');

    if (filtered.length === 0) {
        if (emptyState) emptyState.style.display = 'flex';
    } else {
        if (emptyState) emptyState.style.display = 'none';

        filtered.sort((a, b) => b.id - a.id).forEach((a, i) => {
                    // Inisial nama
                    const initials = a.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

                    // Badge Status
                    let badge = '';
                    if (a.status === 'pending')
                        badge = `<span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#FFFBEB;color:#D97706;border:1px solid #FDE68A;white-space:nowrap;">
                    <i data-lucide="clock" style="width:12px;height:12px;"></i> Menunggu
                </span>`;
                    else if (a.status === 'accepted')
                        badge = `<span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#F0FDF4;color:#16A34A;border:1px solid #BBF7D0;white-space:nowrap;">
                    <i data-lucide="check-circle-2" style="width:12px;height:12px;"></i> Diterima
                </span>`;
                    else
                        badge = `<span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#FEF2F2;color:#DC2626;border:1px solid #FECACA;white-space:nowrap;">
                    <i data-lucide="x-circle" style="width:12px;height:12px;"></i> Ditolak
                </span>`;

                    // Penempatan — kosong jika belum ada, tanpa tanda "—"
                    let locHTML = '';
                    const isKantor = a.location === 'kantor' || a.location === 'Kantor Pusat';
                    const isTerminal = a.location === 'terminal' || a.location === 'Terminal Ops';
                    if (isKantor) {
                        locHTML = `<span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;background:#F5F3FF;color:#7C3AED;border:1px solid rgba(124,58,237,.15);">
                    <i data-lucide="building-2" style="width:11px;height:11px;"></i> Head Office
                </span>`;
                        if (a.internship_start && a.internship_end) {
                            const s = new Date(a.internship_start).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                            const e = new Date(a.internship_end).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                            locHTML += `<div style="font-size:10px;color:#94A3B8;margin-top:4px;">📅 ${s} – ${e}</div>`;
                        }
                    } else if (isTerminal) {
                        locHTML = `<span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;background:#F0FDFA;color:#0D9488;border:1px solid rgba(13,148,136,.15);">
                    <i data-lucide="plane" style="width:11px;height:11px;"></i> Terminal Ops
                </span>`;
                        if (a.internship_start && a.internship_end) {
                            const s = new Date(a.internship_start).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                            const e = new Date(a.internship_end).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                            locHTML += `<div style="font-size:10px;color:#94A3B8;margin-top:4px;">📅 ${s} – ${e}</div>`;
                        }
                    }
                    // Jika locHTML masih '', kolom dibiarkan kosong (tidak ada "—")

                    const pendingDocsCount = typeof countPendingDocs === 'function' ? countPendingDocs(a) : 0;
                    const isChecked = selectedIds.has(a.id);

                    // Tombol aksi — selalu terlihat, dengan label tooltip dan warna jelas
                    const btnDocReview = `
                <button onclick="openDocReviewModal(${a.id})"
                    style="position:relative;display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;transition:all .15s;background:#EFF6FF;color:#2563EB;border:1px solid #BFDBFE;"
                    onmouseover="this.style.background='#DBEAFE'" onmouseout="this.style.background='#EFF6FF'"
                    title="Review Dokumen">
                    <i data-lucide="file-check-2" style="width:13px;height:13px;flex-shrink:0;"></i>
                    Dokumen
                    ${pendingDocsCount > 0 ? `<span style="position:absolute;top:-5px;right:-5px;width:16px;height:16px;border-radius:50%;background:#F59E0B;color:#fff;font-size:9px;font-weight:800;display:flex;align-items:center;justify-content:center;">${pendingDocsCount}</span>` : ''}
                </button>`;

            const btnAccept = a.status === 'pending' ? `
                <button onclick="openPlacementModal(${a.id}, '${a.name.replace(/'/g, "\\'")}')"
                    style="display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;transition:all .15s;background:#F0FDF4;color:#16A34A;border:1px solid #BBF7D0;"
                    onmouseover="this.style.background='#DCFCE7'" onmouseout="this.style.background='#F0FDF4'"
                    title="Terima & Tentukan Penempatan">
                    <i data-lucide="user-check" style="width:13px;height:13px;flex-shrink:0;"></i>
                    Terima
                </button>` : '';

            const btnLetter = a.status === 'accepted' ? `
                <button onclick="triggerReplyLetterUpload(${a.id})"
                    style="display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;transition:all .15s;background:${a.reply_letter_path ? '#F0FDF4' : '#FFFBEB'};color:${a.reply_letter_path ? '#16A34A' : '#D97706'};border:1px solid ${a.reply_letter_path ? '#BBF7D0' : '#FDE68A'};"
                    onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'"
                    title="${a.reply_letter_path ? 'Ganti surat balasan' : 'Upload surat balasan'}">
                    <i data-lucide="${a.reply_letter_path ? 'file-check-2' : 'file-warning'}" style="width:13px;height:13px;flex-shrink:0;"></i>
                    Surat
                </button>` : '';

            const btnEdit = `
                <button onclick="openReviewModal(${a.id})"
                    style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;cursor:pointer;transition:all .15s;background:#F8FAFC;color:#64748B;border:1px solid #E2E8F0;"
                    onmouseover="this.style.background='#FEF3C7';this.style.color='#D97706';this.style.borderColor='#FDE68A'" onmouseout="this.style.background='#F8FAFC';this.style.color='#64748B';this.style.borderColor='#E2E8F0'"
                    title="Edit Status">
                    <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                </button>`;

            const btnDelete = `
                <button onclick="deleteApp(${a.id})"
                    style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;cursor:pointer;transition:all .15s;background:#F8FAFC;color:#64748B;border:1px solid #E2E8F0;"
                    onmouseover="this.style.background='#FEF2F2';this.style.color='#DC2626';this.style.borderColor='#FECACA'" onmouseout="this.style.background='#F8FAFC';this.style.color='#64748B';this.style.borderColor='#E2E8F0'"
                    title="Hapus">
                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                </button>`;

            tbody.innerHTML += `
                <tr style="border-bottom:1px solid #F1F5F9;transition:background .12s;${isChecked ? 'background:#EFF6FF;' : ''}"
                    onmouseover="if(!this.dataset.checked)this.style.background='#F8FAFC'"
                    onmouseout="if(!this.dataset.checked)this.style.background=''"
                    ${isChecked ? 'data-checked="1"' : ''}>
                    <td style="width:44px;text-align:center;padding:12px 8px 12px 16px;">
                        <input type="checkbox" class="row-check" data-id="${a.id}" onchange="toggleRowCheck(this)" ${isChecked ? 'checked' : ''}
                            style="width:14px;height:14px;cursor:pointer;accent-color:#2563EB;">
                    </td>
                    <td style="padding:12px 14px;min-width:200px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:10px;background:#F1F5F9;border:1px solid #E2E8F0;color:#475569;font-weight:700;font-size:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                ${initials}
                            </div>
                            <div style="min-width:0;">
                                <div style="font-weight:700;color:#0F172A;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;" title="${a.name}">${a.name}</div>
                                ${a.nim ? `<div style="font-size:11px;color:#94A3B8;font-family:'JetBrains Mono',monospace;margin-top:2px;">${a.nim}</div>` : ''}
                            </div>
                        </div>
                    </td>
                    <td style="padding:12px 14px;white-space:nowrap;">
                        <span style="font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:600;color:#2563EB;background:#EFF6FF;border:1px solid #BFDBFE;padding:3px 8px;border-radius:5px;letter-spacing:.03em;">${a.code || ''}</span>
                    </td>
                    <td style="padding:12px 14px;min-width:160px;">
                        <div style="font-weight:600;color:#0F172A;font-size:12.5px;">${a.univ || ''}</div>
                        <div style="font-size:11px;color:#64748B;margin-top:2px;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${a.major || ''}">${a.major || ''}</div>
                    </td>
                    <td style="padding:12px 14px;white-space:nowrap;">${badge}</td>
                    <td style="padding:12px 14px;min-width:140px;">${locHTML}</td>
                    <td style="padding:12px 14px;text-align:right;">
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:5px;flex-wrap:nowrap;">
                            ${btnDocReview}
                            ${btnAccept}
                            ${btnLetter}
                            ${btnEdit}
                            ${btnDelete}
                        </div>
                    </td>
                </tr>`;
        });
        lucide.createIcons();
    }
}

// ── SAVE EDIT ──
async function saveEdit() {
    const id = document.getElementById('edit-id').value;
    const status = document.getElementById('edit-status').value;
    let location = null, internship_start = null, internship_end = null;

    if (status === 'accepted') {
        const checked = document.querySelector('input[name="edit-placement"]:checked');
        if (!checked) { showToast('Validasi', 'Pilih lokasi penempatan.', 'error'); return; }
        location = checked.value;

        const startEl = document.getElementById('edit-start');
        const endEl = document.getElementById('edit-end');
        if (!startEl || !endEl) {
            showToast('Error Tampilan', 'Field tanggal masa magang tidak ditemukan.', 'error');
            return;
        }
        internship_start = startEl.value;
        internship_end   = endEl.value;
        if (!internship_start || !internship_end) { showToast('Validasi', 'Isi masa magang (tanggal mulai & selesai).', 'error'); return; }
        if (new Date(internship_end) <= new Date(internship_start)) { showToast('Validasi', 'Tanggal selesai harus setelah tanggal mulai.', 'error'); return; }
    }

    try {
        const res = await fetch(`${getApiCrudUrl()}/${id}`, { 
            method: 'PUT', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ status, location, internship_start, internship_end }) 
        });
        if (res.ok) { 
            showToast('Berhasil', 'Status diperbarui.', 'success'); 
            closeReviewModal(); 
            loadData(); 
        } else {
            showToast('Gagal', 'Gagal memperbarui data.', 'error');
        }
    } catch {
        const idx = applicants.findIndex(a => a.id == id);
        if (idx > -1) {
            applicants[idx].status = status;
            applicants[idx].location = location;
            applicants[idx].internship_start = internship_start;
            applicants[idx].internship_end = internship_end;
            renderTable(); 
            closeReviewModal(); 
            showToast('Sukses (Demo)', 'Status diperbarui (simulasi).', 'success');
        } else {
            showToast('Error', 'Kesalahan jaringan.', 'error');
        }
    }
}

// ── DELETE ──
async function deleteApp(id) {
    if (!confirm('Hapus data ini secara permanen?')) return;
    try {
        const res = await fetch(`${getApiCrudUrl()}/${id}`, { method: 'DELETE' });
        if (res.ok) { 
            showToast('Terhapus', 'Data peserta dihapus.', 'success'); 
            loadData(); 
        }
    } catch {
        applicants = applicants.filter(a => a.id !== id);
        renderTable();
        showToast('Terhapus (Demo)', 'Data dihapus (simulasi).', 'success');
    }
}

// ── UPLOAD SURAT BALASAN ──
function triggerReplyLetterUpload(id) {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.pdf,.jpg,.jpeg,.png';
    input.style.display = 'none';
    document.body.appendChild(input);

    input.onchange = async () => {
        const file = input.files[0];
        document.body.removeChild(input);
        if (!file) return;

        showToast('Mengupload...', 'Mengirim surat balasan ke server.', 'info');
        const fd = new FormData();
        fd.append('reply_letter', file);

        try {
            const res = await fetch(`${getApiCrudUrl()}/${id}/reply-letter`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: fd,
            });
            const result = await res.json().catch(() => ({}));
            if (res.ok) {
                showToast('Berhasil', 'Surat balasan berhasil diupload.', 'success');
                loadData();
            } else {
                showToast('Gagal', result.message || `Upload gagal (HTTP ${res.status}).`, 'error');
            }
        } catch (e) {
            showToast('Error', 'Tidak dapat menghubungi server.', 'error');
        }
    };

    input.click();
}

// ── CHECKBOX SINGLE ROW ──
function toggleRowCheck(checkbox) {
    const id = parseInt(checkbox.dataset.id);
    if (checkbox.checked) {
        selectedIds.add(id);
    } else {
        selectedIds.delete(id);
    }
    updateBulkUI();
    const row = checkbox.closest('tr');
    if(row) {
        if(checkbox.checked) row.classList.add('bg-sky-50/50');
        else row.classList.remove('bg-sky-50/50');
    }
}

// ── CHECKBOX SELECT ALL ──
function toggleCheckAll(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.row-check');
    checkboxes.forEach(cb => {
        cb.checked = masterCheckbox.checked;
        const id = parseInt(cb.dataset.id);
        if (masterCheckbox.checked) {
            selectedIds.add(id);
        } else {
            selectedIds.delete(id);
        }
        const row = cb.closest('tr');
        if (row) {
            if(masterCheckbox.checked) row.classList.add('bg-sky-50/50');
            else row.classList.remove('bg-sky-50/50');
        }
    });
    updateBulkUI();
}

// ── UPDATE BULK TOOLBAR ──
function updateBulkUI() {
    const btn = document.getElementById('btn-bulk-delete');
    const countEl = document.getElementById('bulk-count');
    const masterCb = document.getElementById('check-all');
    const allCheckboxes = document.querySelectorAll('.row-check');

    if (countEl) countEl.textContent = selectedIds.size;
    if (btn) btn.style.display = selectedIds.size > 0 ? 'flex' : 'none';

    if (masterCb && allCheckboxes.length > 0) {
        const checkedCount = document.querySelectorAll('.row-check:checked').length;
        masterCb.checked = checkedCount === allCheckboxes.length;
        masterCb.indeterminate = checkedCount > 0 && checkedCount < allCheckboxes.length;
    }
}

// ── BULK DELETE ──
async function bulkDeleteApps() {
    if (selectedIds.size === 0) return;
    if (!confirm(`Hapus ${selectedIds.size} data terpilih secara permanen?`)) return;

    const ids = Array.from(selectedIds);

    try {
        const res = await fetch(getApiCrudUrl(), {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids }),
        });
        if (res.ok) {
            selectedIds.clear();
            showToast('Terhapus', `${ids.length} data berhasil dihapus.`, 'success');
            loadData();
        } else {
            showToast('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
        }
    } catch {
        applicants = applicants.filter(a => !ids.includes(a.id));
        selectedIds.clear();
        renderTable();
        showToast('Terhapus (Demo)', `${ids.length} data dihapus (simulasi).`, 'success');
    }
}

// ── ANIMATE NUM ──
function animateValue(id, start, end, duration) {
    if (start === end) return;
    const el = document.getElementById(id);
    if (!el) return;
    
    const range = end - start, incr = end > start ? 1 : -1;
    const step = Math.max(10, Math.abs(Math.floor(duration / range)));
    let cur = start;
    const t = setInterval(() => { 
        cur += incr; 
        el.innerText = cur; 
        if (cur === end) clearInterval(t); 
    }, step);
}

// ── TOAST (TAILWIND UI) ──
function showToast(title, message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return; 

    const toast = document.createElement('div');
    const styles = {
        success: { text: '#059669', icon: 'check-circle-2', border: 'border-l-4 border-l-emerald-500' },
        error:   { text: '#e11d48', icon: 'alert-circle',   border: 'border-l-4 border-l-rose-500' },
        info:    { text: '#0284c7', icon: 'info',           border: 'border-l-4 border-l-sky-500' }
    };
    const c = styles[type] || styles.info;
    
    // Gunakan class bawaan tailwind dan custom toast CSS
    toast.className = `toast bg-white border border-slate-200 ${c.border} rounded-xl shadow-[0_10px_30px_-10px_rgba(0,0,0,0.1)] p-4 mb-3 flex items-start gap-3 transition-all`;
    
    toast.innerHTML = `
        <div class="flex-shrink-0 mt-0.5">
            <i data-lucide="${c.icon}" class="w-5 h-5" style="color:${c.text}"></i>
        </div>
        <div class="flex-1 min-w-0">
            <div class="font-bold text-sm text-slate-800 tracking-tight">${title}</div>
            <div class="text-xs font-medium text-slate-500 mt-0.5 leading-relaxed">${message}</div>
        </div>
        <button onclick="this.closest('.toast').remove()" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-slate-50">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    `;
        
    container.appendChild(toast);
    lucide.createIcons();
    
    // Hapus toast otomatis
    setTimeout(() => { 
        toast.style.animation = 'slideOut 0.3s ease forwards'; 
        setTimeout(() => toast.remove(), 300); 
    }, 4500);
}