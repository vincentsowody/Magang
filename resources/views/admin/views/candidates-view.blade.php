{{-- ══ VIEW: KANDIDAT ══ --}}
<div id="view-candidates" class="view-area" style="display: none; flex-direction: column; gap: 20px;">

    {{-- Page Header --}}
    <div class="shrink-0" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div>
            <h2 class="section-title" style="font-size:18px;">Manajemen Kandidat</h2>
            <p class="section-sub">Kelola data pelamar, verifikasi status, dan tentukan penempatan divisi.</p>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <button onclick="openImportModal()" class="btn btn-secondary btn-sm">
                <i data-lucide="file-spreadsheet" style="width:14px;height:14px;color:#16A34A;"></i>
                Import Excel
            </button>
            <button onclick="openRegModal()" class="btn btn-dark btn-sm">
                <i data-lucide="user-plus" style="width:14px;height:14px;"></i>
                Tambah Pelamar
            </button>
        </div>
    </div>

    {{-- Mini Stat Strip --}}
    <div class="shrink-0" style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;box-shadow:var(--shadow-xs);">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="users" style="width:16px;height:16px;"></i>
            </div>
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:800;color:var(--text-primary);line-height:1;" id="cand-stat-total">0</div>
                <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-top:2px;">Total</div>
            </div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;box-shadow:var(--shadow-xs);">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--warning-light);color:var(--warning);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="clock" style="width:16px;height:16px;"></i>
            </div>
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:800;color:var(--text-primary);line-height:1;" id="cand-stat-pending">0</div>
                <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-top:2px;">Pending</div>
            </div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;box-shadow:var(--shadow-xs);">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--success-light);color:var(--success);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="check-circle-2" style="width:16px;height:16px;"></i>
            </div>
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:800;color:var(--text-primary);line-height:1;" id="cand-stat-accepted">0</div>
                <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-top:2px;">Diterima</div>
            </div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;box-shadow:var(--shadow-xs);">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--danger-light);color:var(--danger);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="x-circle" style="width:16px;height:16px;"></i>
            </div>
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:800;color:var(--text-primary);line-height:1;" id="cand-stat-rejected">0</div>
                <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-top:2px;">Ditolak</div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="toolbar shrink-0">
        <div class="toolbar-search">
            <i data-lucide="search" class="toolbar-search-icon"></i>
            <input type="text" id="searchInput" placeholder="Cari nama atau NIM..." onkeyup="renderTable()">
        </div>
        <select id="filterStatus" onchange="renderTable()" class="ctrl-input" style="min-width:160px;">
            <option value="all">Semua Status</option>
            <option value="pending">Menunggu Review</option>
            <option value="accepted">Diterima</option>
            <option value="rejected">Ditolak</option>
        </select>
        <div class="toolbar-sep"></div>
        <button id="btn-bulk-delete" onclick="bulkDeleteApps()"
            class="btn btn-danger-ghost btn-sm" style="display:none;">
            <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
            Hapus (<span id="bulk-count">0</span>)
        </button>
    </div>

    {{-- Data Table — overflow-x scroll agar tidak memotong layout --}}
    <div class="shrink-0" style="background:var(--surface);border:1px solid var(--border);border-radius:var(--r-lg);box-shadow:var(--shadow-xs);overflow:hidden;flex:1;min-height:320px;display:flex;flex-direction:column;">
        <div style="overflow-x:auto;flex:1;-webkit-overflow-scrolling:touch;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;min-width:760px;">
                <thead>
                    <tr style="background:var(--surface-muted);border-bottom:1px solid var(--border);">
                        <th style="width:44px;text-align:center;padding:11px 8px 11px 16px;">
                            <input type="checkbox" id="check-all" onchange="toggleCheckAll(this)"
                                style="width:14px;height:14px;cursor:pointer;accent-color:#2563EB;">
                        </th>
                        <th style="padding:11px 14px;font-size:10.5px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.07em;text-align:left;white-space:nowrap;">Kandidat</th>
                        <th style="padding:11px 14px;font-size:10.5px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.07em;text-align:left;white-space:nowrap;">Kode</th>
                        <th style="padding:11px 14px;font-size:10.5px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.07em;text-align:left;white-space:nowrap;">Pendidikan</th>
                        <th style="padding:11px 14px;font-size:10.5px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.07em;text-align:left;white-space:nowrap;">Status</th>
                        <th style="padding:11px 14px;font-size:10.5px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.07em;text-align:left;white-space:nowrap;">Penempatan</th>
                        <th style="padding:11px 14px;font-size:10.5px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.07em;text-align:right;white-space:nowrap;min-width:200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body"></tbody>
            </table>
        </div>

        {{-- Empty State --}}
        <div id="empty-state" style="display:none;padding:48px 24px;text-align:center;flex-direction:column;align-items:center;justify-content:center;flex:1;">
            <div style="width:52px;height:52px;border-radius:14px;background:var(--surface-muted);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:var(--text-muted);">
                <i data-lucide="search-x" style="width:24px;height:24px;"></i>
            </div>
            <div style="font-size:13.5px;font-weight:700;color:var(--text-secondary);margin-bottom:4px;">Data Tidak Ditemukan</div>
            <div style="font-size:12px;color:var(--text-muted);">Coba ubah filter atau kata kunci pencarian Anda.</div>
        </div>
    </div>

</div>