{{-- ══════════════════════════════════════════════════════════
     MODAL — Konfirmasi Penerimaan (Placement)
     Perbaikan tampilan:
     - Step indicator: circle + line ter-center vertikal dengan benar
     - Lokasi card: tinggi seragam, icon + teks selalu aligned
     - Duration badge: spacing konsisten, muncul tepat di bawah date row
     - Quick-pick buttons: ukuran dan font seragam semua 4 tombol
     - Drop zone: tinggi fixed, idle & preview center sempurna
     - Footer: tombol selalu equal-width, tidak ada layout shift saat step berganti
     ══════════════════════════════════════════════════════════ --}}

<style>
/* ── Placement modal overrides ── */
/* Select chevron tanpa data URI (aman untuk HTML parser) */
#placementModal .pl-select-arrow {
    background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
}
#placementModal .pl-field        { display:flex;flex-direction:column;gap:6px; }
#placementModal .pl-field-label  { font-size:11.5px;font-weight:600;color:var(--text-secondary);letter-spacing:0.01em; }
#placementModal .pl-field-label .req { color:var(--danger);margin-left:2px; }

/* Step indicator */
#placementModal .pl-step-wrap    { display:flex;align-items:center;gap:0;padding:4px 0; }
#placementModal .pl-step         { display:flex;align-items:center;gap:8px; }
#placementModal .pl-step-num     { width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;transition:background .2s,color .2s; }
#placementModal .pl-step-label   { font-size:11.5px;font-weight:600;transition:color .2s;white-space:nowrap; }
#placementModal .pl-step-line    { flex:1;height:1px;background:var(--border);margin:0 12px; }
#placementModal .pl-step.active .pl-step-num   { background:var(--primary);color:#fff; }
#placementModal .pl-step.active .pl-step-label { color:var(--primary); }
#placementModal .pl-step.inactive .pl-step-num   { background:var(--border);color:var(--text-muted); }
#placementModal .pl-step.inactive .pl-step-label { color:var(--text-muted); }

/* Location cards — equal height, pointer-events on whole card */
#placementModal .pl-loc-grid     { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
#placementModal .pl-loc-card     {
    display:flex;align-items:center;gap:10px;
    padding:12px 14px;
    border:2px solid var(--border);
    border-radius:var(--r-sm);
    cursor:pointer;
    transition:border-color .15s,background .15s;
    min-height:60px;
    user-select:none;
}
#placementModal .pl-loc-icon     { width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
#placementModal .pl-loc-title    { font-size:12px;font-weight:700;color:var(--text-primary);line-height:1.2; }
#placementModal .pl-loc-sub      { font-size:10px;color:var(--text-muted);margin-top:2px; }

/* Duration badge */
#placementModal .pl-dur-badge    {
    display:none;align-items:center;gap:8px;
    background:rgba(22,163,74,0.08);
    border:1px solid rgba(22,163,74,0.18);
    border-radius:var(--r-sm);
    padding:9px 12px;
    margin-top:8px;
}
#placementModal .pl-dur-text     { font-size:12px;font-weight:600;color:var(--green); }

/* Quick-pick buttons — uniform size */
#placementModal .pl-quick-wrap   { display:flex;align-items:center;gap:6px;margin-top:8px;flex-wrap:wrap; }
#placementModal .pl-quick-label  { font-size:10px;color:var(--text-muted); }
#placementModal .pl-quick-btn    {
    font-size:11px;font-weight:600;
    padding:4px 11px;
    border:1px solid var(--border);
    border-radius:5px;
    background:var(--surface-muted);
    color:var(--text-secondary);
    cursor:pointer;
    font-family:'Inter',sans-serif;
    transition:border-color .12s,color .12s,background .12s;
    line-height:1.6;
}
#placementModal .pl-quick-btn:hover { border-color:var(--primary);color:var(--primary);background:var(--primary-dim); }

