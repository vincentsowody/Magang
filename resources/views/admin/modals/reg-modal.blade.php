<div id="reg-modal" class="modal-backdrop">
    <div class="modal-box" style="max-width:540px;width:100%">

        {{-- Header --}}
        <div class="modal-hdr" style="display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:11px">
                <div class="stat-icon" style="background:var(--accent-light);width:34px;height:34px;margin:0;border-radius:9px">
                    <i data-lucide="user-plus" style="width:15px;height:15px;color:var(--accent)"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary)">Tambah Pelamar</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:1px">Lengkapi data sesuai KTP / KTM peserta</div>
                </div>
            </div>
            <button onclick="closeRegModal()" class="btn-icon">
                <i data-lucide="x" style="width:14px;height:14px"></i>
            </button>
        </div>

        <div class="modal-body">
            <form id="reg-form" onsubmit="handleRegistration(event)">
                <div style="display:flex;flex-direction:column;gap:14px">

                    {{-- Row: Nama + NIM --}}
                    <div class="form-row">
                        <div>
                            <label class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></label>
                            <input type="text" id="reg-name" required class="form-input" placeholder="Budi Santoso">
                        </div>
                        <div>
                            <label class="form-label">NIM / NIS <span style="color:var(--red)">*</span></label>
                            <input type="text" id="reg-nim" required class="form-input" placeholder="Nomor Induk Mahasiswa"
                                style="font-family:'JetBrains Mono',monospace;letter-spacing:.04em">
                        </div>
                    </div>

                    {{-- Universitas --}}
                    <div>
                        <label class="form-label">Universitas / Instansi <span style="color:var(--red)">*</span></label>
                        <div style="position:relative" id="univ-dropdown-wrap">
                            <div class="form-input" id="univ-display" onclick="toggleUnivDropdown()"
                                style="cursor:pointer;display:flex;align-items:center;justify-content:space-between;user-select:none">
                                <span id="univ-display-text" style="color:var(--text-muted)">Pilih Universitas...</span>
                                <svg id="univ-chevron" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="rgba(71,85,105,0.7)" stroke-width="2.5" style="flex-shrink:0;transition:transform .2s">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </div>
                            <input type="hidden" id="reg-univ" required>
                            <div id="univ-panel" class="dd-panel">
                                <div style="padding:8px 8px 4px;border-bottom:1px solid var(--border)">
                                    <div style="position:relative">
                                        <i data-lucide="search" style="position:absolute;left:9px;top:50%;transform:translateY(-50%);width:12px;height:12px;color:var(--text-muted)"></i>
                                        <input id="univ-search" type="text" placeholder="Cari universitas atau instansi..." oninput="filterUnivList()"
                                            class="ctrl-input" style="width:100%;padding-left:28px;font-size:11.5px">
                                    </div>
                                </div>
                                <div id="univ-list" style="max-height:200px;overflow-y:auto;padding:4px"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Program Studi --}}
                    <div>
                        <label class="form-label">Program Studi <span style="color:var(--red)">*</span></label>
                        <div style="position:relative" id="major-dropdown-wrap">
                            <div class="form-input" id="major-display" onclick="toggleMajorDropdown()"
                                style="cursor:pointer;display:flex;align-items:center;justify-content:space-between;user-select:none">
                                <span id="major-display-text" style="color:var(--text-muted)">Pilih universitas terlebih dahulu...</span>
                                <svg id="major-chevron" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="rgba(71,85,105,0.7)" stroke-width="2.5" style="flex-shrink:0;transition:transform .2s">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </div>
                            <input type="hidden" id="reg-major" required>
                            <div id="major-panel" class="dd-panel">
                                <div id="major-list" style="max-height:220px;overflow-y:auto;padding:4px"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Row: Email + HP --}}
                    <div class="form-row">
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" id="reg-email" class="form-input" placeholder="email@contoh.com">
                        </div>
                        <div>
                            <label class="form-label">No. HP / WhatsApp</label>
                            <input type="tel" id="reg-phone" class="form-input" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <div class="modal-ftr">
            <button type="button" onclick="closeRegModal()" class="btn-ghost" style="flex:1;justify-content:center">Batal</button>
            <button type="submit" form="reg-form" id="reg-btn" class="btn-primary" style="flex:1;justify-content:center">
                <i data-lucide="key-round" style="width:13px;height:13px"></i>
                Simpan & Generate Kode
            </button>
        </div>
    </div>
</div>
