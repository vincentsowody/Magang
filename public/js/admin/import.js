// ══════════════════════════════════════════════
//  IMPORT EXCEL — parse, preview, submit
// ══════════════════════════════════════════════

var importParsedData = [];
var importValidRows = [];
var importSkippedRows = []; // baris bermasalah yang user tetap mau import
var importRawHeaders = [];
var importRawRows = [];
var importImportAll = false; // toggle: import semua (termasuk data tidak lengkap)

// Mapping field sistem → index kolom Excel
var importColMap = { nama: 0, nim: 1, univ: 2, prodi: 3 };

// Kolom wajib diisi
var importRequiredFields = { nama: true, nim: true, univ: true, prodi: true };

// ── MODAL OPEN / CLOSE ──────────────────────────
function openImportModal() {
    importReset();
    var el = document.getElementById('import-modal');
    if (el) el.classList.add('open');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeImportModal() {
    var el = document.getElementById('import-modal');
    if (el) el.classList.remove('open');
    importReset();
}

function importReset() {
    importParsedData = [];
    importValidRows = [];
    importSkippedRows = [];
    importRawHeaders = [];
    importRawRows = [];
    importImportAll = false;
    importColMap = { nama: 0, nim: 1, univ: 2, prodi: 3 };
    importRequiredFields = { nama: true, nim: true, univ: true, prodi: true };

    var fileInput = document.getElementById('import-file-input');
    if (fileInput) fileInput.value = '';

    var idle = document.getElementById('import-drop-idle');
    var prev = document.getElementById('import-drop-preview');
    if (idle) idle.style.display = 'block';
    if (prev) prev.style.display = 'none';

    var wrap = document.getElementById('import-preview-wrap');
    if (wrap) wrap.style.display = 'none';

    var tbody = document.getElementById('import-preview-body');
    if (tbody) tbody.innerHTML = '';

    var btn = document.getElementById('import-submit-btn');
    var label = document.getElementById('import-submit-label');
    if (btn) btn.disabled = true;
    if (label) label.textContent = 'Pilih file dulu';

    var errBadge = document.getElementById('import-error-badge');
    if (errBadge) errBadge.style.display = 'none';

    var importAllWrap = document.getElementById('import-all-wrap');
    if (importAllWrap) importAllWrap.style.display = 'none';

    var dz = document.getElementById('import-drop-zone');
    if (dz) dz.style.borderColor = 'rgba(255,255,255,0.1)';

    var mappingPanel = document.getElementById('import-mapping-panel');
    if (mappingPanel) mappingPanel.style.display = 'none';

    var togglePanel = document.getElementById('import-toggle-panel');
    if (togglePanel) togglePanel.style.display = 'none';

    ['nama', 'nim', 'univ', 'prodi'].forEach(function(f) {
        var cb = document.getElementById('req-' + f);
        if (cb) cb.checked = true;
    });

    var allCb = document.getElementById('import-all-checkbox');
    if (allCb) allCb.checked = false;
}

// ── DRAG & DROP ──────────────────────────────────
function importHandleDragOver(e) {
    e.preventDefault();
    var dz = document.getElementById('import-drop-zone');
    if (dz) dz.style.borderColor = 'rgba(34,197,94,0.5)';
}

function importHandleDragLeave(e) {
    var dz = document.getElementById('import-drop-zone');
    if (dz) dz.style.borderColor = 'rgba(255,255,255,0.1)';
}

function importHandleDrop(e) {
    e.preventDefault();
    var dz = document.getElementById('import-drop-zone');
    if (dz) dz.style.borderColor = 'rgba(255,255,255,0.1)';
    var files = e.dataTransfer && e.dataTransfer.files;
    if (files && files.length > 0) importProcessFile(files[0]);
}

function importHandleFileSelect(e) {
    var files = e.target.files;
    if (files && files.length > 0) importProcessFile(files[0]);
}

function importClearFile(e) {
    e.stopPropagation();
    importReset();
}

// ── FILE PROCESSING ──────────────────────────────
function importProcessFile(file) {
    var allowedExts = ['.xlsx', '.xls', '.csv'];
    var ext = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
    if (allowedExts.indexOf(ext) === -1) {
        showToast('Format Salah', 'Hanya file .xlsx, .xls, atau .csv yang didukung.', 'error');
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        showToast('File Terlalu Besar', 'Ukuran file maksimal 5 MB.', 'error');
        return;
    }

    var idle = document.getElementById('import-drop-idle');
    var prev = document.getElementById('import-drop-preview');
    var nameEl = document.getElementById('import-file-name');
    var sizeEl = document.getElementById('import-file-size');
    if (idle) idle.style.display = 'none';
    if (prev) prev.style.display = 'flex';
    if (nameEl) nameEl.textContent = file.name;
    if (sizeEl) sizeEl.textContent = importFormatSize(file.size);

    var reader = new FileReader();
    reader.onload = function(ev) {
        try {
            var data = new Uint8Array(ev.target.result);
            var workbook = XLSX.read(data, { type: 'array' });
            var sheet = workbook.Sheets[workbook.SheetNames[0]];
            var rows = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });
            importSetupFromRows(rows);
        } catch (err) {
            showToast('Gagal Membaca', 'File tidak dapat dibaca.', 'error');
            importReset();
        }
    };
    reader.readAsArrayBuffer(file);
}

function importFormatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1024 / 1024).toFixed(1) + ' MB';
}

// ── SETUP: DETEKSI HEADER & MAPPING OTOMATIS ─────
function importSetupFromRows(rows) {
    if (!rows || rows.length === 0) {
        showToast('Data Kosong', 'File tidak mengandung data.', 'error');
        return;
    }

    importRawHeaders = rows[0].map(function(h) { return String(h || '').trim(); });
    importRawRows = rows.slice(1).filter(function(r) {
        return r.some(function(c) { return String(c || '').trim() !== ''; });
    });

    var keywords = {
        nama: ['nama', 'name'],
        nim: ['nim', 'nip', 'nis', 'no induk', 'nomor induk', 'no.induk', 'no induk siswa', 'no. induk', 'nisn', 'npm', 'no mahasiswa', 'nomor mahasiswa', 'no. mahasiswa', 'nomor pokok', 'nrp', 'nuptk'],
        univ: ['universitas', 'university', 'perguruan', 'institusi', 'kampus', 'sekolah',
            'smk', 'sma', 'smp', 'politeknik', 'asal instansi', 'instansi'
        ],
        prodi: ['prodi', 'program studi', 'jurusan', 'major', 'dept', 'departemen',
            'bagian', 'unit', 'fakultas'
        ],
    };

    importColMap = { nama: null, nim: null, univ: null, prodi: null };

    importRawHeaders.forEach(function(h, idx) {
        var hl = h.toLowerCase();
        Object.keys(keywords).forEach(function(field) {
            if (importColMap[field] !== null) return;
            keywords[field].forEach(function(kw) {
                if (importColMap[field] === null && hl.indexOf(kw) !== -1) {
                    importColMap[field] = idx;
                }
            });
        });
    });

    if (importColMap.nama === null) importColMap.nama = 0;
    if (importColMap.nim === null) importColMap.nim = 1;
    if (importColMap.univ === null) importColMap.univ = 2;
    if (importColMap.prodi === null) importColMap.prodi = 3;

    importRenderMappingPanel();
    importRenderTogglePanel();
    importParseAndPreview();
}

