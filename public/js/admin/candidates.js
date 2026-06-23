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
        if (nav) nav.classList.remove('active');
    });

    const activePage = document.getElementById(`view-${view}`);
    if (activePage) activePage.style.display = 'flex';

    const activeNav = document.getElementById(`nav-${view}`);
    if (activeNav) activeNav.classList.add('active');

    const titles = {
        dashboard: ['Dashboard', 'Rekrutmen PKL Batch 2025'],
        candidates: ['Kandidat', 'Manajemen Data Pelamar'],
        documents: ['Dokumen', 'Dokumen Peserta PKL'],
        report: ['Laporan', 'Analitik dan Statistik']
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

    // FIX: dulu pakai name="placement" yang sama dengan placement-modal,
    // sekarang pakai name="edit-placement" yang terpisah (lihat catatan di
    // review-modal.blade.php) supaya tidak saling mempengaruhi.
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
    if (locBox) locBox.classList.toggle('hidden', !isAccepted);
}

// ── PILIH LOKASI (kartu Head Office / Terminal Ops) ──
// Setara dengan selectLocation() di placement-modal, tapi pakai ID/nama
// radio yang khusus untuk modal Review Status (edit-placement-*) supaya
// tidak bertabrakan dengan modal Konfirmasi Penerimaan.
function selectEditLocation(val) {
    const kantor = document.getElementById('edit-loc-kantor-card');
    const terminal = document.getElementById('edit-loc-terminal-card');
    const radioKantor = document.getElementById('edit-placement-kantor');
    const radioTerminal = document.getElementById('edit-placement-terminal');
    if (!kantor || !terminal) return;

    kantor.style.borderColor = (val === 'kantor') ? 'var(--accent)' : 'var(--border)';
    terminal.style.borderColor = (val === 'terminal') ? 'var(--accent)' : 'var(--border)';
    kantor.style.background = (val === 'kantor') ? 'var(--accent-light)' : '';
    terminal.style.background = (val === 'terminal') ? 'var(--teal-light)' : '';

    if (radioKantor) radioKantor.checked = (val === 'kantor');
    if (radioTerminal) radioTerminal.checked = (val === 'terminal');
}

// ── PILIHAN CEPAT DURASI MAGANG ──
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
        if (badge) badge.style.display = 'flex';
        return;
    }
    const months = Math.round((end - start) / (1000 * 60 * 60 * 24 * 30.44));
    info.textContent = `Durasi: ± ${months || 1} bulan`;
    if (badge) badge.style.display = 'flex';
}


