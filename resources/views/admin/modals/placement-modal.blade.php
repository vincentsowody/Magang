<div id="placementModal" class="modal-backdrop">
    <div class="modal-box" style="max-width:560px;width:100%">

        {{-- Header --}}
        <div class="modal-hdr" style="display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <div class="stat-icon" style="background:var(--green-light);width:32px;height:32px;margin:0;border-radius:8px">
                    <i data-lucide="user-check" style="width:15px;height:15px;color:var(--green)"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary)">Konfirmasi Penerimaan</div>
                    <div id="placement-subtitle" style="font-size:11px;color:var(--text-muted);margin-top:1px">—</div>
                </div>
            </div>
            <button onclick="closePlacementModal()" class="btn-icon">
                <i data-lucide="x" style="width:14px;height:14px"></i>
            </button>
        </div>

        <div class="modal-body" style="display:flex;flex-direction:column;gap:16px">

            {{-- Step indicator --}}
            <div style="display:flex;align-items:center;gap:0">
                <div id="step-ind-1" style="display:flex;align-items:center;gap:6px;flex:1">
                    <div style="width:22px;height:22px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;flex-shrink:0">1</div>
                    <span style="font-size:11px;font-weight:600;color:var(--accent)">Penempatan & Durasi</span>
                </div>
                <div style="flex:1;height:1px;background:var(--border);margin:0 8px"></div>
                <div id="step-ind-2" style="display:flex;align-items:center;gap:6px">
                    <div id="step2-circle" style="width:22px;height:22px;border-radius:50%;background:var(--border);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:var(--text-muted);flex-shrink:0">2</div>
                    <span id="step2-label" style="font-size:11px;font-weight:600;color:var(--text-muted)">Surat Balasan</span>
                </div>
            </div>

            {{-- ══ STEP 1: Penempatan & Durasi ══ --}}
            <div id="placement-step-1">

                {{-- Unit / Divisi --}}
                <div style="margin-bottom:12px">
                    <label class="form-label">Unit / Divisi Penempatan <span style="color:var(--red)">*</span></label>
                    <select id="placement-lokasi" class="form-input">
                        <option value="">— Pilih Unit Penempatan —</option>
                        <option value="Aviation Security (AVSEC)">Aviation Security (AVSEC)</option>
                        <option value="Terminal Inspector">Terminal Inspector</option>
                        <option value="Information Technology (IT)">Information Technology (IT)</option>
                        <option value="Customer Service">Customer Service</option>
                        <option value="Human Capital">Human Capital</option>
                        <option value="Cargo & Logistics">Cargo & Logistics</option>
                        <option value="Finance & Accounting">Finance & Accounting</option>
                        <option value="Legal & Compliance">Legal & Compliance</option>
                        <option value="Engineering & Maintenance">Engineering & Maintenance</option>
                    </select>
                </div>

                {{-- Lokasi (kantor/terminal) --}}
                <div style="margin-bottom:12px">
                    <label class="form-label">Lokasi Kerja <span style="color:var(--red)">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <label id="loc-kantor-card" onclick="selectLocation('kantor')"
                            style="display:flex;align-items:center;gap:10px;padding:10px 12px;border:2px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;transition:all .15s">
                            <div style="width:30px;height:30px;border-radius:8px;background:var(--accent-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i data-lucide="building-2" style="width:14px;height:14px;color:var(--accent)"></i>
                            </div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:var(--text-primary)">Head Office</div>
                                <div style="font-size:10px;color:var(--text-muted)">Kantor Pusat</div>
                            </div>
                        </label>
                        <label id="loc-terminal-card" onclick="selectLocation('terminal')"
                            style="display:flex;align-items:center;gap:10px;padding:10px 12px;border:2px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;transition:all .15s">
                            <div style="width:30px;height:30px;border-radius:8px;background:var(--teal-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i data-lucide="plane" style="width:14px;height:14px;color:var(--teal)"></i>
                            </div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:var(--text-primary)">Terminal Ops</div>
                                <div style="font-size:10px;color:var(--text-muted)">Operasional Bandara</div>
                            </div>
                        </label>
                    </div>
                    <input type="hidden" id="placement-location-val">
                </div>

                {{-- Durasi magang --}}
                <div style="margin-bottom:4px">
                    <label class="form-label">Masa Magang <span style="color:var(--red)">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px">
                        <div>
                            <div style="font-size:10px;font-weight:600;color:var(--text-muted);margin-bottom:4px;text-transform:uppercase;letter-spacing:.05em">Tanggal Mulai</div>
                            <input type="date" id="placement-start" class="form-input" onchange="plUpdateDurationInfo()">
                        </div>
                        <div>
                            <div style="font-size:10px;font-weight:600;color:var(--text-muted);margin-bottom:4px;text-transform:uppercase;letter-spacing:.05em">Tanggal Selesai</div>
                            <input type="date" id="placement-end" class="form-input" onchange="plUpdateDurationInfo()">
                        </div>
                    </div>

                    {{-- Durasi info badge --}}
                    <div id="duration-badge" style="display:none;background:var(--green-light);border:1px solid rgba(34,197,94,0.2);border-radius:var(--radius-sm);padding:8px 12px;display:flex;align-items:center;gap:8px">
                        <i data-lucide="calendar-check" style="width:13px;height:13px;color:var(--green);flex-shrink:0"></i>
                        <span id="duration-text" style="font-size:12px;font-weight:600;color:var(--green)"></span>
                    </div>

                    {{-- Pilihan cepat durasi --}}
                    <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap">
                        <span style="font-size:10px;color:var(--text-muted);align-self:center">Pilihan cepat:</span>
                        @foreach([1=>'1 Bulan', 2=>'2 Bulan', 3=>'3 Bulan', 6=>'6 Bulan'] as $m => $label)
                        <button type="button" onclick="setDuration({{ $m }})"
                            style="font-size:10px;padding:3px 8px;border:1px solid var(--border);border-radius:4px;background:var(--bg);color:var(--text-secondary);cursor:pointer;transition:all .15s"
                            onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-secondary)'">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ══ STEP 2: Upload Surat Balasan ══ --}}
            <div id="placement-step-2" style="display:none;flex-direction:column;gap:12px">

                {{-- Info box --}}
                <div style="background:var(--accent-light);border:1px solid rgba(59,130,246,0.2);border-radius:var(--radius-sm);padding:10px 12px;display:flex;gap:8px">
                    <i data-lucide="info" style="width:14px;height:14px;color:var(--accent);flex-shrink:0;margin-top:1px"></i>
                    <div style="font-size:12px;color:var(--text-secondary);line-height:1.6">
                        Upload surat balasan resmi dari kantor sebagai bukti penerimaan magang.
                        File akan dapat diakses oleh peserta melalui portal mereka.
                    </div>
                </div>

                {{-- Drop Zone --}}
                <div id="pl-drop-zone"
                    onclick="document.getElementById('pl-file-input').click()"
                    ondragover="plDragOver(event)" ondragleave="plDragLeave(event)" ondrop="plDrop(event)"
                    style="border:2px dashed rgba(255,255,255,0.1);border-radius:var(--radius);padding:28px 20px;text-align:center;cursor:pointer;transition:all .2s">
                    <input type="file" id="pl-file-input" style="display:none" accept=".pdf,.jpg,.jpeg,.png" onchange="plFileSelect(event)">

                    <div id="pl-drop-idle">
                        <div style="width:44px;height:44px;border-radius:12px;background:var(--accent-light);border:1px solid rgba(59,130,246,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                            <i data-lucide="file-up" style="width:20px;height:20px;color:var(--accent)"></i>
                        </div>
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:3px">Klik atau seret file ke sini</div>
                        <div style="font-size:11px;color:var(--text-muted)">PDF, JPG, PNG · Maks. 5 MB</div>
                    </div>

                    <div id="pl-drop-preview" style="display:none;align-items:center;gap:12px">
                        <div style="width:38px;height:38px;border-radius:10px;background:var(--accent-light);border:1px solid rgba(59,130,246,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i data-lucide="file-check" style="width:17px;height:17px;color:var(--accent)"></i>
                        </div>
                        <div style="flex:1;text-align:left;min-width:0">
                            <div id="pl-file-name" style="font-size:12px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
                            <div id="pl-file-size" style="font-size:10px;color:var(--text-muted);margin-top:2px"></div>
                        </div>
                        <button type="button" onclick="plClearFile(event)" class="btn-icon danger" style="flex-shrink:0">
                            <i data-lucide="x" style="width:12px;height:12px"></i>
                        </button>
                    </div>
                </div>

                {{-- Skip note --}}
                <div style="text-align:center">
                    <span style="font-size:11px;color:var(--text-muted)">Upload surat bisa dilakukan nanti melalui tabel kandidat</span>
                </div>

            </div>

        </div>

        {{-- Footer --}}
        <div class="modal-ftr">
            <button id="pl-btn-back" onclick="goPlacementStep(1)" class="btn-ghost" style="display:none;flex:1;justify-content:center">
                <i data-lucide="arrow-left" style="width:13px;height:13px"></i> Kembali
            </button>
            <button onclick="closePlacementModal()" id="pl-btn-cancel" class="btn-ghost" style="flex:1;justify-content:center">Batal</button>
            <button id="pl-btn-next" onclick="goPlacementStep(2)" class="btn-primary" style="flex:1;justify-content:center">
                Lanjut <i data-lucide="arrow-right" style="width:13px;height:13px"></i>
            </button>
            <button id="pl-btn-submit" onclick="submitPlacement()" class="btn-primary" style="display:none;flex:1;justify-content:center">
                <i data-lucide="check" style="width:13px;height:13px"></i>
                <span id="pl-submit-label">Terima & Simpan</span>
            </button>
        </div>

    </div>