// ── RENDER PANEL MAPPING ──────────────────────────
function importRenderMappingPanel() {
    var panel = document.getElementById('import-mapping-panel');
    var body = document.getElementById('import-mapping-body');
    if (!panel || !body) return;

    panel.style.display = 'flex';

    var fields = [
        { key: 'nama', label: 'Nama' },
        { key: 'nim', label: 'NIM / No. Induk' },
        { key: 'univ', label: 'Universitas / Sekolah' },
        { key: 'prodi', label: 'Prodi / Jurusan' },
    ];

    var optsBase = '<option value="">— Kosongkan —</option>';
    importRawHeaders.forEach(function(h, i) {
        var letter = String.fromCharCode(65 + i);
        optsBase += '<option value="' + i + '">' + letter + ' — ' + (h || '(kolom ' + (i + 1) + ')') + '</option>';
    });

    var html = '';
    fields.forEach(function(f) {
        var val = importColMap[f.key];
        var opts = optsBase.replace('value="' + val + '"', 'value="' + val + '" selected');
        html +=
            '<div style="display:flex;align-items:center;gap:6px;flex:1;min-width:220px">' +
            '<span style="font-size:11px;color:var(--text-secondary);white-space:nowrap;min-width:110px">' + f.label + '</span>' +
            '<select id="map-' + f.key + '" onchange="importUpdateMap(\'' + f.key + '\')" ' +
            'style="flex:1;padding:5px 8px;background:var(--bg);border:1px solid var(--border);' +
            'border-radius:6px;font-size:12px;color:var(--text-primary);outline:none;cursor:pointer">' +
            opts + '</select></div>';
    });

    body.innerHTML = html;
}

function importUpdateMap(field) {
    var sel = document.getElementById('map-' + field);
    importColMap[field] = (sel && sel.value !== '') ? parseInt(sel.value) : null;
    importParseAndPreview();
}

// ── RENDER PANEL TOGGLE WAJIB ─────────────────────
function importRenderTogglePanel() {
    var panel = document.getElementById('import-toggle-panel');
    if (panel) panel.style.display = 'flex';
}

function importToggleRequired(field) {
    var cb = document.getElementById('req-' + field);
    if (!cb) return;
    importRequiredFields[field] = cb.checked;
    if (importRawRows.length > 0) importParseAndPreview();
}

// ── TOGGLE IMPORT SEMUA (termasuk yang tidak valid) ──
function importToggleAll() {
    var cb = document.getElementById('import-all-checkbox');
    if (!cb) return;
    importImportAll = cb.checked;
    importRenderPreview(); // re-render tombol & label
}

// ── PARSE & PREVIEW ───────────────────────────────
function importParseAndPreview() {
    importParsedData = [];
    importValidRows = [];
    importSkippedRows = [];

    importRawRows.forEach(function(row, i) {
        var get = function(colIdx) {
            return colIdx !== null && colIdx !== undefined ?
                String(row[colIdx] || '').trim() :
                '';
        };

        var nama = get(importColMap.nama);
        var nim = get(importColMap.nim);
        var univ = get(importColMap.univ);
        var prodi = get(importColMap.prodi);

        if (!nama && !nim && !univ && !prodi) return; // skip baris kosong total

        var errors = [];
        if (importRequiredFields.nama && !nama) errors.push('Nama kosong');
        if (importRequiredFields.nim && !nim) errors.push('NIM kosong');
        if (importRequiredFields.univ && !univ) errors.push('Universitas kosong');
        if (importRequiredFields.prodi && !prodi) errors.push('Prodi kosong');

        var entry = { nama: nama, nim: nim, univ: univ, prodi: prodi, errors: errors, rowNum: i + 2 };
        importParsedData.push(entry);

        if (errors.length === 0) {
            importValidRows.push(entry);
        } else {
            importSkippedRows.push(entry);
        }
    });

    importRenderPreview();
}

