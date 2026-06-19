// ══════════════════════════════════════════════
//  DOCUMENT REVIEW — Admin
//  Fetch dokumen peserta dari API, approve / reject
// ══════════════════════════════════════════════

let reviewCandidateId = null;   // applicant.id yang sedang direview

// ── BUKA MODAL ───────────────────────────────────────────
async function openDocReviewModal(candidateId) {
    const app = applicants.find(a => a.id === candidateId);
    if (!app) return;

    reviewCandidateId = candidateId;

    document.getElementById('doc-review-subtitle').textContent = `${app.name} · NIM ${app.nim}`;
    document.getElementById('doc-review-list').innerHTML = `
        <div style="padding:24px;text-align:center;color:var(--text-muted);font-size:12px">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2"
                style="display:block;margin:0 auto 8px;animation:spin .8s linear infinite">
                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
            </svg>
            Memuat dokumen...
        </div>`;
    document.getElementById('doc-review-empty').style.display = 'none';
    document.getElementById('doc-review-modal').classList.add('open');
    lucide.createIcons();

    await fetchAndRenderDocs(candidateId);
}

function closeDocReviewModal() {
    document.getElementById('doc-review-modal').classList.remove('open');
    reviewCandidateId = null;
}

// ── FETCH DOKUMEN DARI API ────────────────────────────────
async function fetchAndRenderDocs(candidateId) {
    try {
        const res  = await fetch(`${API_BASE_URL}/admin/applicants/${candidateId}/documents`);
        if (!res.ok) throw new Error('Gagal mengambil dokumen (HTTP ' + res.status + ')');
        const data = await res.json();
        renderDocReviewList(candidateId, data.data || []);
    } catch (e) {
        document.getElementById('doc-review-list').innerHTML = `
            <div style="padding:24px;text-align:center;color:var(--red);font-size:12px">
                ${e.message}
            </div>`;
    }
}

// ── RENDER DAFTAR DOKUMEN ─────────────────────────────────
function renderDocReviewList(candidateId, docs) {
    const list  = document.getElementById('doc-review-list');
    const empty = document.getElementById('doc-review-empty');

    if (!docs.length) {
        list.innerHTML = '';
        empty.style.display = 'block';
        return;
    }
    empty.style.display = 'none';

    const mimeIcon = mime => {
        if (!mime) return { icon: 'file', bg: 'rgba(100,116,139,.12)', color: 'var(--text-muted)' };
        if (mime.includes('pdf'))   return { icon: 'file-text',        bg: 'rgba(239,68,68,.12)',    color: 'var(--red)' };
        if (mime.includes('image')) return { icon: 'image',            bg: 'rgba(139,92,246,.12)',   color: 'var(--purple)' };
        if (mime.includes('word') || mime.includes('doc'))
                                    return { icon: 'file-text',        bg: 'rgba(59,130,246,.12)',   color: 'var(--accent)' };
        if (mime.includes('sheet') || mime.includes('excel'))
                                    return { icon: 'file-spreadsheet', bg: 'rgba(34,197,94,.12)',    color: 'var(--green)' };
        return { icon: 'file', bg: 'rgba(100,116,139,.12)', color: 'var(--text-muted)' };
    };

    const statusBadge = status => {
        const map = {
            pending:  `<span class="badge b-pending"  style="font-size:10px"><span class="badge-dot"></span>Menunggu Review</span>`,
            approved: `<span class="badge b-accepted" style="font-size:10px">✓ Disetujui</span>`,
            rejected: `<span class="badge b-rejected" style="font-size:10px">✕ Ditolak</span>`,
        };
        return map[status] || map.pending;
    };

    list.innerHTML = docs.map(doc => {
        const ic = mimeIcon(doc.mime_type);
        const isDecided = doc.status === 'approved' || doc.status === 'rejected';
        return `
        <div class="panel" style="padding:14px;margin-bottom:2px" id="doc-card-${doc.id}">
            <!-- Header baris -->
            <div style="display:flex;align-items:center;gap:10px">
                <div style="width:36px;height:36px;border-radius:9px;background:${ic.bg};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i data-lucide="${ic.icon}" style="width:16px;height:16px;color:${ic.color}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${doc.name}</div>
                    <div style="font-size:10px;color:var(--text-muted);margin-top:1px">${doc.file_name} · ${doc.file_size} · ${doc.uploaded_at}</div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;flex-shrink:0">
                    ${statusBadge(doc.status)}
                    ${doc.url ? `<a href="${doc.url}" target="_blank" class="btn-icon" title="Lihat / Download" style="text-decoration:none">
                        <i data-lucide="external-link" style="width:12px;height:12px"></i>
                    </a>` : ''}
                </div>
            </div>

            <!-- Catatan tolak (jika ada) -->
            ${doc.status === 'rejected' && doc.notes ? `
            <div style="margin-top:10px;padding:8px 10px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.15);border-radius:8px;font-size:11px;color:var(--red)">
                <strong>Catatan:</strong> ${doc.notes}
            </div>` : ''}

            <!-- Input catatan tolak (tersembunyi) -->
            <div id="reject-box-${doc.id}" style="display:none;margin-top:10px">
                <textarea id="reject-note-${doc.id}"
                    style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(239,68,68,.25);border-radius:9px;color:var(--text-primary);padding:9px 12px;font-size:12px;font-family:'Inter',sans-serif;resize:vertical;min-height:64px;outline:none"
                    placeholder="Tulis alasan penolakan untuk peserta..."></textarea>
                <div style="display:flex;gap:8px;margin-top:8px">
                    <button onclick="cancelReject(${doc.id})" class="btn-ghost" style="font-size:11px;flex:1;justify-content:center">Batal</button>
                    <button onclick="submitReject(${candidateId}, ${doc.id})"
                        style="flex:1;padding:8px;border-radius:9px;background:#dc2626;color:#fff;font-size:11px;font-weight:700;cursor:pointer;border:none;display:flex;align-items:center;justify-content:center;gap:5px">
                        <i data-lucide="x-circle" style="width:12px;height:12px"></i> Kirim Penolakan
                    </button>
                </div>
            </div>

            <!-- Tombol aksi (disembunyikan kalau sudah diputuskan) -->
            ${!isDecided ? `
            <div style="display:flex;gap:8px;margin-top:10px">
                <button onclick="submitApprove(${candidateId}, ${doc.id})"
                    class="btn-ghost btn-green"
                    style="font-size:11px;flex:1;justify-content:center;color:var(--green);border-color:rgba(34,197,94,.2)">
                    <i data-lucide="check-circle-2" style="width:12px;height:12px"></i> Setujui
                </button>
                <button onclick="openRejectBox(${doc.id})"
                    class="btn-ghost"
                    style="font-size:11px;flex:1;justify-content:center;color:var(--red);border-color:rgba(239,68,68,.2)">
                    <i data-lucide="x-circle" style="width:12px;height:12px"></i> Tolak
                </button>
            </div>` : `
            <div style="margin-top:10px;display:flex;gap:8px">
                <button onclick="resetDocStatus(${candidateId}, ${doc.id})"
                    class="btn-ghost" style="font-size:10px;padding:5px 10px;color:var(--text-muted)">
                    <i data-lucide="rotate-ccw" style="width:10px;height:10px"></i> Reset ke Pending
                </button>
            </div>`}
        </div>`;
    }).join('');

    lucide.createIcons();
}

