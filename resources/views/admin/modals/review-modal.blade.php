        <div id="review-modal" class="modal-backdrop">
            <div class="modal-box">
                <div class="modal-hdr flex items-center gap-3">
                    <div class="stat-icon" style="background:rgba(0,185,232,0.1);margin-bottom:0;width:36px;height:36px">
                        <i data-lucide="file-edit" style="width:16px;height:16px;color:#00b9e8"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Review Status</h3>
                        <p class="text-xs text-slate-400">Update status penerimaan peserta</p>
                    </div>
                </div>
                <div class="modal-body space-y-5">
                    <div class="px-4 py-3 rounded-12px" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px">
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mb-1">Peserta</p>
                        <p class="font-semibold text-white" id="edit-subtitle">—</p>
                        <input type="hidden" id="edit-id">
                    </div>

                    <div>
                        <label class="form-label">Keputusan Akhir</label>
                        <select id="edit-status" onchange="toggleLoc()" class="form-input">
                            <option value="pending">⏳ Menunggu Review</option>
                            <option value="accepted">✅ Diterima (Lulus)</option>
                            <option value="rejected">❌ Ditolak</option>
                        </select>
                    </div>

                    <div id="edit-loc-box" class="hidden">
                        <label class="form-label" style="color:#00b9e8">Penempatan Area</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="place-card">
                                <input type="radio" name="placement" value="kantor">
                                <div class="place-card-inner">
                                    <i data-lucide="building-2" style="width:20px;height:20px"></i>
                                    <span class="text-xs font-semibold">Kantor Pusat</span>
                                </div>
                            </label>
                            <label class="place-card">
                                <input type="radio" name="placement" value="terminal">
                                <div class="place-card-inner">
                                    <i data-lucide="plane" style="width:20px;height:20px"></i>
                                    <span class="text-xs font-semibold">Terminal Ops</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-ftr">
                    <button onclick="closeReviewModal()" class="flex-1 py-2.5 font-semibold text-sm text-slate-400" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;">Batal</button>
                    <button onclick="saveEdit()" class="btn-primary flex-1 justify-center py-2.5" style="border-radius:10px">Simpan Perubahan</button>
                </div>
            </div>
        </div>
