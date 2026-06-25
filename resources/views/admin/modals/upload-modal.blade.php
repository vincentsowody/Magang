<div id="upload-modal" class="modal-backdrop">
    <div class="modal-box" style="max-width:520px;width:100%">

        {{-- Header --}}
        <div class="modal-hdr" style="display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:11px">
                <div class="stat-icon" style="background:var(--teal-light);width:34px;height:34px;margin:0;border-radius:9px">
                    <i data-lucide="upload-cloud" style="width:15px;height:15px;color:var(--teal)"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary)">Upload Dokumen</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:1px">File otomatis tersedia di portal peserta</div>
                </div>
            </div>
            <button onclick="closeUploadModal()" class="btn-icon">
                <i data-lucide="x" style="width:14px;height:14px"></i>
            </button>
        </div>

        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px">

            {{-- Lokasi Penempatan --}}
            <div>
                <label class="form-label">Lokasi Penempatan <span style="color:var(--red)">*</span></label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px" id="upload-loc-selector">
                    <label id="upload-loc-kantor" onclick="setUploadLoc('kantor')"
                        style="display:flex;align-items:center;gap:10px;padding:10px 13px;border:2px solid var(--border);border-radius:var(--r-sm);cursor:pointer;transition:all .15s">
                        <div style="width:30px;height:30px;border-radius:8px;background:var(--accent-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i data-lucide="building-2" style="width:13px;height:13px;color:var(--accent)"></i>
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:700;color:var(--text-primary)">Head Office</div>
                            <div style="font-size:10px;color:var(--text-muted)">Kantor Pusat</div>
                        </div>
                    </label>
                    <label id="upload-loc-terminal" onclick="setUploadLoc('terminal')"
                        style="display:flex;align-items:center;gap:10px;padding:10px 13px;border:2px solid var(--border);border-radius:var(--r-sm);cursor:pointer;transition:all .15s">
                        <div style="width:30px;height:30px;border-radius:8px;background:var(--teal-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i data-lucide="plane" style="width:13px;height:13px;color:var(--teal)"></i>
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:700;color:var(--text-primary)">Terminal Ops</div>
                            <div style="font-size:10px;color:var(--text-muted)">Operasional Bandara</div>
                        </div>
                    </label>
                </div>
                <input type="hidden" id="upload-loc-val">
            </div>

            {{-- Nama Dokumen --}}
            <div>
                <label class="form-label">Nama Dokumen</label>
                <input type="text" id="upload-doc-name" class="form-input" placeholder="Contoh: Surat Keputusan (SK) Magang">
            </div>

            {{-- Tipe File --}}
            <div>
                <label class="form-label">Tipe File</label>
                <div style="display:flex;gap:6px;flex-wrap:wrap" id="type-chips">
                    <button type="button" onclick="selectDocType(this,'PDF')" class="type-chip active-chip">PDF</button>
                    <button type="button" onclick="selectDocType(this,'DOCX')" class="type-chip">DOCX</button>
                    <button type="button" onclick="selectDocType(this,'XLSX')" class="type-chip">XLSX</button>
                    <button type="button" onclick="selectDocType(this,'IMG')" class="type-chip">IMG</button>
                    <button type="button" onclick="selectDocType(this,'ZIP')" class="type-chip">ZIP</button>
                </div>
            </div>

            {{-- Drop Zone --}}
            <div>
                <label class="form-label">File Dokumen</label>
                <div class="doc-zone" id="drop-zone"
                    onclick="document.getElementById('file-input').click()"
                    ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)">
                    <input type="file" id="file-input" style="display:none" onchange="handleFileSelect(event)"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.zip">
                    <div id="drop-idle">
                        <div style="width:44px;height:44px;border-radius:12px;background:var(--teal-light);border:1px solid rgba(20,184,166,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                            <i data-lucide="upload-cloud" style="width:20px;height:20px;color:var(--teal)"></i>
                        </div>
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:3px">Klik atau seret file ke sini</div>
                        <div style="font-size:11px;color:var(--text-muted)">PDF, DOCX, XLSX, IMG, ZIP · Maks. 20 MB</div>
                    </div>
                    <div id="drop-preview" style="display:none;align-items:center;gap:12px;padding:4px 0">
                        <div id="file-icon-wrap" style="width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0"></div>
                        <div style="flex:1;text-align:left;min-width:0">
                            <div id="file-preview-name" style="font-size:12.5px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
                            <div id="file-preview-size" style="font-size:11px;color:var(--text-muted);margin-top:2px"></div>
                        </div>
                        <button type="button" onclick="clearFile(event)" class="btn-icon danger" style="flex-shrink:0">
                            <i data-lucide="x" style="width:12px;height:12px"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Upload progress --}}
            <div id="upload-progress-wrap" style="display:none">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                    <span style="font-size:11px;font-weight:600;color:var(--text-secondary)">Mengupload...</span>
                    <span id="upload-percent" style="font-size:11px;font-family:'JetBrains Mono',monospace;color:var(--accent)">0%</span>
                </div>
                <div class="progress-bar">
                    <div id="upload-progress-bar" class="progress-fill" style="width:0%;background:linear-gradient(90deg,var(--accent),#2470d8)"></div>
                </div>
            </div>

        </div>

        <div class="modal-ftr">
            <button onclick="closeUploadModal()" class="btn-ghost" style="flex:1;justify-content:center">Batal</button>
            <button onclick="handleUploadDoc()" id="btn-upload-save" class="btn-primary" style="flex:1;justify-content:center">
                <i data-lucide="upload" style="width:13px;height:13px"></i> Simpan Dokumen
            </button>
        </div>
    </div>
</div>
