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
            nav.className = 'nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-all';
        }
    });

    const activePage = document.getElementById(`view-${view}`);
    if (activePage) activePage.style.display = 'flex';

    const activeNav = document.getElementById(`nav-${view}`);
    if (activeNav) {
        activeNav.className = 'nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-sky-700 bg-sky-50 transition-all';
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

    const emptyState = document.getElementById('empty-state');

    if (filtered.length === 0) {
        if (emptyState) emptyState.style.display = 'flex';
    } else {
        if (emptyState) emptyState.style.display = 'none';

        filtered.sort((a, b) => b.id - a.id).forEach((a, i) => {
                    // Ambil inisial nama
                    const initials = a.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

                    // Badge Status Tailwind
                    let badge = '';
                    if (a.status === 'pending') badge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200"><i data-lucide="clock" class="w-3 h-3"></i> Pending</span>`;
                    else if (a.status === 'accepted') badge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200"><i data-lucide="check-circle-2" class="w-3 h-3"></i> Diterima</span>`;
                    else badge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200"><i data-lucide="x-circle" class="w-3 h-3"></i> Ditolak</span>`;

                    // Lokasi
                    let locText = '<span class="text-slate-300 font-bold">—</span>';
                    if (a.location === 'kantor' || a.location === 'Kantor Pusat') locText = '<span class="inline-flex items-center gap-1.5 font-bold text-slate-700 text-xs"><i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i> Head Office</span>';
                    else if (a.location === 'terminal' || a.location === 'Terminal Ops') locText = '<span class="inline-flex items-center gap-1.5 font-bold text-slate-700 text-xs"><i data-lucide="plane" class="w-3.5 h-3.5 text-slate-400"></i> Terminal Ops</span>';

                    if (a.status === 'accepted' && a.internship_start && a.internship_end) {
                        const s = new Date(a.internship_start).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                        const e = new Date(a.internship_end).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                        locText += `<div class="text-[10px] text-slate-500 mt-1">📅 ${s} – ${e}</div>`;
                    }

                    const pendingDocsCount = typeof countPendingDocs === 'function' ? countPendingDocs(a) : 0;
                    const isChecked = selectedIds.has(a.id);

                    tbody.innerHTML += `
                <tr class="hover:bg-slate-50 transition-colors group ${isChecked ? 'bg-sky-50/50' : ''}">
                    <td class="pl-6 pr-2 py-4 text-center">
                        <input type="checkbox" class="row-check w-4 h-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500 cursor-pointer transition-colors" data-id="${a.id}" onchange="toggleRowCheck(this)" ${isChecked ? 'checked' : ''}>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center shrink-0">
                                ${initials}
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm">${a.name}</div>
                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">NIM: ${a.nim || '<em class="opacity-50">—</em>'}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="font-mono text-[11px] font-bold text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-md tracking-wide">${a.code}</span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="font-bold text-slate-700 text-xs">${a.univ}</div>
                        <div class="text-[11px] font-medium text-slate-500 mt-0.5 max-w-[200px] truncate" title="${a.major}">${a.major}</div>
                    </td>
                    <td class="px-4 py-4">${badge}</td>
                    <td class="px-4 py-4">${locText}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openDocReviewModal(${a.id})" class="p-1.5 rounded-lg text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition-colors relative" title="Review Dokumen">
                                <i data-lucide="file-check-2" class="w-4 h-4"></i>
                                ${pendingDocsCount > 0 ? `<span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-amber-500 text-white flex items-center justify-center text-[8px] font-bold">${pendingDocsCount}</span>` : ''}
                            </button>
                            ${a.status === 'pending' ? `<button onclick="openPlacementModal(${a.id}, '${a.name.replace(/'/g, "\\'")}')" class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Terima Pelamar">
                                <i data-lucide="user-check" class="w-4 h-4"></i>
                            </button>` : ''}
                            ${a.status === 'accepted' ? `<button onclick="triggerReplyLetterUpload(${a.id})" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="${a.reply_letter_path ? 'Ganti surat balasan' : 'Upload surat balasan'}">
                                <i data-lucide="${a.reply_letter_path ? 'file-check-2' : 'file-warning'}" class="w-4 h-4 ${a.reply_letter_path ? 'text-emerald-500' : 'text-amber-500'}"></i>
                            </button>` : ''}
                            <button onclick="openReviewModal(${a.id})" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit Status">
                                <i data-lucide="file-edit" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteApp(${a.id})" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
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