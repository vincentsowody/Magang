        <div id="view-documents" style="flex:1;overflow-y:auto;padding:20px;display:none;flex-direction:column;gap:16px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <!-- Kantor -->
                <div class="panel">
                    <div class="panel-hdr">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="stat-icon" style="background:var(--purple-light);width:28px;height:28px;margin:0;border-radius:7px">
                                <i data-lucide="building-2" style="width:13px;height:13px;color:var(--purple)"></i>
                            </div>
                            <div>
                                <div class="panel-title">Head Office</div>
                                <div class="panel-sub">Kantor Pusat · <span id="kantor-count">0</span> dokumen</div>
                            </div>
                        </div>
                        <button onclick="openUploadModal('kantor')" class="btn-ghost" style="font-size:11px;padding:6px 10px">
                            <i data-lucide="upload" style="width:12px;height:12px"></i> Upload
                        </button>
                    </div>
                    <div id="doc-list-kantor"></div>
                    <div id="doc-empty-kantor" style="padding:32px;text-align:center;color:var(--text-muted);font-size:12px">
                        <i data-lucide="file-plus" style="width:24px;height:24px;opacity:.4;margin:0 auto 8px;display:block"></i>
                        Belum ada dokumen
                    </div>
                </div>
                <!-- Terminal -->
                <div class="panel">
                    <div class="panel-hdr">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="stat-icon" style="background:var(--teal-light);width:28px;height:28px;margin:0;border-radius:7px">
                                <i data-lucide="plane" style="width:13px;height:13px;color:var(--teal)"></i>
                            </div>
                            <div>
                                <div class="panel-title">Terminal Ops</div>
                                <div class="panel-sub">Operasional Bandara · <span id="terminal-count">0</span> dokumen</div>
                            </div>
                        </div>
                        <button onclick="openUploadModal('terminal')" class="btn-ghost" style="font-size:11px;padding:6px 10px">
                            <i data-lucide="upload" style="width:12px;height:12px"></i> Upload
                        </button>
                    </div>
                    <div id="doc-list-terminal"></div>
                    <div id="doc-empty-terminal" style="padding:32px;text-align:center;color:var(--text-muted);font-size:12px">
                        <i data-lucide="file-plus" style="width:24px;height:24px;opacity:.4;margin:0 auto 8px;display:block"></i>
                        Belum ada dokumen
                    </div>
                </div>
            </div>
            <div class="panel" style="padding:14px 16px;display:flex;gap:10px;align-items:flex-start">
                <i data-lucide="info" style="width:14px;height:14px;color:var(--accent);flex-shrink:0;margin-top:1px"></i>
                <p style="font-size:12px;color:var(--text-muted);line-height:1.7">
                    Dokumen yang diupload akan <strong style="color:var(--text-primary)">otomatis tersedia</strong> di portal peserta sesuai lokasi penempatan masing-masing.
                </p>
            </div>
        </div>