/* Drop zone */
#placementModal .pl-drop-zone    {
    border:2px dashed var(--border);
    border-radius:var(--r-lg);
    cursor:pointer;
    transition:border-color .2s,background .2s;
    min-height:110px;
    display:flex;align-items:center;justify-content:center;
}
#placementModal .pl-drop-zone:hover { border-color:rgba(37,99,235,0.4);background:var(--primary-dim); }
#placementModal .pl-drop-idle    { display:flex;flex-direction:column;align-items:center;gap:6px;padding:24px 16px;width:100%; }
#placementModal .pl-drop-preview { display:none;align-items:center;gap:12px;padding:16px 18px;width:100%; }

/* Footer — always equal-width buttons, no layout shift */
#placementModal .pl-footer-btns  { display:flex;gap:8px;width:100%; }
#placementModal .pl-footer-btns > button { flex:1;justify-content:center; }
</style>

<div id="placementModal" class="modal-backdrop">
    <div class="modal-box" style="max-width:560px;width:100%">

        {{-- ── Header ── --}}
        <div class="modal-hdr">
            <div style="display:flex;align-items:center;gap:12px">
                <div class="stat-icon" style="background:rgba(22,163,74,0.1);width:36px;height:36px;margin:0;border-radius:10px;border:1px solid rgba(22,163,74,0.15)">
                    <i data-lucide="user-check" style="width:16px;height:16px;color:var(--green)"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary);line-height:1.2">Konfirmasi Penerimaan</div>
                    <div id="placement-subtitle" style="font-size:11px;color:var(--text-muted);margin-top:2px">—</div>
                </div>
            </div>
            <button onclick="closePlacementModal()" class="btn-icon" title="Tutup">
                <i data-lucide="x" style="width:14px;height:14px"></i>
            </button>
        </div>

        <div class="modal-body" style="display:flex;flex-direction:column;gap:20px">

            {{-- ── Step Indicator ── --}}
            <div class="pl-step-wrap">
                <div id="pl-step-1" class="pl-step active">
                    <div class="pl-step-num">1</div>
                    <span class="pl-step-label">Penempatan &amp; Durasi</span>
                </div>
                <div class="pl-step-line"></div>
                <div id="pl-step-2" class="pl-step inactive">
                    <div id="step2-circle" class="pl-step-num">2</div>
                    <span id="step2-label" class="pl-step-label">Surat Balasan</span>
                </div>
            </div>

            {{-- ══ STEP 1: Penempatan & Durasi ══ --}}
            <div id="placement-step-1" style="display:flex;flex-direction:column;gap:16px">

                {{-- Unit / Divisi --}}
                <div class="pl-field">
                    <label class="pl-field-label">
                        Unit / Divisi Penempatan <span class="req">*</span>
                    </label>
                    <select id="placement-lokasi" class="form-input pl-select-arrow">
                        <option value="">— Pilih Unit Penempatan —</option>
                        <option value="Aviation Security (AVSEC)">Aviation Security (AVSEC)</option>
                        <option value="Terminal Inspector">Terminal Inspector</option>
                        <option value="Information Technology (IT)">Information Technology (IT)</option>
                        <option value="Customer Service">Customer Service</option>
                        <option value="Human Capital">Human Capital</option>
                        <option value="Cargo & Logistics">Cargo &amp; Logistics</option>
                        <option value="Finance & Accounting">Finance &amp; Accounting</option>
                        <option value="Legal & Compliance">Legal &amp; Compliance</option>
                        <option value="Engineering & Maintenance">Engineering &amp; Maintenance</option>
                    </select>
                </div>

                {{-- Lokasi Kerja --}}
                <div class="pl-field">
                    <label class="pl-field-label">
                        Lokasi Kerja <span class="req">*</span>
                    </label>
                    <div class="pl-loc-grid">
                        <div id="loc-kantor-card" class="pl-loc-card" onclick="selectLocation('kantor')">
                            <div class="pl-loc-icon" style="background:var(--accent-light)">
                                <i data-lucide="building-2" style="width:15px;height:15px;color:var(--accent)"></i>
                            </div>
                            <div>
                                <div class="pl-loc-title">Head Office</div>
                                <div class="pl-loc-sub">Kantor Pusat</div>
                            </div>
                        </div>
                        <div id="loc-terminal-card" class="pl-loc-card" onclick="selectLocation('terminal')">
                            <div class="pl-loc-icon" style="background:var(--teal-dim)">
                                <i data-lucide="plane" style="width:15px;height:15px;color:var(--teal)"></i>
                            </div>
                            <div>
                                <div class="pl-loc-title">Terminal Ops</div>
                                <div class="pl-loc-sub">Operasional Bandara</div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="placement-location-val">
                </div>

                {{-- Masa Magang --}}
                <div class="pl-field">
                    <label class="pl-field-label">
                        Masa Magang <span class="req">*</span>
                    </label>

                    {{-- Date row --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="pl-field" style="gap:4px">
                            <span style="font-size:10px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--text-muted)">Tanggal Mulai</span>
                            <input type="date" id="placement-start" class="form-input" onchange="plUpdateDurationInfo()">
                        </div>
                        <div class="pl-field" style="gap:4px">
                            <span style="font-size:10px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--text-muted)">Tanggal Selesai</span>
                            <input type="date" id="placement-end" class="form-input" onchange="plUpdateDurationInfo()">
                        </div>
                    </div>

                    {{-- Duration badge --}}
                    <div id="duration-badge" class="pl-dur-badge">
                        <i data-lucide="calendar-check" style="width:13px;height:13px;color:var(--green);flex-shrink:0"></i>
                        <span id="duration-text" class="pl-dur-text"></span>
                    </div>

                    {{-- Quick-pick durasi --}}
                    <div class="pl-quick-wrap">
                        <span class="pl-quick-label">Pilih cepat:</span>
                        @foreach([1=>'1 Bln', 2=>'2 Bln', 3=>'3 Bln', 6=>'6 Bln'] as $m => $lbl)
                        <button type="button" class="pl-quick-btn" onclick="setDuration({{ $m }})">{{ $lbl }}</button>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ══ STEP 2: Upload Surat Balasan ══ --}}
            <div id="placement-step-2" style="display:none;flex-direction:column;gap:16px">

                <div class="info-box">
                    <i data-lucide="info" style="width:14px;height:14px;color:var(--accent);flex-shrink:0;margin-top:1px"></i>
                    <div style="font-size:12px;line-height:1.65;color:var(--text-secondary)">
                        Upload surat balasan resmi sebagai bukti penerimaan magang.
                        File akan dapat diakses peserta melalui portal mereka.
                    </div>
                </div>

                {{-- Drop Zone --}}
                <div id="pl-drop-zone" class="pl-drop-zone"
                    onclick="document.getElementById('pl-file-input').click()"
                    ondragover="plDragOver(event)" ondragleave="plDragLeave(event)" ondrop="plDrop(event)">
                    <input type="file" id="pl-file-input" style="display:none" accept=".pdf,.jpg,.jpeg,.png" onchange="plFileSelect(event)">

                    {{-- Idle state --}}
                    <div id="pl-drop-idle" class="pl-drop-idle">
                        <div style="width:44px;height:44px;border-radius:12px;background:var(--accent-light);border:1px solid var(--primary-mid);display:flex;align-items:center;justify-content:center">
                            <i data-lucide="file-up" style="width:20px;height:20px;color:var(--accent)"></i>
                        </div>
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary)">Klik atau seret file ke sini</div>
                        <div style="font-size:11px;color:var(--text-muted)">PDF, JPG, PNG · Maks. 5 MB</div>
                    </div>

                    {{-- Preview state --}}
                    <div id="pl-drop-preview" class="pl-drop-preview">
                        <div style="width:40px;height:40px;border-radius:10px;background:var(--accent-light);border:1px solid var(--primary-mid);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i data-lucide="file-check" style="width:18px;height:18px;color:var(--accent)"></i>
                        </div>
                        <div style="flex:1;min-width:0">
                            <div id="pl-file-name" style="font-size:12.5px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
                            <div id="pl-file-size" style="font-size:10.5px;color:var(--text-muted);margin-top:3px"></div>
                        </div>
                        <button type="button" onclick="plClearFile(event)" class="btn-icon danger" title="Hapus file" style="flex-shrink:0">
                            <i data-lucide="x" style="width:12px;height:12px"></i>
                        </button>
                    </div>
                </div>

                <p style="font-size:11px;color:var(--text-muted);text-align:center;margin:0">
                    Upload surat bisa dilakukan nanti melalui tabel kandidat
                </p>

            </div>

        </div>

        {{-- ── Footer ── --}}
        <div class="modal-ftr">
            <div class="pl-footer-btns">
                <button id="pl-btn-back"   onclick="goPlacementStep(1)" class="btn-ghost"   style="display:none">
                    <i data-lucide="arrow-left" style="width:13px;height:13px"></i> Kembali
                </button>
                <button id="pl-btn-cancel" onclick="closePlacementModal()" class="btn-ghost">Batal</button>
                <button id="pl-btn-next"   onclick="goPlacementStep(2)" class="btn-primary">
                    Lanjut <i data-lucide="arrow-right" style="width:13px;height:13px"></i>
                </button>
                <button id="pl-btn-submit" onclick="submitPlacement()" class="btn-primary" style="display:none">
                    <i data-lucide="check" style="width:13px;height:13px"></i>
                    <span id="pl-submit-label">Terima &amp; Simpan</span>
                </button>
            </div>
        </div>

    </div>