// ── RENDER PREVIEW TABLE ─────────────────────────
function importRenderPreview() {
    var wrap = document.getElementById('import-preview-wrap');
    var tbody = document.getElementById('import-preview-body');
    var count = document.getElementById('import-row-count');
    var errBadge = document.getElementById('import-error-badge');
    var errCount = document.getElementById('import-error-count');
    var btn = document.getElementById('import-submit-btn');
    var label = document.getElementById('import-submit-label');
    var allWrap = document.getElementById('import-all-wrap');
    var allDesc = document.getElementById('import-all-desc');

    if (!tbody) return;

    if (importParsedData.length === 0) {
        if (wrap) wrap.style.display = 'none';
        if (allWrap) allWrap.style.display = 'none';
        if (btn) btn.disabled = true;
        if (label) label.textContent = 'Tidak ada data';
        return;
    }

    if (wrap) { wrap.style.display = 'flex';
        wrap.style.flexDirection = 'column'; }

    var invalidCount = importSkippedRows.length;
    if (count) count.textContent = '(' + importParsedData.length + ' baris ditemukan)';

    // Badge error
    if (invalidCount > 0 && errBadge && errCount) {
        errBadge.style.display = 'flex';
        errBadge.style.alignItems = 'center';
        errBadge.style.gap = '4px';
        errCount.textContent = invalidCount;
    } else if (errBadge) {
        errBadge.style.display = 'none';
    }

    // Panel "Tetap Import Semua" — muncul hanya jika ada data bermasalah
    if (allWrap) {
        if (invalidCount > 0) {
            allWrap.style.display = 'flex';
            if (allDesc) {
                allDesc.textContent = invalidCount + ' baris bermasalah akan tetap diimport dengan kolom kosong (—).';
            }
        } else {
            allWrap.style.display = 'none';
        }
    }

    // Render tabel
    tbody.innerHTML = '';
    importParsedData.forEach(function(row) {
        var isValid = row.errors.length === 0;
        var willSkip = !isValid && !importImportAll;

        var rowStyle = isValid ?
            '' :
            (willSkip ?
                'background:rgba(239,68,68,0.05);opacity:0.6' // merah redup = dilewati
                :
                'background:rgba(245,158,11,0.06)'); // amber = diimport tetap

        var statusEl = isValid ?
            '<span style="font-size:10px;font-weight:700;color:var(--green);background:var(--green-light);padding:2px 7px;border-radius:4px">✓ OK</span>' :
            (willSkip ?
                '<span style="font-size:10px;font-weight:700;color:#ef4444;background:rgba(239,68,68,0.1);padding:2px 7px;border-radius:4px" title="' + row.errors.join(', ') + '">✕ Dilewati</span>' :
                '<span style="font-size:10px;font-weight:700;color:var(--amber);background:var(--amber-light);padding:2px 7px;border-radius:4px" title="' + row.errors.join(', ') + '">⚠ ' + row.errors[0] + '</span>');

        tbody.innerHTML +=
            '<tr style="border-bottom:1px solid var(--border);' + rowStyle + '">' +
            '<td style="padding:7px 12px;color:var(--text-muted);font-size:11px">' + row.rowNum + '</td>' +
            '<td style="padding:7px 12px;font-size:12px;font-weight:500;color:var(--text-primary)">' + (row.nama || '<em style="color:var(--text-muted)">—</em>') + '</td>' +
            '<td style="padding:7px 12px;font-family:\'JetBrains Mono\',monospace;font-size:11px;color:var(--accent)">' + (row.nim || '<em style="color:var(--text-muted)">—</em>') + '</td>' +
            '<td style="padding:7px 12px;font-size:12px;color:var(--text-secondary)">' + (row.univ || '<em style="color:var(--text-muted)">—</em>') + '</td>' +
            '<td style="padding:7px 12px;font-size:12px;color:var(--text-secondary)">' + (row.prodi || '<em style="color:var(--text-muted)">—</em>') + '</td>' +
            '<td style="padding:7px 12px;text-align:center">' + statusEl + '</td>' +
            '</tr>';
    });

    if (typeof lucide !== 'undefined') lucide.createIcons();

    // Update tombol submit
    if (btn && label) {
        var totalToImport = importImportAll ?
            importParsedData.length :
            importValidRows.length;

        if (totalToImport > 0) {
            btn.disabled = false;
            if (importImportAll && invalidCount > 0) {
                label.textContent = 'Import Semua ' + totalToImport + ' Data';
            } else {
                label.textContent = 'Import ' + totalToImport + ' Data';
            }
        } else {
            btn.disabled = true;
            label.textContent = 'Tidak ada data';
        }
    }
}

