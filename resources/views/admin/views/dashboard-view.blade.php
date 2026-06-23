{{-- ══ VIEW: DASHBOARD ══ --}}
        <div id="view-dashboard" style="flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:16px">

            {{-- Stat Cards --}}
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px">

                <div class="stat-card">
                    <div class="stat-icon" style="background:var(--accent-light)">
                        <i data-lucide="users" style="width:16px;height:16px;color:var(--accent)"></i>
                    </div>
                    <div class="stat-num" id="stat-total">0</div>
                    <div class="stat-label">Total Pelamar Masuk</div>
                    <div class="stat-tag" style="background:var(--accent-light);color:var(--accent);margin-top:8px;display:inline-block">Total</div>
                    <div class="stat-bar" style="background:linear-gradient(90deg,var(--accent),transparent)"></div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background:var(--amber-light)">
                        <i data-lucide="clock" style="width:16px;height:16px;color:var(--amber)"></i>
                    </div>
                    <div class="stat-num" id="stat-pending">0</div>
                    <div class="stat-label">Menunggu Review</div>
                    <div class="stat-tag" style="background:var(--amber-light);color:var(--amber);margin-top:8px;display:inline-block">Pending</div>
                    <div class="stat-bar" style="background:linear-gradient(90deg,var(--amber),transparent)"></div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background:var(--green-light)">
                        <i data-lucide="building-2" style="width:16px;height:16px;color:var(--green)"></i>
                    </div>
                    <div class="stat-num" id="stat-kantor">0</div>
                    <div class="stat-label">Diterima (Kantor)</div>
                    <div class="stat-tag" style="background:var(--green-light);color:var(--green);margin-top:8px;display:inline-block">Head Office</div>
                    <div class="stat-bar" style="background:linear-gradient(90deg,var(--green),transparent)"></div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background:var(--teal-light)">
                        <i data-lucide="plane" style="width:16px;height:16px;color:var(--teal)"></i>
                    </div>
                    <div class="stat-num" id="stat-terminal">0</div>
                    <div class="stat-label">Diterima (Terminal)</div>
                    <div class="stat-tag" style="background:var(--teal-light);color:var(--teal);margin-top:8px;display:inline-block">Terminal Ops</div>
                    <div class="stat-bar" style="background:linear-gradient(90deg,var(--teal),transparent)"></div>
                </div>

            </div>

            {{-- Welcome Banner --}}
            <div class="panel" style="padding:28px 32px;background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 100%);border-color:rgba(59,130,246,0.2);position:relative;overflow:hidden">
                <div style="position:absolute;right:-20px;top:-20px;opacity:0.05">
                    <i data-lucide="globe-2" style="width:180px;height:180px"></i>
                </div>
                <div style="position:relative;z-index:1">
                    <div style="font-size:18px;font-weight:700;color:var(--text-primary);margin-bottom:8px">Selamat Datang di Portal Admin</div>
                    <div style="font-size:13px;color:var(--text-secondary);line-height:1.7;max-width:600px">
                        Silakan gunakan menu <strong style="color:var(--text-primary)">Kandidat</strong> untuk verifikasi status pelamar,
                        dan <strong style="color:var(--text-primary)">Dokumen</strong> untuk mengecek kelengkapan berkas magang.
                    </div>
                    <button onclick="switchView('candidates')" class="btn-primary" style="margin-top:18px;padding:9px 20px;font-size:13px">
                        <i data-lucide="users" style="width:14px;height:14px"></i>
                        Mulai Review Pelamar
                    </button>
                </div>
            </div>

        </div>

        {{-- ══ VIEW: KANDIDAT ══ --}}
        <div id="view-candidates" style="flex:1;overflow-y:auto;padding:20px;display:none;flex-direction:column;gap:14px;min-height:0">

            {{-- Toolbar --}}
            <div class="panel" style="padding:14px 16px;display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="position:relative;flex:1;min-width:200px">
                    <i data-lucide="search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text-muted)"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama atau NIM..." onkeyup="renderTable()"
                        style="width:100%;padding:8px 10px 8px 32px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-sm);font-size:12px;color:var(--text-primary);outline:none;font-family:'Inter',sans-serif">
                </div>
                <select id="filterStatus" onchange="renderTable()"
                    style="padding:8px 12px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-sm);font-size:12px;color:var(--text-secondary);outline:none;cursor:pointer">
                    <option value="all">Semua Status</option>
                    <option value="pending">Menunggu Review</option>
                    <option value="accepted">Diterima</option>
                    <option value="rejected">Ditolak</option>
                </select>
                <button id="btn-bulk-delete" onclick="bulkDeleteApps()" class="btn-ghost" style="padding:8px 14px;font-size:12px;white-space:nowrap;display:none;border-color:#f87171;color:#f87171">
                    <i data-lucide="trash-2" style="width:13px;height:13px"></i> Hapus Terpilih (<span id="bulk-count">0</span>)
                </button>
                <button onclick="openImportModal()" class="btn-ghost" style="padding:8px 14px;font-size:12px;white-space:nowrap">
                    <i data-lucide="file-spreadsheet" style="width:13px;height:13px"></i> Import Excel
                </button>
                <button onclick="openRegModal()" class="btn-primary" style="padding:8px 14px;font-size:12px;white-space:nowrap">
                    <i data-lucide="user-plus" style="width:13px;height:13px"></i> Tambah Pelamar
                </button>
            </div>

            {{-- Table --}}
            <div class="panel" style="overflow:hidden;flex:1;min-height:0;display:flex;flex-direction:column">
                <div style="overflow-x:auto;overflow-y:auto;flex:1;min-height:0">
                    <table style="width:100%;border-collapse:collapse;white-space:nowrap">
                        <thead>
                            <tr style="border-bottom:1px solid var(--border)">
                                <th style="padding:12px 16px;width:36px;text-align:center">
                                    <input type="checkbox" id="check-all" onchange="toggleCheckAll(this)" style="width:14px;height:14px;cursor:pointer;accent-color:var(--accent)">
                                </th>
                                <th style="padding:12px 16px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);text-align:left">Nama Pelamar</th>
                                <th style="padding:12px 16px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);text-align:left">Kode Akses</th>
                                <th style="padding:12px 16px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);text-align:left">Pendidikan</th>
                                <th style="padding:12px 16px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);text-align:left">Status</th>
                                <th style="padding:12px 16px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);text-align:left">Penempatan</th>
                                <th style="padding:12px 16px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);text-align:left">Tgl. Daftar</th>
                                <th style="padding:12px 16px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body"></tbody>
                    </table>
                </div>
                <div id="empty-state" style="display:none;padding:48px;text-align:center">
                    <i data-lucide="search-x" style="width:32px;height:32px;opacity:.3;margin:0 auto 12px;display:block"></i>
                    <div style="font-size:13px;font-weight:600;color:var(--text-secondary)">Data Tidak Ditemukan</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px">Coba ubah kata kunci atau filter status</div>
                </div>
            </div>

        </div>