// ── DEMO DATA ──
function loadDemoData() {
    applicants = [
        { id: 101, name: 'Sarah Amalia', nim: '2105101', code: 'MAG-2025-089', univ: 'Universitas Indonesia', major: 'Psikologi', status: 'pending', location: null, internship_start: null, internship_end: null },
        { id: 102, name: 'Dimas Pratama', nim: '1902204', code: 'MAG-2025-012', univ: 'ITB', major: 'Teknik Informatika', status: 'accepted', location: 'kantor', internship_start: '2025-12-01', internship_end: '2026-02-28' },
        { id: 103, name: 'Reza Rahadian', nim: '2001105', code: 'MAG-2025-045', univ: 'UGM', major: 'Manajemen Bisnis', status: 'rejected', location: null, internship_start: null, internship_end: null },
        { id: 104, name: 'Linda Kusuma', nim: '2103309', code: 'MAG-2025-102', univ: 'Universitas Brawijaya', major: 'Ilmu Komunikasi', status: 'accepted', location: 'terminal', internship_start: '2025-11-15', internship_end: '2026-01-15' },
        { id: 105, name: 'Budi Santoso', nim: '2004401', code: 'MAG-2025-001', univ: 'Univ. Sam Ratulangi', major: 'Teknik Sipil', status: 'pending', location: null, internship_start: null, internship_end: null },
        { id: 106, name: 'Citra Kirana', nim: '2109902', code: 'MAG-2025-156', univ: 'UNPAD', major: 'Hukum', status: 'accepted', location: 'kantor', internship_start: '2025-12-10', internship_end: '2026-03-10' },
    ];
    renderTable();
    showToast('Mode Demo', '6 data simulasi dimuat.', 'info');
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
    btn.innerHTML = '<svg class="animate-spin inline" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Menyimpan...';
    btn.disabled = true;

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

    if (!name) {
        showToast('Validasi', 'Nama peserta wajib diisi.', 'error');
        btn.innerHTML = orig;
        btn.disabled = false;
        return;
    }
    if (!nim) {
        showToast('Validasi', 'NIM / No. Induk wajib diisi.', 'error');
        btn.innerHTML = orig;
        btn.disabled = false;
        return;
    }
    if (!univ) {
        showToast('Validasi', 'Universitas / Sekolah wajib diisi.', 'error');
        btn.innerHTML = orig;
        btn.disabled = false;
        return;
    }
    if (!major) {
        showToast('Validasi', 'Pilih Program Studi terlebih dahulu.', 'error');
        btn.innerHTML = orig;
        btn.disabled = false;
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

// ── RENDER TABLE ──
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
        if (a.status === 'accepted' && a.location === 'kantor') stats.kantor++;
        if (a.status === 'accepted' && a.location === 'terminal') stats.terminal++;
        return (filter === 'all' || a.status === filter) && (a.name.toLowerCase().includes(search) || (a.nim || '').toLowerCase().includes(search));
    });

    animateValue('stat-total', 0, stats.total, 500);
    animateValue('stat-pending', 0, stats.pending, 500);
    animateValue('stat-kantor', 0, stats.kantor, 500);
    animateValue('stat-terminal', 0, stats.terminal, 500);

    const emptyState = document.getElementById('empty-state');

    if (filtered.length === 0) {
        if (emptyState) emptyState.style.display = 'block';
    } else {
        if (emptyState) emptyState.style.display = 'none';

        filtered.sort((a, b) => b.id - a.id).forEach((a, i) => {
                    let badge = '';
                    if (a.status === 'pending') badge = `<span class="badge b-pending"><span class="badge-dot"></span>Pending</span>`;
                    else if (a.status === 'accepted') badge = `<span class="badge b-accepted">✓ Diterima</span>`;
                    else badge = `<span class="badge b-rejected">✕ Ditolak</span>`;

                    let loc = '<span style="color:var(--text-muted);font-size:11px">—</span>';
                    if (a.location === 'kantor') loc = '<span class="loc-tag loc-kantor">🏢 Head Office</span>';
                    if (a.location === 'terminal') loc = '<span class="loc-tag loc-terminal">✈ Terminal Ops</span>';

                    if (a.status === 'accepted' && a.internship_start && a.internship_end) {
                        const s = new Date(a.internship_start).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                        const e = new Date(a.internship_end).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                        loc += `<div class="td-sub" style="margin-top:3px">📅 ${s} – ${e}</div>`;
                    }

                    const dateStr = a.created_at ? new Date(a.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

                    const pendingDocsCount = typeof countPendingDocs === 'function' ? countPendingDocs(a) : 0;
                    const isChecked = selectedIds.has(a.id);

                    tbody.innerHTML += `
                <tr class="row-animate" style="animation-delay:${i * 35}ms;${isChecked ? 'background:rgba(59,130,246,0.07)' : ''}">
                    <td style="padding:12px 16px;text-align:center">
                        <input type="checkbox" class="row-check" data-id="${a.id}" onchange="toggleRowCheck(this)"
                            ${isChecked ? 'checked' : ''}
                            style="width:14px;height:14px;cursor:pointer;accent-color:var(--accent)">
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar">${a.name.charAt(0).toUpperCase()}</div>
                            <div>
                                <div class="td-name">${a.name}</div>
                                <div class="td-sub" style="font-family:'JetBrains Mono',monospace">NIM: ${a.nim || '<em style="opacity:0.4">—</em>'}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="code-chip">${a.code}</span></td>
                    <td>
                        <div style="font-size:12px;color:var(--text-secondary);font-weight:500">${a.univ}</div>
                        <div class="td-sub">${a.major}</div>
                    </td>
                    <td>${badge}</td>
                    <td>${loc}</td>
                    <td style="font-size:11px;color:var(--text-muted)">${dateStr}</td>
                    <td style="text-align:center">
                        <div style="display:flex;justify-content:center;gap:6px">
                            <button onclick="openDocReviewModal(${a.id})" class="btn-icon" title="Review Dokumen" style="position:relative">
                                <i data-lucide="file-check-2" style="width:12px;height:12px"></i>
                                ${pendingDocsCount > 0 ? `<span style="position:absolute;top:-4px;right:-4px;width:14px;height:14px;border-radius:50%;background:var(--amber);color:#000;font-size:8px;font-weight:700;display:flex;align-items:center;justify-content:center">${pendingDocsCount}</span>` : ''}
                            </button>
                            ${a.status === 'pending' ? `<button onclick="openPlacementModal(${a.id}, '${a.name.replace(/'/g, "\\'")}')" class="btn-icon" title="Terima Pelamar" style="color:var(--green);border-color:rgba(34,197,94,0.3)">
                                <i data-lucide="user-check" style="width:12px;height:12px"></i>
                            </button>` : ''}
                            ${a.status === 'accepted' ? `<button onclick="triggerReplyLetterUpload(${a.id})" class="btn-icon" title="${a.reply_letter_path ? 'Surat balasan sudah ada — klik untuk ganti' : 'Surat balasan belum ada — klik untuk upload'}" style="${a.reply_letter_path ? 'color:var(--green);border-color:rgba(34,197,94,0.3)' : 'color:var(--amber);border-color:rgba(245,158,11,0.3)'}">
                                <i data-lucide="${a.reply_letter_path ? 'file-check-2' : 'file-warning'}" style="width:12px;height:12px"></i>
                            </button>` : ''}
                            <button onclick="openReviewModal(${a.id})" class="btn-icon" title="Edit Status">
                                <i data-lucide="file-edit" style="width:12px;height:12px"></i>
                            </button>
                            <button onclick="deleteApp(${a.id})" class="btn-icon danger" title="Hapus">
                                <i data-lucide="trash-2" style="width:12px;height:12px"></i>
                            </button>
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
            showToast('Error Tampilan', 'Field tanggal masa magang tidak ditemukan di halaman. Refresh halaman dan coba lagi.', 'error');
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

// ── UPLOAD / GANTI SURAT BALASAN LANGSUNG DARI TABEL ──
// Sebelumnya tidak ada cara lain untuk upload surat balasan selain saat
// pertama kali "Terima Pelamar" — kalau upload itu gagal (atau dilewati),
// tidak ada jalan untuk retry. Sekarang bisa lewat tombol di tabel kandidat.
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
        if (row) row.style.background = masterCheckbox.checked ? 'rgba(59,130,246,0.07)' : '';
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

// ── TOAST ──
function showToast(title, message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return; 

    const toast = document.createElement('div');
    const colors = {
        success: { accent: '#4ade80', icon: 'check-circle-2' },
        error: { accent: '#f87171', icon: 'alert-circle' },
        info: { accent: '#00b9e8', icon: 'info' }
    };
    const c = colors[type] || colors.info;
    
    toast.className = 'toast';
    toast.style.borderLeft = `3px solid ${c.accent}`;
    toast.innerHTML = `
        <i data-lucide="${c.icon}" style="width:16px;height:16px;color:${c.accent};flex-shrink:0;margin-top:1px"></i>
        <div style="flex:1">
            <div style="font-weight:600;font-size:13px;color:#e2e8f0;margin-bottom:2px">${title}</div>
            <div style="font-size:12px;color:rgba(148,163,184,0.7)">${message}</div>
        </div>
        <button onclick="this.closest('.toast').remove()" style="color:rgba(100,116,139,0.5);cursor:pointer;padding:2px;transition:color .2s;background:transparent;border:none" onmouseover="this.style.color='#94a3b8'" onmouseout="this.style.color='rgba(100,116,139,0.5)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>`;
        
    container.appendChild(toast);
    lucide.createIcons();
    setTimeout(() => { 
        toast.classList.add('out'); 
        setTimeout(() => toast.remove(), 350); 
    }, 4000);
}