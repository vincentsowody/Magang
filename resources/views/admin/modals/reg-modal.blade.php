        <div id="reg-modal" class="modal-backdrop">
            <div class="modal-box">
                <div class="modal-hdr flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-white">Input Data Pelamar</h3>
                        <p class="text-sm text-slate-400 mt-1">Masukkan data sesuai KTP/KTM Peserta</p>
                    </div>
                    <button onclick="closeRegModal()" class="btn-icon" style="margin-top:2px">
                        <i data-lucide="x" style="width:16px;height:16px"></i>
                    </button>
                </div>
                <div class="modal-body space-y-4">
                    <form id="reg-form" onsubmit="handleRegistration(event)">
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" id="reg-name" required class="form-input" placeholder="Contoh: Budi Santoso">
                            </div>
                            <div>
                                <label class="form-label">NIM</label>
                                <input type="text" id="reg-nim" required class="form-input" placeholder="NIM atau NIS" style="font-family:'DM Mono',monospace">
                            </div>
                            <!-- University searchable dropdown -->
                            <div>
                                <label class="form-label">Universitas</label>
                                <div style="position:relative" id="univ-dropdown-wrap">
                                    <div class="form-input" id="univ-display" onclick="toggleUnivDropdown()"
                                        style="cursor:pointer;display:flex;align-items:center;justify-content:space-between;user-select:none">
                                        <span id="univ-display-text" style="color:rgba(100,116,139,0.5)">Pilih Universitas...</span>
                                        <svg id="univ-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(100,116,139,0.5)" stroke-width="2" style="flex-shrink:0;transition:transform .2s"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                    <input type="hidden" id="reg-univ" required>
                                    <!-- Dropdown panel -->
                                    <div id="univ-panel" style="display:none;position:absolute;top:calc(100% + 6px);left:0;right:0;z-index:200;
                                        background:#0a1628;border:1px solid rgba(0,185,232,0.2);border-radius:12px;
                                        box-shadow:0 20px 40px rgba(0,0,0,0.5);overflow:hidden">
                                        <!-- Search -->
                                        <div style="padding:10px 10px 6px;border-bottom:1px solid rgba(255,255,255,0.06)">
                                            <div style="position:relative">
                                                <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:rgba(100,116,139,0.5)" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                                <input id="univ-search" type="text" placeholder="Cari universitas/instansi..." oninput="filterUnivList()"
                                                    style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:8px;
                                                            padding:8px 10px 8px 32px;font-size:12px;color:#e2e8f0;outline:none;font-family:'DM Sans',sans-serif">
                                            </div>
                                        </div>
                                        <!-- List -->
                                        <div id="univ-list" style="max-height:220px;overflow-y:auto;padding:6px"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Major dropdown (populated based on university) -->
                            <div>
                                <label class="form-label">Program Studi</label>
                                <div style="position:relative" id="major-dropdown-wrap">
                                    <div class="form-input" id="major-display" onclick="toggleMajorDropdown()"
                                        style="cursor:pointer;display:flex;align-items:center;justify-content:space-between;user-select:none">
                                        <span id="major-display-text" style="color:rgba(100,116,139,0.5)">Pilih Instansi dulu...</span>
                                        <svg id="major-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(100,116,139,0.5)" stroke-width="2" style="flex-shrink:0;transition:transform .2s"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                    <input type="hidden" id="reg-major" required>
                                    <div id="major-panel" style="display:none;position:absolute;top:calc(100% + 6px);left:0;right:0;z-index:200;
                                        background:#0a1628;border:1px solid rgba(0,185,232,0.2);border-radius:12px;
                                        box-shadow:0 20px 40px rgba(0,0,0,0.5);overflow:hidden">
                                        <div id="major-list" style="max-height:220px;overflow-y:auto;padding:6px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-ftr">
                    <button type="button" onclick="closeRegModal()" class="flex-1 py-2.5 rounded-10px font-semibold text-sm text-slate-400 transition-all" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;">Batal</button>
                    <button type="submit" form="reg-form" id="reg-btn" class="btn-primary flex-1 justify-center py-2.5" style="border-radius:10px">
                        Simpan & Generate Kode
                    </button>
                </div>
                    </form>
            </div>
        </div>
