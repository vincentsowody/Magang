// ══════════════════════════════════════════════
//  DOCUMENT MANAGER (Upload Modal)
// ══════════════════════════════════════════════
let documentsData = { kantor: [], terminal: [] };
let currentUploadLoc = null;

// ── Safe API base URL getter ──
function getApiBase() {
    if (window.APP_CONFIG && window.APP_CONFIG.apiBaseUrl) {
        return window.APP_CONFIG.apiBaseUrl;
    }
    return window.location.origin + '/api';
}

let currentDocType = 'PDF';
let selectedUploadFile = null;

// ── LOAD DOKUMEN DARI API ──
async function loadDocuments() {
    try {
        const res = await fetch(getApiBase() + '/admin/documents');
        if (!res.ok) throw new Error('API Error ' + res.status);
        const result = await res.json();
        if (result.success && result.data) {
            // Mapping field API → field yang dipakai renderDocList
            const mapDoc = d => ({
                id: d.id,
                name: d.name,
                type: (d.mime_type || '').includes('pdf') ? 'PDF' :
                    (d.mime_type || '').includes('image') ? 'IMG' :
                    (d.mime_type || '').includes('spreadsheet') || (d.file_name || '').match(/\.xlsx?$/i) ? 'XLSX' :
                    (d.mime_type || '').includes('word') || (d.file_name || '').match(/\.docx?$/i) ? 'DOCX' :
                    (d.mime_type || '').includes('zip') ? 'ZIP' : 'PDF',
                size: d.file_size,
                url: d.url,
                uploadedAt: new Date(d.uploaded_at),
            });
            documentsData.kantor = (result.data.kantor || []).map(mapDoc);
            documentsData.terminal = (result.data.terminal || []).map(mapDoc);
        } else {
            documentsData = { kantor: [], terminal: [] };
        }
    } catch (e) {
        console.error('[loadDocuments] Gagal:', e);
        documentsData = { kantor: [], terminal: [] };
    }
    renderDocList('kantor');
    renderDocList('terminal');
    updateDocBadge();
}

function openUploadModal(loc) {
    currentUploadLoc = loc || null;
    document.getElementById('upload-doc-name').value = '';
    selectedUploadFile = null;
    currentDocType = 'PDF';
    document.querySelectorAll('#type-chips .type-chip').forEach(c => c.classList.remove('active-chip'));
    document.querySelector('#type-chips .type-chip').classList.add('active-chip');
    document.getElementById('file-input').value = '';
    document.getElementById('drop-idle').style.display = 'block';
    document.getElementById('drop-preview').style.display = 'none';
    document.getElementById('upload-progress-wrap').style.display = 'none';
    document.getElementById('upload-progress-bar').style.width = '0%';
    document.getElementById('upload-percent').textContent = '0%';
    document.querySelectorAll('input[name="upload-placement"]').forEach(r => r.checked = (r.value === loc));
    document.getElementById('upload-modal').classList.add('open');
    lucide.createIcons();
}

function closeUploadModal() {
    document.getElementById('upload-modal').classList.remove('open');
}

function setUploadLoc(loc) {
    currentUploadLoc = loc;
}

function selectDocType(btn, type) {
    document.querySelectorAll('#type-chips .type-chip').forEach(c => c.classList.remove('active-chip'));
    btn.classList.add('active-chip');
    currentDocType = type;
}

function handleDragOver(e) {
    e.preventDefault();
    document.getElementById('drop-zone').classList.add('dragover');
}

function handleDragLeave(e) {
    e.preventDefault();
    document.getElementById('drop-zone').classList.remove('dragover');
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('drop-zone').classList.remove('dragover');
    if (e.dataTransfer.files && e.dataTransfer.files[0]) setSelectedUploadFile(e.dataTransfer.files[0]);
}

function handleFileSelect(e) {
    if (e.target.files && e.target.files[0]) setSelectedUploadFile(e.target.files[0]);
}

function setSelectedUploadFile(file) {
    if (file.size > 20 * 1024 * 1024) {
        showToast('Ukuran Terlalu Besar', 'Maksimal ukuran file 20 MB.', 'error');
        return;
    }
    selectedUploadFile = file;
    document.getElementById('drop-idle').style.display = 'none';
    document.getElementById('drop-preview').style.display = 'flex';
    document.getElementById('file-preview-name').textContent = file.name;
    document.getElementById('file-preview-size').textContent = formatFileSize(file.size);

    const ext = (file.name.split('.').pop() || '').toLowerCase();
    const iconMap = {
        pdf: { icon: 'file-text', bg: 'rgba(239,68,68,0.12)', color: 'var(--red)' },
        doc: { icon: 'file-text', bg: 'rgba(59,130,246,0.12)', color: 'var(--accent)' },
        docx: { icon: 'file-text', bg: 'rgba(59,130,246,0.12)', color: 'var(--accent)' },
        xls: { icon: 'file-spreadsheet', bg: 'rgba(34,197,94,0.12)', color: 'var(--green)' },
        xlsx: { icon: 'file-spreadsheet', bg: 'rgba(34,197,94,0.12)', color: 'var(--green)' },
        png: { icon: 'image', bg: 'rgba(139,92,246,0.12)', color: 'var(--purple)' },
        jpg: { icon: 'image', bg: 'rgba(139,92,246,0.12)', color: 'var(--purple)' },
        jpeg: { icon: 'image', bg: 'rgba(139,92,246,0.12)', color: 'var(--purple)' },
        zip: { icon: 'file-archive', bg: 'rgba(245,158,11,0.12)', color: 'var(--amber)' },
    };
    const meta = iconMap[ext] || { icon: 'file', bg: 'rgba(255,255,255,0.06)', color: 'var(--text-muted)' };
    const wrap = document.getElementById('file-icon-wrap');
    wrap.style.background = meta.bg;
    wrap.innerHTML = `<i data-lucide="${meta.icon}" style="width:18px;height:18px;color:${meta.color}"></i>`;
    lucide.createIcons();

    const typeByExt = { pdf: 'PDF', doc: 'DOCX', docx: 'DOCX', xls: 'XLSX', xlsx: 'XLSX', png: 'IMG', jpg: 'IMG', jpeg: 'IMG', zip: 'ZIP' };
    const guessed = typeByExt[ext];
    if (guessed) {
        const chip = Array.from(document.querySelectorAll('#type-chips .type-chip')).find(c => c.textContent.trim() === guessed);
        if (chip) selectDocType(chip, guessed);
    }
    const nameInput = document.getElementById('upload-doc-name');
    if (!nameInput.value.trim()) nameInput.value = file.name.replace(/\.[^/.]+$/, '');
}

