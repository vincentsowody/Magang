<div id="review-modal" class="modal-backdrop">
    <div class="modal-box" style="max-width:560px;width:100%">

        {{-- Header --}}
        <div class="modal-hdr" style="display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:11px">
                <div class="stat-icon" style="background:var(--accent-light);width:34px;height:34px;margin:0;border-radius:9px">
                    <i data-lucide="file-edit" style="width:15px;height:15px;color:var(--accent)"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary)">Review Status</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:1px">Peserta: <span id="edit-subtitle">—</span></div>
                    <input type="hidden" id="edit-id">
                </div>
            </div>
            <button onclick="closeReviewModal()" class="btn-icon">
                <i data-lucide="x" style="width:14px;height:14px"></i>
            </button>
        </div>

        <div class="modal-body" style="display:flex;flex-direction:column;gap:16px">

            {{-- Keputusan Akhir --}}
            <div>
                <label class="form-label">Keputusan Akhir</label>
                <select id="edit-status" onchange="toggleLoc()" class="form-input">
                    <option value="pending">⏳ Menunggu Review</option>
                    <option value="accepted">✅ Diterima (Lulus)</option>
                    <option value="rejected">❌ Ditolak</option>
                </select>
            </div>

            {{-- Tampil jika status = Diterima --}}
            <div id="edit-loc-box" class="hidden" style="display:flex;flex-direction:column;gap:14px">

                {{-- Lokasi Kerja --}}
                <div>
                    <label class="form-label">Lokasi Kerja <span style="color:var(--red)">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <label id="edit-loc-kantor-card" onclick="selectEditLocation('kantor')"
                            style="display:flex;align-items:center;gap:10px;padding:11px 14px;border:2px solid var(--border);border-radius:var(--r-sm);cursor:pointer;transition:all .15s">
                            <div style="width:32px;height:32px;border-radius:8px;background:var(--accent-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i data-lucide="building-2" style="width:14px;height:14px;color:var(--accent)"></i>
                            </div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:var(--text-primary)">Head Office</div>
                                <div style="font-size:10px;color:var(--text-muted)">Kantor Pusat</div>
                            </div>
                        </label>
                        <label id="edit-loc-terminal-card" onclick="selectEditLocation('terminal')"
                            style="display:flex;align-items:center;gap:10px;padding:11px 14px;border:2px solid var(--border);border-radius:var(--r-sm);cursor:pointer;transition:all .15s">
                            <div style="width:32px;height:32px;border-radius:8px;background:var(--teal-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i data-lucide="plane" style="width:14px;height:14px;color:var(--teal)"></i>
                            </div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:var(--text-primary)">Terminal Ops</div>
                                <div style="font-size:10px;color:var(--text-muted)">Operasional Bandara</div>
                            </div>
                        </label>
                    </div>
                    <input type="radio" name="edit-placement" value="kantor"   id="edit-placement-kantor"   style="display:none">
                    <input type="radio" name="edit-placement" value="terminal" id="edit-placement-terminal" style="display:none">
                </div>

                {{-- Masa Magang --}}
                <div>
                    <label class="form-label">Masa Magang <span style="color:var(--red)">*</span></label>
                    <div class="form-row" style="margin-bottom:8px">
                        <div>
                            <div class="form-section-label">Tanggal Mulai</div>
                            <input type="date" id="edit-start" class="form-input" onchange="updateDurationInfo()">
                        </div>
                        <div>
                            <div class="form-section-label">Tanggal Selesai</div>
                            <input type="date" id="edit-end" class="form-input" onchange="updateDurationInfo()">
                        </div>
                    </div>

                    <div id="edit-duration-badge" style="display:none;background:var(--green-light);border:1px solid rgba(34,197,94,0.2);border-radius:var(--r-sm);padding:8px 12px;align-items:center;gap:8px">
                        <i data-lucide="calendar-check" style="width:13px;height:13px;color:var(--green);flex-shrink:0"></i>
                        <span id="edit-duration-info" style="font-size:12px;font-weight:600;color:var(--green)"></span>
                    </div>

                    <div style="display:flex;gap:6px;margin-top:8px;flex-wrap:wrap;align-items:center">
                        <span style="font-size:10px;color:var(--text-muted)">Cepat:</span>
                        @foreach([1=>'1 Bln', 2=>'2 Bln', 3=>'3 Bln', 6=>'6 Bln'] as $m => $label)
                        <button type="button" onclick="setEditDuration({{ $m }})"
                            style="font-size:10.5px;padding:3px 10px;border:1px solid var(--border);border-radius:4px;background:var(--bg);color:var(--text-secondary);cursor:pointer;transition:all .15s;font-family:'Inter',sans-serif"
                            onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-secondary)'">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

        <div class="modal-ftr">
            <button onclick="closeReviewModal()" class="btn-ghost" style="flex:1;justify-content:center">Batal</button>
            <button onclick="saveEdit()" class="btn-primary" style="flex:1;justify-content:center">
                <i data-lucide="save" style="width:13px;height:13px"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</div>
