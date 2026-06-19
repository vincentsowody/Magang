        <div id="success-modal" class="modal-backdrop">
            <div class="modal-box" style="max-width:400px">
                <div class="modal-body text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2)">
                        <i data-lucide="check-circle-2" style="width:24px;height:24px;color:#4ade80"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Registrasi Berhasil</h3>
                    <p class="text-sm text-slate-400 mb-6">Berikan kode akses ini kepada peserta untuk login ke portal.</p>

                    <div class="relative rounded-14px p-6 text-center" style="background:#020b18;border:1px solid rgba(0,185,232,0.15);border-radius:14px">
                        <p class="text-[10px] text-slate-500 tracking-widest uppercase mb-3">Access Code</p>
                        <p id="generated-code" class="text-3xl font-mono font-bold tracking-widest" style="color:#00b9e8">MAG-XXXX</p>
                        <button onclick="copyCode()" title="Salin" class="absolute top-3 right-3 btn-icon">
                            <i data-lucide="copy" style="width:13px;height:13px"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-ftr">
                    <button onclick="closeSuccessModal()" class="flex-1 py-2.5 rounded-10px font-semibold text-sm text-white" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-radius:10px;">Tutup</button>
                </div>
            </div>
        </div>