function clearFile(e) {
    e.stopPropagation();
    selectedUploadFile = null;
    document.getElementById('file-input').value = '';
    document.getElementById('drop-idle').style.display = 'block';
    document.getElementById('drop-preview').style.display = 'none';
}

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function handleUploadDoc() {
    if (!currentUploadLoc) { showToast('Validasi', 'Pilih lokasi penempatan terlebih dahulu.', 'error'); return; }
    const name = document.getElementById('upload-doc-name').value.trim();
    if (!name) { showToast('Validasi', 'Nama dokumen wajib diisi.', 'error'); return; }
    if (!selectedUploadFile) { showToast('Validasi', 'Pilih file untuk diupload.', 'error'); return; }

    const btn = document.getElementById('btn-upload-save');
    btn.disabled = true;
    document.getElementById('upload-progress-wrap').style.display = 'block';

    let pct = 0;
    const bar = document.getElementById('upload-progress-bar');
    const label = document.getElementById('upload-percent');
    const timer = setInterval(() => {
        pct += Math.random() * 25 + 10;
        if (pct >= 100) {
            pct = 100;
            clearInterval(timer);
            bar.style.width = '100%';
            label.textContent = '100%';
            setTimeout(() => finalizeUpload(name, btn), 250);
        } else {
            bar.style.width = pct + '%';
            label.textContent = Math.round(pct) + '%';
        }
    }, 180);
}

async function finalizeUpload(name, btn) {

    try {

        const formData = new FormData();

        formData.append('file', selectedUploadFile);
        formData.append('name', name);
        formData.append('location', currentUploadLoc);
        formData.append('type', currentDocType);

        const response = await fetch(getApiBase() + '/admin/documents', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(
                result.error ||
                result.message ||
                'Upload gagal'
            );
        }

        btn.disabled = false;
        closeUploadModal();

        showToast(
            'Upload Berhasil',
            'Dokumen berhasil disimpan ke server',
            'success'
        );

        // Reload daftar dokumen dari server agar tampil di UI
        await loadDocuments();

    } catch (error) {

        console.error(error);
        btn.disabled = false;

        showToast(
            'Upload Gagal',
            error.message,
            'error'
        );
    }
}

function renderDocList(loc) {
    const list = document.getElementById(`doc-list-${loc}`);
    const empty = document.getElementById(`doc-empty-${loc}`);
    const countEl = document.getElementById(`${loc}-count`);
    const items = documentsData[loc];
    if (countEl) countEl.textContent = items.length;
    if (!items.length) {
        list.innerHTML = '';
        empty.style.display = 'block';
        return;
    }
    empty.style.display = 'none';
    const badgeClass = { PDF: 'doc-pdf', DOCX: 'doc-docx', XLSX: 'doc-xlsx', IMG: 'doc-img', ZIP: 'doc-zip' };
    list.innerHTML = items.slice().reverse().map(d => `
        <div class="doc-file-row">
            <span class="doc-type-badge ${badgeClass[d.type]||'doc-pdf'}">${d.type}</span>
            <div style="flex:1;min-width:0">
                <div style="font-size:12px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${d.name}</div>
                <div style="font-size:10px;color:var(--text-muted);margin-top:1px">${d.size} · ${d.uploadedAt.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'})}</div>
            </div>
            <button class="btn-icon danger" title="Hapus" onclick="deleteDoc('${loc}', ${d.id})">
                <i data-lucide="trash-2" style="width:12px;height:12px"></i>
            </button>
        </div>`).join('');
    lucide.createIcons();
}

async function deleteDoc(loc, id) {
    if (!confirm('Hapus dokumen ini secara permanen?')) return;
    try {
        const res = await fetch(getApiBase() + '/admin/documents/' + id, { method: 'DELETE' });
        if (!res.ok) throw new Error('Gagal menghapus');
    } catch (e) {
        console.error('[deleteDoc]', e);
        showToast('Gagal', 'Tidak dapat menghapus dokumen.', 'error');
        return;
    }
    await loadDocuments();
    showToast('Terhapus', 'Dokumen dihapus.', 'success');
}

function updateDocBadge() {
    const total = documentsData.kantor.length + documentsData.terminal.length;
    const badge = document.getElementById('doc-badge');
    if (badge) {
        badge.textContent = total;
        badge.style.display = total > 0 ? 'inline-flex' : 'none';
    }
}