</div>

<script>
var _plApplicantId   = null;
var _plApplicantName = null;
var _plSelectedFile  = null;

// ── OPEN / CLOSE ─────────────────────────────────────────────
function openPlacementModal(id, name) {
    _plApplicantId   = id;
    _plApplicantName = name || 'Kandidat';

    var sub = document.getElementById('placement-subtitle');
    if (sub) sub.textContent = 'Peserta: ' + _plApplicantName;

    resetPlacementForm();
    goPlacementStep(1);

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
    ['placement-lokasi', 'placement-start', 'placement-end'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.value = '';
    });
    var hidden = document.getElementById('placement-location-val');
    if (hidden) hidden.value = '';
    selectLocation(null);
    plUpdateDurationInfo();
    plResetFile();
    _plSelectedFile = null;
}

// ── STEP NAVIGATION ───────────────────────────────────────────
function goPlacementStep(step) {
    var s1        = document.getElementById('placement-step-1');
    var s2        = document.getElementById('placement-step-2');
    var btnBack   = document.getElementById('pl-btn-back');
    var btnCancel = document.getElementById('pl-btn-cancel');
    var btnNext   = document.getElementById('pl-btn-next');
    var btnSubmit = document.getElementById('pl-btn-submit');
    var step1ind  = document.getElementById('pl-step-1');
    var step2ind  = document.getElementById('pl-step-2');

    if (step === 1) {
        if (s1) { s1.style.display = 'flex'; s1.style.flexDirection = 'column'; }
        if (s2)   s2.style.display = 'none';
        if (btnBack)   btnBack.style.display   = 'none';
        if (btnCancel) btnCancel.style.display = '';
        if (btnNext)   btnNext.style.display   = '';
        if (btnSubmit) btnSubmit.style.display = 'none';
        if (step1ind) { step1ind.className = 'pl-step active'; }
        if (step2ind) { step2ind.className = 'pl-step inactive'; }
    } else {
        if (!validateStep1()) return;
        if (s1)   s1.style.display = 'none';
        if (s2) { s2.style.display = 'flex'; s2.style.flexDirection = 'column'; }
        if (btnBack)   btnBack.style.display   = '';
        if (btnCancel) btnCancel.style.display = 'none';
        if (btnNext)   btnNext.style.display   = 'none';
        if (btnSubmit) btnSubmit.style.display = '';
        if (step1ind) { step1ind.className = 'pl-step inactive'; }
        if (step2ind) { step2ind.className = 'pl-step active'; }
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function validateStep1() {
    var lokasi   = (document.getElementById('placement-lokasi') || {}).value;
    var location = (document.getElementById('placement-location-val') || {}).value;
    var start    = (document.getElementById('placement-start') || {}).value;
    var end      = (document.getElementById('placement-end') || {}).value;

    if (!lokasi)        { showToast('Validasi', 'Pilih unit/divisi penempatan.', 'error'); return false; }
    if (!location)      { showToast('Validasi', 'Pilih lokasi kerja (Head Office / Terminal Ops).', 'error'); return false; }
    if (!start)         { showToast('Validasi', 'Tanggal mulai magang wajib diisi.', 'error'); return false; }
    if (!end)           { showToast('Validasi', 'Tanggal selesai magang wajib diisi.', 'error'); return false; }
    if (end <= start)   { showToast('Validasi', 'Tanggal selesai harus setelah tanggal mulai.', 'error'); return false; }
    return true;
}

// ── LOCATION SELECTOR ─────────────────────────────────────────
function selectLocation(val) {
    var kantor   = document.getElementById('loc-kantor-card');
    var terminal = document.getElementById('loc-terminal-card');
    var hidden   = document.getElementById('placement-location-val');
    if (!kantor || !terminal) return;

    kantor.style.borderColor   = (val === 'kantor')   ? 'var(--primary)' : 'var(--border)';
    kantor.style.background    = (val === 'kantor')   ? 'var(--accent-light)' : '';
    terminal.style.borderColor = (val === 'terminal') ? 'var(--teal)' : 'var(--border)';
    terminal.style.background  = (val === 'terminal') ? 'var(--teal-dim)' : '';
    if (hidden) hidden.value = val || '';
}

// ── DURATION HELPERS ──────────────────────────────────────────
function setDuration(months) {
    var startEl = document.getElementById('placement-start');
    if (!startEl) return;
    if (!startEl.value) {
        startEl.value = new Date().toISOString().split('T')[0];
    }
    var d = new Date(startEl.value);
    d.setMonth(d.getMonth() + months);
    var endEl = document.getElementById('placement-end');
    if (endEl) endEl.value = d.toISOString().split('T')[0];
    plUpdateDurationInfo();
}

function plUpdateDurationInfo() {
    var start = (document.getElementById('placement-start') || {}).value;
    var end   = (document.getElementById('placement-end')   || {}).value;
    var badge = document.getElementById('duration-badge');
    var text  = document.getElementById('duration-text');
    if (!badge || !text) return;

    if (start && end && end > start) {
        var ms     = new Date(end) - new Date(start);
        var days   = Math.round(ms / 86400000);
        var months = Math.floor(days / 30);
        var weeks  = Math.floor((days % 30) / 7);
        var parts  = [];
        if (months > 0) parts.push(months + ' bulan');
        if (weeks  > 0) parts.push(weeks  + ' minggu');
        if (!parts.length) parts.push(days + ' hari');

        var opts     = { day:'numeric', month:'short', year:'numeric' };
        var startFmt = new Date(start).toLocaleDateString('id-ID', opts);
        var endFmt   = new Date(end).toLocaleDateString('id-ID', opts);
        text.textContent    = parts.join(' ') + '  ·  ' + startFmt + ' – ' + endFmt;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

// ── DROP ZONE ─────────────────────────────────────────────────
function plDragOver(e) {
    e.preventDefault();
    var dz = document.getElementById('pl-drop-zone');
    if (dz) { dz.style.borderColor = 'var(--primary)'; dz.style.background = 'var(--primary-dim)'; }
}
function plDragLeave(e) {
    var dz = document.getElementById('pl-drop-zone');
    if (dz) { dz.style.borderColor = 'var(--border)'; dz.style.background = ''; }
}
function plDrop(e) {
    e.preventDefault();
    plDragLeave(e);
    var files = e.dataTransfer && e.dataTransfer.files;
    if (files && files.length > 0) plSetFile(files[0]);
}
function plFileSelect(e) {
    if (e.target.files && e.target.files[0]) plSetFile(e.target.files[0]);
}

function plSetFile(file) {
    var allowed = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    if (allowed.indexOf(file.type) === -1) { showToast('Format Salah', 'Hanya PDF, JPG, atau PNG.', 'error'); return; }
    if (file.size > 5 * 1024 * 1024)       { showToast('File Terlalu Besar', 'Maks. 5 MB.', 'error'); return; }

    _plSelectedFile = file;
    var idle = document.getElementById('pl-drop-idle');
    var prev = document.getElementById('pl-drop-preview');
    if (idle) idle.style.display = 'none';
    if (prev) prev.style.display = 'flex';
    var nameEl = document.getElementById('pl-file-name');
    var sizeEl = document.getElementById('pl-file-size');
    if (nameEl) nameEl.textContent = file.name;
    if (sizeEl) sizeEl.textContent = plFmtSize(file.size);
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
    var inp  = document.getElementById('pl-file-input');  if (inp)  inp.value = '';
    var idle = document.getElementById('pl-drop-idle');   if (idle) idle.style.display = 'flex';
    var prev = document.getElementById('pl-drop-preview'); if (prev) prev.style.display = 'none';
    var dz   = document.getElementById('pl-drop-zone');
    if (dz) { dz.style.borderColor = ''; dz.style.background = ''; }
    var label = document.getElementById('pl-submit-label');
    if (label) label.textContent = 'Terima & Simpan';
}

function plFmtSize(bytes) {
    if (bytes < 1024)    return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

// ── SUBMIT ────────────────────────────────────────────────────
async function submitPlacement() {
    if (!_plApplicantId) return;

    var btn   = document.getElementById('pl-btn-submit');
    var label = document.getElementById('pl-submit-label');
    if (btn)   btn.disabled = true;
    if (label) label.textContent = 'Menyimpan...';

    var lokasi   = (document.getElementById('placement-lokasi')        || {}).value;
    var location = (document.getElementById('placement-location-val')  || {}).value;
    var start    = (document.getElementById('placement-start')         || {}).value;
    var end      = (document.getElementById('placement-end')           || {}).value;
    var base     = (window.APP_CONFIG && window.APP_CONFIG.apiBaseUrl) ? window.APP_CONFIG.apiBaseUrl : '/api';

    try {
        var res = await fetch(base + '/applicants/' + _plApplicantId, {
            method : 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body   : JSON.stringify({
                status            : 'accepted',
                location          : location,
                lokasi_penempatan : lokasi,
                internship_start  : start,
                internship_end    : end,
            }),
        });

        if (!res.ok) {
            var err = await res.json().catch(function() { return {}; });
            showToast('Gagal', err.message || 'Terjadi kesalahan saat menyimpan.', 'error');
            if (btn)   btn.disabled = false;
            if (label) label.textContent = 'Terima & Simpan';
            return;
        }

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
                var letterErr = await letterRes.json().catch(function() { return {}; });
                showToast(
                    'Status Disimpan, Surat Gagal',
                    'Status tersimpan, tapi surat gagal diupload: ' + (letterErr.message || 'HTTP ' + letterRes.status) + '. Upload ulang dari tabel kandidat.',
                    'error'
                );
            }
        }

        var ms      = new Date(end) - new Date(start);
        var months  = Math.round(ms / 86400000 / 30);
        var durText = months > 0 ? months + ' bulan' : Math.round(ms / 86400000) + ' hari';
        showToast('Berhasil Diterima! 🎉',
            _plApplicantName + ' diterima · ' + durText + (letterUploaded ? ' · Surat terupload' : ''),
            'success'
        );

        closePlacementModal();
        if (typeof loadData === 'function') loadData();

    } catch (e) {
        showToast('Error', 'Gagal menghubungi server.', 'error');
        if (btn)   btn.disabled = false;
        if (label) label.textContent = 'Terima & Simpan';
    }
}
</script>
