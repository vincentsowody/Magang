        <div id="view-dashboard" style="flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:16px">

            <!-- Stat row -->
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
                <div class="stat-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                        <div class="stat-icon" style="background:var(--accent-light)">
                            <i data-lucide="users" style="width:17px;height:17px;color:var(--accent)"></i>
                        </div>
                        <span class="stat-tag" style="background:var(--accent-light);color:var(--accent)">TOTAL</span>
                    </div>
                    <div class="stat-num" id="stat-total">0</div>
                    <div class="stat-label">Total Pendaftar</div>
                    <div class="stat-bar" style="background:var(--accent);opacity:.4;margin-top:14px;height:2px;border-radius:2px"></div>
                </div>
                <div class="stat-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                        <div class="stat-icon" style="background:var(--amber-light)">
                            <i data-lucide="clock" style="width:17px;height:17px;color:var(--amber)"></i>
                        </div>
                        <span class="stat-tag" style="background:var(--amber-light);color:var(--amber)">REVIEW</span>
                    </div>
                    <div class="stat-num" id="stat-pending">0</div>
                    <div class="stat-label">Menunggu Review</div>
                    <div class="stat-bar" style="background:var(--amber);opacity:.4;margin-top:14px;height:2px;border-radius:2px"></div>
                </div>
                <div class="stat-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                        <div class="stat-icon" style="background:var(--purple-light)">
                            <i data-lucide="building-2" style="width:17px;height:17px;color:var(--purple)"></i>
                        </div>
                        <span class="stat-tag" style="background:var(--purple-light);color:var(--purple)">OFFICE</span>
                    </div>
                    <div class="stat-num" id="stat-kantor">0</div>
                    <div class="stat-label">Penempatan Kantor</div>
                    <div class="stat-bar" style="background:var(--purple);opacity:.4;margin-top:14px;height:2px;border-radius:2px"></div>
                </div>
                <div class="stat-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                        <div class="stat-icon" style="background:var(--teal-light)">
                            <i data-lucide="plane" style="width:17px;height:17px;color:var(--teal)"></i>
                        </div>
                        <span class="stat-tag" style="background:var(--teal-light);color:var(--teal)">OPS</span>
                    </div>
                    <div class="stat-num" id="stat-terminal">0</div>
                    <div class="stat-label">Penempatan Terminal</div>
                    <div class="stat-bar" style="background:var(--teal);opacity:.4;margin-top:14px;height:2px;border-radius:2px"></div>
                </div>
            </div>

            <!-- Table panel -->
            <div class="panel" style="flex:1">
                <div class="panel-hdr">
                    <div>
                        <div class="panel-title">Data Kandidat PKL</div>
                        <div class="panel-sub">Kelola pendaftaran peserta magang</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <button onclick="openRegModal()" class="btn-primary" style="font-size:12px;padding:8px 14px">
                            <i data-lucide="user-plus" style="width:13px;height:13px"></i>
                            Tambah Peserta
                        </button>
                        <div style="position:relative">
                            <input type="text" id="searchInput" placeholder="Cari nama / NIM..." class="ctrl-input" style="width:200px;padding-left:32px" oninput="renderTable()">
                            <i data-lucide="search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:var(--text-muted)"></i>
                        </div>
                        <select id="filterStatus" class="ctrl-input" onchange="renderTable()">
                            <option value="all">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="accepted">Diterima</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                        <button class="btn-icon" onclick="loadData()" title="Refresh">
                            <i data-lucide="refresh-cw" style="width:13px;height:13px"></i>
                        </button>
                    </div>
                </div>
                <div style="overflow-x:auto">
                    <table>
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Kode Akses</th>
                                <th>Akademik</th>
                                <th>Status</th>
                                <th>Penempatan</th>
                                <th>Tanggal</th>
                                <th style="text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body"></tbody>
                    </table>
                </div>
                <div id="empty-state" class="empty-state hidden">
                    <div class="empty-icon">
                        <i data-lucide="inbox" style="width:22px;height:22px;color:var(--text-muted)"></i>
                    </div>
                    <div style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:6px">Belum Ada Data</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-bottom:16px">Tambahkan peserta atau muat data simulasi</div>
                    <div style="display:flex;justify-content:center;gap:8px">
                        <button onclick="loadDemoData()" class="btn-ghost" style="font-size:12px">
                            <i data-lucide="database" style="width:12px;height:12px"></i> Simulasi Data
                        </button>
                        <button onclick="openRegModal()" class="btn-primary" style="font-size:12px">
                            <i data-lucide="plus" style="width:12px;height:12px"></i> Tambah Manual
                        </button>
                    </div>
                </div>
                <div style="padding:10px 16px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                    <span style="font-size:11px;color:var(--text-muted)">Data diperbarui secara realtime</span>
                    <span style="font-size:11px;color:var(--text-muted)">InJourney Recruitment v2.0</span>
                </div>
            </div>
        </div>