// ── SUBMIT ───────────────────────────────────────
async function handleImportSubmit() {
    var rowsToImport = importImportAll ? importParsedData : importValidRows;
    if (rowsToImport.length === 0) return;

    var btn = document.getElementById('import-submit-btn');
    var label = document.getElementById('import-submit-label');
    if (btn) btn.disabled = true;

    var success = 0,
        failed = 0,
        skippedCount = 0;
    var apiBase = (window.APP_CONFIG && window.APP_CONFIG.apiBaseUrl) ?
        window.APP_CONFIG.apiBaseUrl :
        '/api';

    for (var i = 0; i < rowsToImport.length; i++) {
        var row = rowsToImport[i];

        // Update label progress
        if (label) label.textContent = 'Mengimport ' + (i + 1) + '/' + rowsToImport.length + '...';

        // Jika data tidak valid tapi user tetap mau import,
        // kirim data apa adanya — kolom kosong dibiarkan kosong
        var payload = {
            name: row.nama || '',
            nim: row.nim || null,
            univ: row.univ || '',
            major: row.prodi || null,
        };

        // Tandai baris tidak lengkap agar mudah diidentifikasi admin nanti
        if (row.errors && row.errors.length > 0) {
            payload._incomplete = true;
            payload._notes = row.errors.join('; ');
        }

        try {
            var res = await fetch(apiBase + '/applicants', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            });

            if (res.ok) {
                success++;
            } else {
                failed++;
                console.warn('Baris ' + row.rowNum + ' gagal:', await res.text());
            }
        } catch (e) {
            failed++;
            console.error('Baris ' + row.rowNum + ' error:', e);
        }

        // Beri jeda kecil agar UI bisa repaint progress
        if (i % 5 === 0) await new Promise(function(r) { setTimeout(r, 0); });
    }

    // Hitung yang diskip (tidak masuk rowsToImport)
    skippedCount = importParsedData.length - rowsToImport.length;

    closeImportModal();

    // Ringkasan hasil
    var msg = success + ' data berhasil diimport.';
    if (failed > 0) msg += ' ' + failed + ' gagal (cek koneksi server).';
    if (skippedCount > 0) msg += ' ' + skippedCount + ' baris dilewati (tidak lengkap).';

    showToast(
        'Import Selesai',
        msg,
        success > 0 ? 'success' : 'error'
    );

    if (typeof loadData === 'function') loadData();
}

// ── TEMPLATE DOWNLOAD ────────────────────────────
function downloadTemplate() {
    var wb = XLSX.utils.book_new();
    var wsData = [
        ['Nama', 'NIM', 'Universitas', 'Program Studi'],
        ['Contoh: Budi Santoso', '2024001', 'Universitas Sam Ratulangi', 'Teknik Informatika'],
        ['Contoh: Sari Dewi', '2024002', 'Universitas Indonesia', 'Manajemen'],
    ];
    var ws = XLSX.utils.aoa_to_sheet(wsData);
    ws['!cols'] = [{ wch: 30 }, { wch: 15 }, { wch: 35 }, { wch: 25 }];
    XLSX.utils.book_append_sheet(wb, ws, 'Template Pelamar');
    XLSX.writeFile(wb, 'template-import-pelamar.xlsx');
    showToast('Template Diunduh', 'Isi data sesuai kolom yang tersedia.', 'info');
}