</div>

<script>
var _plApplicantId   = null;
var _plApplicantName = null;
var _plSelectedFile  = null;

// ── OPEN / CLOSE ─────────────────────────────────────
function openPlacementModal(id, name) {
    _plApplicantId   = id;
    _plApplicantName = name || 'Kandidat';

    var sub = document.getElementById('placement-subtitle');
    if (sub) sub.textContent = 'Peserta: ' + _plApplicantName;

    goPlacementStep(1);
    resetPlacementForm();

    var modal = document.getElementById('placementModal');
    if (modal) modal.classList.add('open');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closePlacementModal() {
    var modal = document.getElementById('placementModal');
    if (modal) modal.classList.remove('open');
    resetPlacementForm();
}

function resetPlacementForm() {
    var el;
    ['placement-lokasi','placement-start','placement-end'].forEach(function(id) {
        el = document.getElementById(id); if (el) el.value = '';
    });
    el = document.getElementById('placement-location-val'); if (el) el.value = '';
    selectLocation(null);
    plUpdateDurationInfo();
    plResetFile();
    _plSelectedFile = null;
}

// ── STEP NAVIGATION ───────────────────────────────────
function goPlacementStep(step) {
    var s1 = document.getElementById('placement-step-1');
    var s2 = document.getElementById('placement-step-2');
    var btnBack   = document.getElementById('pl-btn-back');
    var btnCancel = document.getElementById('pl-btn-cancel');
    var btnNext   = document.getElementById('pl-btn-next');
    var btnSubmit = document.getElementById('pl-btn-submit');
    var c1 = document.getElementById('step2-circle');
    var l1 = document.getElementById('step2-label');

    if (step === 1) {
        s1.style.display   = 'flex'; s1.style.flexDirection = 'column';
        s2.style.display   = 'none';
        btnBack.style.display   = 'none';
        btnCancel.style.display = '';
        btnNext.style.display   = '';
        btnSubmit.style.display = 'none';
        if (c1) { c1.style.background = 'var(--border)'; c1.style.color = 'var(--text-muted)'; }
        if (l1) l1.style.color = 'var(--text-muted)';
    } else {
        // Validate step 1 before proceeding
        if (!validateStep1()) return;
        s1.style.display   = 'none';
        s2.style.display   = 'flex'; s2.style.flexDirection = 'column';
        btnBack.style.display   = '';
        btnCancel.style.display = 'none';
        btnNext.style.display   = 'none';
        btnSubmit.style.display = '';
        if (c1) { c1.style.background = 'var(--accent)'; c1.style.color = '#fff'; }
        if (l1) l1.style.color = 'var(--accent)';
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function validateStep1() {
    var lokasi   = document.getElementById('placement-lokasi').value;
    var location = document.getElementById('placement-location-val').value;
    var start    = document.getElementById('placement-start').value;
    var end      = document.getElementById('placement-end').value;

    if (!lokasi)   { showToast('Validasi', 'Pilih unit/divisi penempatan.', 'error'); return false; }
    if (!location) { showToast('Validasi', 'Pilih lokasi kerja (Head Office / Terminal Ops).', 'error'); return false; }
    if (!start)    { showToast('Validasi', 'Tanggal mulai magang wajib diisi.', 'error'); return false; }
    if (!end)      { showToast('Validasi', 'Tanggal selesai magang wajib diisi.', 'error'); return false; }
    if (end <= start) { showToast('Validasi', 'Tanggal selesai harus setelah tanggal mulai.', 'error'); return false; }
    return true;
}

// ── LOCATION SELECTOR ────────────────────────────────
function selectLocation(val) {
    var kantor   = document.getElementById('loc-kantor-card');
    var terminal = document.getElementById('loc-terminal-card');
    var hidden   = document.getElementById('placement-location-val');
    if (!kantor || !terminal) return;

    kantor.style.borderColor   = (val === 'kantor')   ? 'var(--accent)' : 'var(--border)';
    terminal.style.borderColor = (val === 'terminal') ? 'var(--accent)' : 'var(--border)';
    kantor.style.background    = (val === 'kantor')   ? 'var(--accent-light)' : '';
    terminal.style.background  = (val === 'terminal') ? 'var(--teal-light)' : '';
    if (hidden) hidden.value = val || '';
}

// ── DURATION HELPERS ──────────────────────────────────
function setDuration(months) {
    var startEl = document.getElementById('placement-start');
    if (!startEl.value) {
        var today = new Date();
        startEl.value = today.toISOString().split('T')[0];
    }
    var start = new Date(startEl.value);
    start.setMonth(start.getMonth() + months);
    document.getElementById('placement-end').value = start.toISOString().split('T')[0];
    plUpdateDurationInfo();
}

function plUpdateDurationInfo() {
    var start = document.getElementById('placement-start').value;
    var end   = document.getElementById('placement-end').value;
    var badge = document.getElementById('duration-badge');
    var text  = document.getElementById('duration-text');
    if (!badge || !text) return;

    if (start && end && end > start) {
        var ms      = new Date(end) - new Date(start);
        var days    = Math.round(ms / 86400000);
        var months  = Math.floor(days / 30);
        var weeks   = Math.floor((days % 30) / 7);
        var parts   = [];
        if (months > 0) parts.push(months + ' bulan');
        if (weeks  > 0) parts.push(weeks  + ' minggu');
        if (parts.length === 0) parts.push(days + ' hari');

        var opts = { day:'numeric', month:'short', year:'numeric' };
        var startFmt = new Date(start).toLocaleDateString('id-ID', opts);
        var endFmt   = new Date(end).toLocaleDateString('id-ID', opts);
        text.textContent = parts.join(' ') + ' · ' + startFmt + ' – ' + endFmt;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

// ── FILE UPLOAD (STEP 2) ──────────────────────────────
function plDragOver(e)  { e.preventDefault(); document.getElementById('pl-drop-zone').style.borderColor = 'rgba(59,130,246,0.5)'; }
function plDragLeave(e) { document.getElementById('pl-drop-zone').style.borderColor = 'rgba(255,255,255,0.1)'; }
function plDrop(e) {
    e.preventDefault();
    document.getElementById('pl-drop-zone').style.borderColor = 'rgba(255,255,255,0.1)';
    var files = e.dataTransfer && e.dataTransfer.files;
    if (files && files.length > 0) plSetFile(files[0]);
}
function plFileSelect(e) { if (e.target.files && e.target.files[0]) plSetFile(e.target.files[0]); }

function plSetFile(file) {
    var allowed = ['application/pdf','image/jpeg','image/jpg','image/png'];
    if (allowed.indexOf(file.type) === -1) { showToast('Format Salah', 'Hanya PDF, JPG, atau PNG.', 'error'); return; }
    if (file.size > 5 * 1024 * 1024)       { showToast('File Terlalu Besar', 'Maks. 5 MB.', 'error'); return; }

    _plSelectedFile = file;
    document.getElementById('pl-drop-idle').style.display    = 'none';
    document.getElementById('pl-drop-preview').style.display = 'flex';
    document.getElementById('pl-file-name').textContent = file.name;
    document.getElementById('pl-file-size').textContent = plFmtSize(file.size);
    if (typeof lucide !== 'undefined') lucide.createIcons();

    var label = document.getElementById('pl-submit-label');
    if (label) label.textContent = 'Terima & Upload Surat';
}

function plClearFile(e) {
    e.stopPropagation();
    plResetFile();
}
function plResetFile() {
    _plSelectedFile = null;
    var inp = document.getElementById('pl-file-input'); if (inp) inp.value = '';
    var idle = document.getElementById('pl-drop-idle'); if (idle) idle.style.display = 'block';
    var prev = document.getElementById('pl-drop-preview'); if (prev) prev.style.display = 'none';
    var dz   = document.getElementById('pl-drop-zone'); if (dz) dz.style.borderColor = 'rgba(255,255,255,0.1)';
    var label = document.getElementById('pl-submit-label');
    if (label) label.textContent = 'Terima & Simpan';
}

function plFmtSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes/1024).toFixed(1) + ' KB';
    return (bytes/1048576).toFixed(1) + ' MB';
}

// ── SUBMIT ─────────────────────────────────────────────
async function submitPlacement() {
    if (!_plApplicantId) return;

    var btn   = document.getElementById('pl-btn-submit');
    var label = document.getElementById('pl-submit-label');
    if (btn) btn.disabled = true;
    if (label) label.textContent = 'Menyimpan...';

    var lokasi   = document.getElementById('placement-lokasi').value;
    var location = document.getElementById('placement-location-val').value;
    var start    = document.getElementById('placement-start').value;
    var end      = document.getElementById('placement-end').value;
    var base     = (window.APP_CONFIG && window.APP_CONFIG.apiBaseUrl) ? window.APP_CONFIG.apiBaseUrl : '/api';

    try {
        // Step 1: Update status + penempatan + periode
        var res = await fetch(base + '/applicants/' + _plApplicantId, {
            method : 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body   : JSON.stringify({
                status             : 'accepted',
                location           : location,
                lokasi_penempatan  : lokasi,
                internship_start   : start,
                internship_end     : end,
            }),
        });

        if (!res.ok) {
            var err = await res.json();
            showToast('Gagal', err.message || 'Terjadi kesalahan.', 'error');
            if (btn) btn.disabled = false;
            if (label) label.textContent = 'Terima & Simpan';
            return;
        }

        // Step 2: Upload surat balasan jika ada
        // FIX BUG: sebelumnya hasil fetch ini tidak pernah dicek (res.ok),
        // jadi kalau upload gagal di server, admin tetap melihat toast
        // "Berhasil" padahal file tidak pernah tersimpan dan klien tidak
        // akan pernah melihat surat balasannya.
        var letterUploaded = false;
        if (_plSelectedFile) {
            if (label) label.textContent = 'Mengupload surat...';
            var fd = new FormData();
            fd.append('reply_letter', _plSelectedFile);
            var letterRes = await fetch(base + '/applicants/' + _plApplicantId + '/reply-letter', {
                method : 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body   : fd,
            });

            if (letterRes.ok) {
                letterUploaded = true;
            } else {
                var letterErr = await letterRes.json().catch(function () { return {}; });
                showToast(
                    'Status Disimpan, Surat Gagal',
                    'Status penerimaan tersimpan, tapi surat balasan gagal diupload: ' + (letterErr.message || ('HTTP ' + letterRes.status)) + '. Coba upload ulang dari tabel kandidat.',
                    'error'
                );
            }
        }

        var ms      = new Date(end) - new Date(start);
        var months  = Math.round(ms / 86400000 / 30);
        var durText = months > 0 ? months + ' bulan' : Math.round(ms/86400000) + ' hari';
        showToast('Berhasil Diterima! 🎉', _plApplicantName + ' diterima · ' + durText + (letterUploaded ? ' · Surat terupload' : ''), 'success');

        closePlacementModal();
        if (typeof loadData === 'function') loadData();

    } catch(e) {
        showToast('Error', 'Gagal menghubungi server.', 'error');
        if (btn) btn.disabled = false;
        if (label) label.textContent = 'Terima & Simpan';
    }
}
</script>