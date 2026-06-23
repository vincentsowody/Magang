<div id="import-modal" class="modal-backdrop">
    <div class="modal-box" style="max-width:640px;width:100%">

        {{-- Header --}}
        <div class="modal-hdr" style="display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <div class="stat-icon" style="background:var(--green-light);width:32px;height:32px;margin:0;border-radius:8px">
                    <i data-lucide="file-spreadsheet" style="width:15px;height:15px;color:var(--green)"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary)">Import Data via Excel</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:1px">Upload file .xlsx atau .xls untuk menambah pelamar massal</div>
                </div>
            </div>
            <button onclick="closeImportModal()" class="btn-icon">
                <i data-lucide="x" style="width:14px;height:14px"></i>
            </button>
        </div>

        <div class="modal-body" style="display:flex;flex-direction:column;gap:16px">

            {{-- Template download --}}
            <div style="background:var(--accent-light);border:1px solid rgba(59,130,246,0.2);border-radius:var(--radius-sm);padding:10px 14px;display:flex;align-items:center;justify-content:space-between;gap:10px">
                <div style="display:flex;align-items:center;gap:8px">
                    <i data-lucide="info" style="width:14px;height:14px;color:var(--accent);flex-shrink:0"></i>
                    <span style="font-size:12px;color:var(--text-secondary)">
                        Gunakan template agar format kolom sesuai sistem
                    </span>
                </div>
                <button onclick="downloadTemplate()" class="btn-ghost" style="font-size:11px;padding:5px 10px;flex-shrink:0">
                    <i data-lucide="download" style="width:12px;height:12px"></i> Download Template
                </button>
            </div>

            {{-- Drop Zone --}}
            <div id="import-drop-zone"
                onclick="document.getElementById('import-file-input').click()"
                ondragover="importHandleDragOver(event)"
                ondragleave="importHandleDragLeave(event)"
                ondrop="importHandleDrop(event)"
                style="border:2px dashed rgba(255,255,255,0.1);border-radius:var(--radius);padding:32px 20px;text-align:center;cursor:pointer;transition:all .2s">
                <input type="file" id="import-file-input" style="display:none" accept=".xlsx,.xls,.csv" onchange="importHandleFileSelect(event)">

                <div id="import-drop-idle">
                    <div style="width:48px;height:48px;border-radius:12px;background:var(--green-light);border:1px solid rgba(34,197,94,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                        <i data-lucide="upload-cloud" style="width:22px;height:22px;color:var(--green)"></i>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:4px">Klik atau seret file Excel ke sini</div>
                    <div style="font-size:11px;color:var(--text-muted)">.xlsx, .xls, .csv · Maks. 5 MB</div>
                </div>

                <div id="import-drop-preview" style="display:none;align-items:center;gap:12px">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--green-light);border:1px solid rgba(34,197,94,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i data-lucide="file-spreadsheet" style="width:18px;height:18px;color:var(--green)"></i>
                    </div>
                    <div style="flex:1;text-align:left;min-width:0">
                        <div id="import-file-name" style="font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
                        <div id="import-file-size" style="font-size:11px;color:var(--text-muted);margin-top:2px"></div>
                    </div>
                    <button type="button" onclick="importClearFile(event)" class="btn-icon danger" title="Hapus file">
                        <i data-lucide="x" style="width:12px;height:12px"></i>
                    </button>
                </div>
            </div>

            {{-- Mapping Kolom --}}
            <div id="import-mapping-panel" style="display:none;flex-direction:column;gap:10px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 14px">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:2px">
                    <i data-lucide="columns" style="width:13px;height:13px;color:var(--accent)"></i>
                    <span style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">Petakan Kolom Excel</span>
                    <span style="font-size:11px;font-weight:400;text-transform:none;color:var(--text-muted)">— sesuaikan jika urutan kolom berbeda</span>
                </div>
                <div id="import-mapping-body" style="display:flex;flex-wrap:wrap;gap:8px"></div>
            </div>

            {{-- Toggle Kolom Wajib --}}
            <div id="import-toggle-panel" style="display:none;flex-direction:column;gap:8px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 14px">
                <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px">
                    Kolom Wajib Diisi
                    <span style="font-weight:400;text-transform:none;font-size:11px;color:var(--text-muted)"> — nonaktifkan kolom yang boleh kosong</span>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    @foreach([['nama','Nama','A'],['nim','NIM','B'],['univ','Universitas','C'],['prodi','Prodi','D']] as $f)
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:5px 10px;border-radius:6px;border:1px solid var(--border);background:var(--surface);user-select:none"
                           onclick="importToggleRequired('{{ $f[0] }}')">
                        <input type="checkbox" id="req-{{ $f[0] }}" checked
                               style="width:13px;height:13px;accent-color:var(--accent);cursor:pointer"
                               onclick="event.stopPropagation();importToggleRequired('{{ $f[0] }}')">
                        <span style="font-size:12px;color:var(--text-secondary)">
                            <span style="font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--accent);margin-right:3px">{{ $f[2] }}</span>{{ $f[1] }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Panel: Import semua termasuk data tidak valid --}}
            <div id="import-all-wrap" style="display:none;align-items:flex-start;gap:10px;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.25);border-radius:var(--radius-sm);padding:11px 14px">
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;width:100%">
                    <input type="checkbox" id="import-all-checkbox"
                           style="width:14px;height:14px;margin-top:2px;accent-color:var(--amber);cursor:pointer;flex-shrink:0"
                           onchange="importToggleAll()">
                    <div>
                        <div style="font-size:12px;font-weight:700;color:var(--amber);margin-bottom:2px">
                            Tetap import data tidak lengkap
                        </div>
                        <div id="import-all-desc" style="font-size:11px;color:var(--text-muted);line-height:1.5">
                            Baris bermasalah akan tetap diimport dengan kolom kosong (—).
                        </div>
                    </div>
                </label>
            </div>

            {{-- Preview table --}}
            <div id="import-preview-wrap" style="display:none;flex-direction:column;gap:10px">
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <div style="font-size:12px;font-weight:600;color:var(--text-secondary)">
                        Preview Data <span id="import-row-count" style="color:var(--green)"></span>
                    </div>
                    <div id="import-error-badge" style="display:none;font-size:11px;color:var(--amber);background:var(--amber-light);border:1px solid rgba(245,158,11,0.2);padding:2px 8px;border-radius:4px">
                        <i data-lucide="alert-triangle" style="width:11px;height:11px;display:inline"></i>
                        <span id="import-error-count"></span> baris bermasalah
                    </div>
                </div>
                <div style="max-height:220px;overflow-y:auto;border-radius:var(--radius-sm);border:1px solid var(--border)">
                    <table style="width:100%;border-collapse:collapse;font-size:12px">
                        <thead style="position:sticky;top:0;background:var(--surface)">
                            <tr>
                                <th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">#</th>
                                <th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">Nama</th>
                                <th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">NIM</th>
                                <th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">Universitas</th>
                                <th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">Prodi</th>
                                <th style="padding:8px 12px;text-align:center;font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">Status</th>
                            </tr>
                        </thead>
                        <tbody id="import-preview-body"></tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="modal-ftr">
            <button onclick="closeImportModal()" class="btn-ghost" style="flex:1;justify-content:center">Batal</button>
            <button onclick="handleImportSubmit()" id="import-submit-btn" class="btn-primary" style="flex:1;justify-content:center" disabled>
                <i data-lucide="upload" style="width:14px;height:14px"></i>
                <span id="import-submit-label">Pilih file dulu</span>
            </button>
        </div>

    </div>
</div>