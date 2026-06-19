        <div id="view-report" style="flex:1;overflow-y:auto;padding:20px;display:none;flex-direction:column;gap:16px">

            <!-- Report header -->
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px">
                <div>
                    <div style="font-size:15px;font-weight:700;color:var(--text-primary)">Laporan Kandidat PKL</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:3px">Rekap & analisis data peserta magang Batch 2025</div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                    <!-- Filter -->
                    <select id="export-filter-status" class="ctrl-input" style="font-size:11px">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="accepted">Diterima</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                    <select id="export-filter-loc" class="ctrl-input" style="font-size:11px">
                        <option value="all">Semua Lokasi</option>
                        <option value="kantor">Head Office</option>
                        <option value="terminal">Terminal Ops</option>
                    </select>
                    <!-- Export dropdown -->
                    <div style="position:relative">
                        <button class="btn-primary" onclick="toggleExportMenu()" style="font-size:12px;padding:8px 14px" id="export-btn">
                            <i data-lucide="download" style="width:13px;height:13px"></i>
                            Export
                            <i data-lucide="chevron-down" style="width:11px;height:11px;margin-left:-2px"></i>
                        </button>
                        <div class="export-menu" id="export-menu">
                            <div class="export-item" onclick="exportExcel()">
                                <div style="width:26px;height:26px;border-radius:6px;background:rgba(34,197,94,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <i data-lucide="table-2" style="width:13px;height:13px;color:var(--green)"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--text-primary)">Excel (.xlsx)</div>
                                    <div style="font-size:10px;color:var(--text-muted)">Dengan format & warna</div>
                                </div>
                            </div>
                            <div class="export-item" onclick="exportPdf()">
                                <div style="width:26px;height:26px;border-radius:6px;background:rgba(239,68,68,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <i data-lucide="file-text" style="width:13px;height:13px;color:var(--red)"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--text-primary)">PDF Report</div>
                                    <div style="font-size:10px;color:var(--text-muted)">Siap cetak, A4 landscape</div>
                                </div>
                            </div>
                            <div class="export-sep"></div>
                            <div class="export-item" onclick="exportCsv()">
                                <div style="width:26px;height:26px;border-radius:6px;background:var(--accent-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <i data-lucide="file-spreadsheet" style="width:13px;height:13px;color:var(--accent)"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--text-primary)">CSV (Plain)</div>
                                    <div style="font-size:10px;color:var(--text-muted)">Data mentah tanpa format</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn-icon" onclick="loadReport()" title="Refresh" style="width:34px;height:34px">
                        <i data-lucide="refresh-cw" style="width:13px;height:13px"></i>
                    </button>
                </div>
            </div>

            <!-- Overview stat cards (report) -->
            <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:10px">
                <div class="stat-card" style="padding:14px">
                    <div style="font-size:10px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Total</div>
                    <div class="stat-num" id="rep-total" style="font-size:24px">—</div>
                </div>
                <div class="stat-card" style="padding:14px">
                    <div style="font-size:10px;color:var(--amber);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Pending</div>
                    <div class="stat-num" id="rep-pending" style="font-size:24px;color:var(--amber)">—</div>
                </div>
                <div class="stat-card" style="padding:14px">
                    <div style="font-size:10px;color:var(--green);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Diterima</div>
                    <div class="stat-num" id="rep-accepted" style="font-size:24px;color:var(--green)">—</div>
                </div>
                <div class="stat-card" style="padding:14px">
                    <div style="font-size:10px;color:var(--red);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Ditolak</div>
                    <div class="stat-num" id="rep-rejected" style="font-size:24px;color:var(--red)">—</div>
                </div>
                <div class="stat-card" style="padding:14px">
                    <div style="font-size:10px;color:var(--purple);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Head Office</div>
                    <div class="stat-num" id="rep-kantor" style="font-size:24px;color:var(--purple)">—</div>
                </div>
                <div class="stat-card" style="padding:14px">
                    <div style="font-size:10px;color:var(--teal);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Terminal</div>
                    <div class="stat-num" id="rep-terminal" style="font-size:24px;color:var(--teal)">—</div>
                </div>
            </div>

            <!-- Charts row -->
            <div style="display:grid;grid-template-columns:280px 1fr;gap:12px">
                <!-- Donut -->
                <div class="panel" style="padding:16px">
                    <div style="font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:16px">Distribusi Status</div>
                    <div style="display:flex;flex-direction:column;align-items:center;gap:16px">
                        <div style="position:relative;width:140px;height:140px">
                            <svg viewBox="0 0 140 140" width="140" height="140">
                                <circle cx="70" cy="70" r="52" fill="none" stroke="rgba(255,255,255,0.04)" stroke-width="22"/>
                                <circle id="donut-accepted" cx="70" cy="70" r="52" fill="none" stroke="var(--green)" stroke-width="22"
                                    stroke-dasharray="0 327" stroke-dashoffset="82" style="transition:stroke-dasharray .8s ease"/>
                                <circle id="donut-rejected" cx="70" cy="70" r="52" fill="none" stroke="var(--red)" stroke-width="22"
                                    stroke-dasharray="0 327" stroke-dashoffset="82" style="transition:stroke-dasharray .8s ease"/>
                                <circle id="donut-pending" cx="70" cy="70" r="52" fill="none" stroke="var(--amber)" stroke-width="22"
                                    stroke-dasharray="0 327" stroke-dashoffset="82" style="transition:stroke-dasharray .8s ease"/>
                            </svg>
                            <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center">
                                <div id="donut-center-num" style="font-size:22px;font-weight:700;color:var(--text-primary);font-family:'JetBrains Mono',monospace">0</div>
                                <div style="font-size:9px;color:var(--text-muted);font-weight:600">TOTAL</div>
                            </div>
                        </div>
                        <div style="width:100%">
                            <div style="display:flex;align-items:center;gap:8px;padding:7px 0;border-bottom:1px solid var(--border)">
                                <div style="width:8px;height:8px;border-radius:50%;background:var(--green);flex-shrink:0"></div>
                                <div style="flex:1;font-size:12px;color:var(--text-secondary)">Diterima</div>
                                <div id="leg-accepted" style="font-weight:700;color:var(--text-primary);font-family:'JetBrains Mono',monospace;font-size:13px">0</div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;padding:7px 0;border-bottom:1px solid var(--border)">
                                <div style="width:8px;height:8px;border-radius:50%;background:var(--amber);flex-shrink:0"></div>
                                <div style="flex:1;font-size:12px;color:var(--text-secondary)">Pending</div>
                                <div id="leg-pending" style="font-weight:700;color:var(--text-primary);font-family:'JetBrains Mono',monospace;font-size:13px">0</div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;padding:7px 0">
                                <div style="width:8px;height:8px;border-radius:50%;background:var(--red);flex-shrink:0"></div>
                                <div style="flex:1;font-size:12px;color:var(--text-secondary)">Ditolak</div>
                                <div id="leg-rejected" style="font-weight:700;color:var(--text-primary);font-family:'JetBrains Mono',monospace;font-size:13px">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Monthly bar chart -->
                <div class="panel" style="padding:16px">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <div style="font-size:12px;font-weight:600;color:var(--text-primary)">Registrasi per Bulan</div>
                        <div style="display:flex;align-items:center;gap:10px;font-size:10px">
                            <span style="display:flex;align-items:center;gap:4px"><span style="width:8px;height:8px;border-radius:2px;background:var(--accent);display:inline-block"></span>Total</span>
                            <span style="display:flex;align-items:center;gap:4px"><span style="width:8px;height:8px;border-radius:2px;background:var(--green);display:inline-block"></span>Diterima</span>
                        </div>
                    </div>
                    <div id="monthly-chart" style="height:170px;display:flex;align-items:flex-end;gap:6px;padding:0 4px">
                        <div style="color:var(--text-muted);font-size:12px;width:100%;text-align:center;padding-top:60px">Memuat data...</div>
                    </div>
                </div>
            </div>

            <!-- Tables row -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <!-- Per Universitas -->
                <div class="panel">
                    <div class="panel-hdr">
                        <div>
                            <div class="panel-title">Per Universitas</div>
                            <div class="panel-sub" id="univ-count">—</div>
                        </div>
                    </div>
                    <div style="max-height:300px;overflow-y:auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th><th>Universitas</th>
                                    <th style="text-align:center">Total</th>
                                    <th style="text-align:center">✓</th>
                                    <th>Rasio</th>
                                </tr>
                            </thead>
                            <tbody id="univ-tbody">
                                <tr><td colspan="5" class="empty-state" style="padding:24px">Memuat...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Per Jurusan -->
                <div class="panel">
                    <div class="panel-hdr">
                        <div>
                            <div class="panel-title">Top 10 Jurusan</div>
                            <div class="panel-sub">Berdasarkan jumlah pelamar</div>
                        </div>
                    </div>
                    <div style="max-height:300px;overflow-y:auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th><th>Jurusan</th>
                                    <th style="text-align:center">Total</th>
                                    <th style="text-align:center">✓</th>
                                    <th>Proporsi</th>
                                </tr>
                            </thead>
                            <tbody id="major-tbody">
                                <tr><td colspan="5" class="empty-state" style="padding:24px">Memuat...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