// ── AKSI ─────────────────────────────────────────────────
function openRejectBox(docId) {
    document.getElementById(`reject-box-${docId}`).style.display = 'block';
    document.getElementById(`reject-note-${docId}`).focus();
}
function cancelReject(docId) {
    document.getElementById(`reject-box-${docId}`).style.display = 'none';
    document.getElementById(`reject-note-${docId}`).value = '';
}

async function submitApprove(candidateId, docId) {
    await verifyDoc(candidateId, docId, 'approved', '');
}

async function submitReject(candidateId, docId) {
    const note = document.getElementById(`reject-note-${docId}`).value.trim();
    if (!note) { showToast('Catatan Kosong', 'Tulis alasan penolakan terlebih dahulu.', 'error'); return; }
    await verifyDoc(candidateId, docId, 'rejected', note);
}

async function resetDocStatus(candidateId, docId) {
    await verifyDoc(candidateId, docId, 'pending', '');
}

async function verifyDoc(candidateId, docId, status, notes) {
    // Disable semua tombol di card ini sementara
    const card = document.getElementById(`doc-card-${docId}`);
    card?.querySelectorAll('button, a').forEach(el => el.style.pointerEvents = 'none');

    try {
        const res = await fetch(`${API_BASE_URL}/admin/documents/${docId}/verify`, {
            method:  'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ status, notes }),
        });
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message || 'Gagal memperbarui status');
        }

        const labels = { approved: 'Disetujui ✅', rejected: 'Ditolak ❌', pending: 'Direset ke Pending' };
        showToast(labels[status] || 'Berhasil', `Status dokumen berhasil diperbarui.`, status === 'approved' ? 'success' : 'info');

        // Refresh dokumen di modal + badge di tabel
        await fetchAndRenderDocs(candidateId);
        await refreshApplicantDocBadge(candidateId);

    } catch (e) {
        showToast('Gagal', e.message, 'error');
        card?.querySelectorAll('button, a').forEach(el => el.style.pointerEvents = '');
    }
}

// ── UPDATE BADGE PENDING DI TABEL ────────────────────────
async function refreshApplicantDocBadge(candidateId) {
    try {
        const res  = await fetch(`${API_BASE_URL}/admin/applicants/${candidateId}/documents`);
        const data = await res.json();
        const docs = data.data || [];
        // Simpan ke cache lokal di object applicant supaya renderTable() bisa pakai
        const app = applicants.find(a => a.id === candidateId);
        if (app) app._docCache = docs;
        renderTable();
    } catch {}
}

// Dipakai oleh candidates.js renderTable() untuk hitung badge
function countPendingDocs(app) {
    return (app._docCache || []).filter(d => d.status === 'pending').length;
}
