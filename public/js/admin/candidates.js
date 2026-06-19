// ══════════════════════════════════════════════
//  CORE: config, clock, navigasi view, modal generik,
//  data kandidat (CRUD), tabel, toast, animasi angka
// ══════════════════════════════════════════════
            const API_BASE_URL = window.APP_CONFIG.apiBaseUrl;
            const API_CRUD_URL = API_BASE_URL + '/applicants';
            let applicants = [];

            // Clock
            function updateClock() {
                const now = new Date();
                const el = document.getElementById('dash-clock');
                if (el) el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
            setInterval(updateClock, 1000);
            updateClock();

            lucide.createIcons();

                        function switchView(view) {
                const views = ['dashboard', 'candidates', 'documents', 'report'];

                views.forEach(v => {
                    const page = document.getElementById(`view-${v}`);
                    if (page) page.style.display = 'none';

                    const nav = document.getElementById(`nav-${v}`);
                    if (nav) nav.classList.remove('active');
                });

                const activePage = document.getElementById(`view-${view}`);
                if (activePage) {
                    activePage.style.display = 'flex';
                }

                const activeNav = document.getElementById(`nav-${view}`);
                if (activeNav) {
                    activeNav.classList.add('active');
                }

                const titles = {
                    dashboard: ['Dashboard', 'Rekrutmen PKL Batch 2025'],
                    candidates: ['Kandidat', 'Manajemen Data Pelamar'],
                    documents: ['Dokumen', 'Dokumen Peserta PKL'],
                    report: ['Laporan', 'Analitik dan Statistik']
                };

                if (titles[view]) {
                    document.getElementById('page-title').textContent = titles[view][0];
                    document.getElementById('page-sub').textContent = titles[view][1];
                }

                if (view === 'report' && typeof loadReport === 'function') {
                    loadReport();
                }
                if (view === 'documents' && typeof loadDocuments === 'function') {
                    loadDocuments();
                }
                if (view === 'candidates') {
                    loadData();
                }
            }
            // ── MODALS ──
            function openLogoutModal() { document.getElementById('logout-modal').classList.add('open'); }
            function closeLogoutModal() { document.getElementById('logout-modal').classList.remove('open'); }
            function handleLogout() { window.location.href = window.APP_CONFIG.loginUrl; }

            function openRegModal() {
                document.getElementById('reg-form').reset();
                document.getElementById('reg-modal').classList.add('open');
            }
            function closeRegModal() { document.getElementById('reg-modal').classList.remove('open'); }
            function closeSuccessModal() { document.getElementById('success-modal').classList.remove('open'); }
            function copyCode() {
                navigator.clipboard.writeText(document.getElementById('generated-code').innerText);
                showToast('Disalin!', 'Kode akses berhasil disalin.', 'success');
            }
            function openReviewModal(id) {
                const app = applicants.find(a => a.id === id);
                document.getElementById('edit-id').value = app.id;
                document.getElementById('edit-subtitle').innerText = app.name;
                document.getElementById('edit-status').value = app.status;
                document.querySelectorAll('input[name="placement"]').forEach(r => r.checked = false);
                if (app.location) {
                    const radio = document.querySelector(`input[name="placement"][value="${app.location}"]`);
                    if (radio) radio.checked = true;
                }
                toggleLoc();
                document.getElementById('review-modal').classList.add('open');
            }
            function closeReviewModal() { document.getElementById('review-modal').classList.remove('open'); }
            function toggleLoc() {
                const isAccepted = document.getElementById('edit-status').value === 'accepted';
                document.getElementById('edit-loc-box').classList.toggle('hidden', !isAccepted);
            }

            // ── DEMO DATA ──
            function loadDemoData() {
                applicants = [
                    { id: 101, name: 'Sarah Amalia', nim: '2105101', code: 'MAG-2025-089', univ: 'Universitas Indonesia', major: 'Psikologi', status: 'pending', location: null },
                    { id: 102, name: 'Dimas Pratama', nim: '1902204', code: 'MAG-2025-012', univ: 'ITB', major: 'Teknik Informatika', status: 'accepted', location: 'kantor' },
                    { id: 103, name: 'Reza Rahadian', nim: '2001105', code: 'MAG-2025-045', univ: 'UGM', major: 'Manajemen Bisnis', status: 'rejected', location: null },
                    { id: 104, name: 'Linda Kusuma', nim: '2103309', code: 'MAG-2025-102', univ: 'Universitas Brawijaya', major: 'Ilmu Komunikasi', status: 'accepted', location: 'terminal' },
                    { id: 105, name: 'Budi Santoso', nim: '2004401', code: 'MAG-2025-001', univ: 'Univ. Sam Ratulangi', major: 'Teknik Sipil', status: 'pending', location: null },
                    { id: 106, name: 'Citra Kirana', nim: '2109902', code: 'MAG-2025-156', univ: 'UNPAD', major: 'Hukum', status: 'accepted', location: 'kantor' },
                ];
                renderTable();
                showToast('Mode Demo', '6 data simulasi dimuat.', 'info');
            }

            // ── LOAD DATA ──
            async function loadData() {
                try {
                    const res = await fetch(API_CRUD_URL);
                    if (res.ok) { applicants = await res.json(); renderTable(); }
                    else throw new Error('API Error');
                } catch { applicants = []; renderTable(); }
            }

            // Panggil otomatis saat halaman pertama kali dimuat
            document.addEventListener('DOMContentLoaded', function () {
                loadData();
                if (typeof loadDocuments === 'function') loadDocuments();
            });

            // ── REGISTRATION ──
            async function handleRegistration(e) {
                e.preventDefault();
                const btn = document.getElementById('reg-btn');
                const orig = btn.innerHTML;
                btn.innerHTML = '<svg class="animate-spin inline" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Menyimpan...';
                btn.disabled = true;

                const payload = {
                    name: document.getElementById('reg-name').value,
                    nim: document.getElementById('reg-nim').value,
                    univ: document.getElementById('reg-univ').value,
                    major: document.getElementById('reg-major').value,
                };

                try {
                    const res = await fetch(API_CRUD_URL, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                    const result = await res.json();
                    if (res.ok) {
                        closeRegModal();
                        document.getElementById('generated-code').innerText = result.code;
                        document.getElementById('success-modal').classList.add('open');
                        showToast('Registrasi Berhasil', 'Data peserta ditambahkan.', 'success');
                        loadData();
                    } else showToast('Gagal', 'Terjadi kesalahan saat menyimpan.', 'error');
                } catch { showToast('Koneksi Error', 'Tidak dapat menghubungi server.', 'error'); }
                finally { btn.innerHTML = orig; btn.disabled = false; lucide.createIcons(); }
            }

            // ── RENDER TABLE ──
            function renderTable() {
                const tbody = document.getElementById('table-body');
                const filter = document.getElementById('filterStatus').value;
                const search = document.getElementById('searchInput').value.toLowerCase();
                tbody.innerHTML = '';

                let stats = { total: applicants.length, pending: 0, kantor: 0, terminal: 0 };
                const filtered = applicants.filter(a => {
                    if (a.status === 'pending') stats.pending++;
                    if (a.status === 'accepted' && a.location === 'kantor') stats.kantor++;
                    if (a.status === 'accepted' && a.location === 'terminal') stats.terminal++;
                    return (filter === 'all' || a.status === filter) && (a.name.toLowerCase().includes(search) || a.nim.includes(search));
                });

                animateValue('stat-total', parseInt(document.getElementById('stat-total').innerText) || 0, stats.total, 500);
                animateValue('stat-pending', parseInt(document.getElementById('stat-pending').innerText) || 0, stats.pending, 500);
                animateValue('stat-kantor', parseInt(document.getElementById('stat-kantor').innerText) || 0, stats.kantor, 500);
                animateValue('stat-terminal', parseInt(document.getElementById('stat-terminal').innerText) || 0, stats.terminal, 500);

                if (filtered.length === 0) {
                    document.getElementById('empty-state').classList.remove('hidden');
                } else {
                    document.getElementById('empty-state').classList.add('hidden');
                    filtered.sort((a, b) => b.id - a.id).forEach((a, i) => {
                        let badge = '';
                        if (a.status === 'pending') badge = `<span class="badge b-pending"><span class="badge-dot"></span>Pending</span>`;
                        else if (a.status === 'accepted') badge = `<span class="badge b-accepted">✓ Diterima</span>`;
                        else badge = `<span class="badge b-rejected">✕ Ditolak</span>`;

                        let loc = '<span style="color:var(--text-muted);font-size:11px">—</span>';
                        if (a.location === 'kantor') loc = '<span class="loc-tag loc-kantor">🏢 Head Office</span>';
                        if (a.location === 'terminal') loc = '<span class="loc-tag loc-terminal">✈ Terminal Ops</span>';

                        const dateStr = a.created_at ? new Date(a.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-';

                        tbody.innerHTML += `
                            <tr class="row-animate" style="animation-delay:${i * 35}ms">
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <div class="avatar">${a.name.charAt(0).toUpperCase()}</div>
                                        <div>
                                            <div class="td-name">${a.name}</div>
                                            <div class="td-sub" style="font-family:'JetBrains Mono',monospace">NIM: ${a.nim}</div>
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
            ${countPendingDocs(a) > 0 ? `<span style="position:absolute;top:-4px;right:-4px;width:14px;height:14px;border-radius:50%;background:var(--amber);color:#000;font-size:8px;font-weight:700;display:flex;align-items:center;justify-content:center">${countPendingDocs(a)}</span>` : ''}
        </button>
        <button onclick="openReviewModal(${a.id})" class="btn-icon" title="Review Status">
            <i data-lucide="file-edit" style="width:12px;height:12px"></i>
        </button>
        <button onclick="deleteApp(${a.id})" class="btn-icon danger" title="Hapus">
            <i data-lucide="trash-2" style="width:12px;height:12px"></i>
        </button>
    </div>
</td>
                            </tr>`;
                    });
                }
                lucide.createIcons();
            }

            // ── SAVE EDIT ──
            async function saveEdit() {
                const id = document.getElementById('edit-id').value;
                const status = document.getElementById('edit-status').value;
                let location = null;
                if (status === 'accepted') {
                    const checked = document.querySelector('input[name="placement"]:checked');
                    if (!checked) { showToast('Validasi', 'Pilih lokasi penempatan.', 'error'); return; }
                    location = checked.value;
                }
                try {
                    const res = await fetch(`${API_CRUD_URL}/${id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ status, location }) });
                    if (res.ok) { showToast('Berhasil', 'Status diperbarui.', 'success'); closeReviewModal(); loadData(); }
                    else showToast('Gagal', 'Gagal memperbarui data.', 'error');
                } catch {
                    const idx = applicants.findIndex(a => a.id == id);
                    if (idx > -1) { applicants[idx].status = status; applicants[idx].location = location; renderTable(); closeReviewModal(); showToast('Sukses (Demo)', 'Status diperbarui (simulasi).', 'success'); }
                    else showToast('Error', 'Kesalahan jaringan.', 'error');
                }
            }

            // ── DELETE ──
            async function deleteApp(id) {
                if (!confirm('Hapus data ini secara permanen?')) return;
                try {
                    const res = await fetch(`${API_CRUD_URL}/${id}`, { method: 'DELETE' });
                    if (res.ok) { showToast('Terhapus', 'Data peserta dihapus.', 'success'); loadData(); }
                } catch {
                    applicants = applicants.filter(a => a.id !== id);
                    renderTable();
                    showToast('Terhapus (Demo)', 'Data dihapus (simulasi).', 'success');
                }
            }

            // ── ANIMATE NUM ──
            function animateValue(id, start, end, duration) {
                if (start === end) return;
                const el = document.getElementById(id);
                const range = end - start, incr = end > start ? 1 : -1;
                const step = Math.max(10, Math.abs(Math.floor(duration / range)));
                let cur = start;
                const t = setInterval(() => { cur += incr; el.innerText = cur; if (cur == end) clearInterval(t); }, step);
            }

            // ── TOAST ──
            function showToast(title, message, type = 'success') {
                const container = document.getElementById('toast-container');
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
                    <button onclick="this.closest('.toast').remove()" style="color:rgba(100,116,139,0.5);cursor:pointer;padding:2px;transition:color .2s" onmouseover="this.style.color='#94a3b8'" onmouseout="this.style.color='rgba(100,116,139,0.5)'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>`;
                container.appendChild(toast);
                lucide.createIcons();
                setTimeout(() => { toast.classList.add('out'); setTimeout(() => toast.remove(), 350); }, 4000);
            }
