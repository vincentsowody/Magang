        <div id="upload-modal" class="modal-backdrop">
            <div class="modal-box" style="max-width:520px">
                <div class="modal-header" style="display:flex;align-items:flex-start;justify-content:space-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Upload Dokumen</h3>
                        <p style="font-size:12px;color:rgba(100,116,139,0.6);margin-top:3px">Dokumen akan tersedia otomatis untuk peserta</p>
                    </div>
                    <button onclick="closeUploadModal()" class="btn-icon"><i data-lucide="x" style="width:15px;height:15px"></i></button>
                </div>
                <div class="modal-body" style="display:flex;flex-direction:column;gap:16px">

                    <!-- Location selector -->
                    <div>
                        <label class="form-label">Lokasi Penempatan</label>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px" id="upload-loc-selector">
                            <label class="place-card" id="upload-loc-kantor">
                                <input type="radio" name="upload-placement" value="kantor" onchange="setUploadLoc('kantor')">
                                <div class="place-card-inner" style="flex-direction:row;gap:10px;padding:12px 14px">
                                    <i data-lucide="building-2" style="width:16px;height:16px;flex-shrink:0"></i>
                                    <div style="text-align:left">
                                        <div style="font-size:12px;font-weight:700">Kantor Pusat</div>
                                        <div style="font-size:10px;opacity:.6;margin-top:1px">Head Office</div>
                                    </div>
                                </div>
                            </label>
                            <label class="place-card" id="upload-loc-terminal">
                                <input type="radio" name="upload-placement" value="terminal" onchange="setUploadLoc('terminal')">
                                <div class="place-card-inner" style="flex-direction:row;gap:10px;padding:12px 14px">
                                    <i data-lucide="plane" style="width:16px;height:16px;flex-shrink:0"></i>
                                    <div style="text-align:left">
                                        <div style="font-size:12px;font-weight:700">Terminal Ops</div>
                                        <div style="font-size:10px;opacity:.6;margin-top:1px">Operasional Bandara</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Doc name -->
                    <div>
                        <label class="form-label">Nama Dokumen</label>
                        <input type="text" id="upload-doc-name" class="form-input" placeholder="Contoh: Surat Keputusan (SK) Magang">
                    </div>

                    <!-- Doc type -->
                    <div>
                        <label class="form-label">Tipe File</label>
                        <div style="display:flex;gap:8px;flex-wrap:wrap" id="type-chips">
                            <button type="button" onclick="selectDocType(this,'PDF')" class="type-chip active-chip">PDF</button>
                            <button type="button" onclick="selectDocType(this,'DOCX')" class="type-chip">DOCX</button>
                            <button type="button" onclick="selectDocType(this,'XLSX')" class="type-chip">XLSX</button>
                            <button type="button" onclick="selectDocType(this,'IMG')" class="type-chip">IMG</button>
                            <button type="button" onclick="selectDocType(this,'ZIP')" class="type-chip">ZIP</button>
                        </div>
                    </div>

                    <!-- Drop zone -->
                    <div>
                        <label class="form-label">File Dokumen</label>
                        <div id="drop-zone"
                            onclick="document.getElementById('file-input').click()"
                            ondragover="handleDragOver(event)"
                            ondragleave="handleDragLeave(event)"
                            ondrop="handleDrop(event)"
                            style="border:2px dashed rgba(255,255,255,0.1);border-radius:14px;padding:28px 20px;text-align:center;cursor:pointer;transition:all .2s;position:relative">
                            <input type="file" id="file-input" style="display:none" onchange="handleFileSelect(event)" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.zip">
                            <div id="drop-idle">
                                <div style="width:44px;height:44px;border-radius:12px;background:rgba(0,185,232,0.08);border:1px solid rgba(0,185,232,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                                    <i data-lucide="upload-cloud" style="width:20px;height:20px;color:#00b9e8"></i>
                                </div>
                                <p style="font-size:13px;font-weight:600;color:#e2e8f0;margin-bottom:4px">Klik atau seret file ke sini</p>
                                <p style="font-size:11px;color:rgba(100,116,139,0.5)">PDF, DOCX, XLSX, IMG, ZIP · Maks. 20 MB</p>
                            </div>
                            <div id="drop-preview" style="display:none;align-items:center;gap:12px;padding:4px 0">
                                <div id="file-icon-wrap" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0"></div>
                                <div style="flex:1;text-align:left;min-width:0">
                                    <div id="file-preview-name" style="font-size:13px;font-weight:600;color:#e2e8f0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
                                    <div id="file-preview-size" style="font-size:11px;color:rgba(100,116,139,0.5);margin-top:2px"></div>
                                </div>
                                <button type="button" onclick="clearFile(event)" class="btn-icon delete" title="Hapus file" style="flex-shrink:0">
                                    <i data-lucide="x" style="width:13px;height:13px"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Progress bar (hidden by default) -->
                    <div id="upload-progress-wrap" style="display:none">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                            <span style="font-size:11px;font-weight:600;color:rgba(148,163,184,0.7)">Mengupload...</span>
                            <span id="upload-percent" style="font-size:11px;font-family:'DM Mono',monospace;color:#00b9e8">0%</span>
                        </div>
                        <div style="height:4px;background:rgba(255,255,255,0.06);border-radius:4px;overflow:hidden">
                            <div id="upload-progress-bar" style="height:100%;width:0%;background:linear-gradient(90deg,#00b9e8,#0284c7);border-radius:4px;transition:width .2s"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="closeUploadModal()" class="flex-1 py-2.5 font-semibold text-sm text-slate-400" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;">Batal</button>
                    <button onclick="handleUploadDoc()" id="btn-upload-save" class="btn-primary flex-1 justify-center py-2.5" style="border-radius:10px">
                        <i data-lucide="upload" style="width:14px;height:14px"></i>
                        Simpan Dokumen
                    </button>
                </div>
            </div>
        </div>
