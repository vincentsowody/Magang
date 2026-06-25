<div id="success-modal" class="modal-backdrop">
    <div class="modal-box" style="max-width:380px;text-align:center">
        <div class="modal-body" style="padding:28px 24px 20px">
            <div style="width:52px;height:52px;border-radius:14px;background:var(--green-dim);border:1px solid rgba(16,185,129,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                <i data-lucide="check-circle-2" style="width:24px;height:24px;color:var(--green)"></i>
            </div>
            <div style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:6px">Registrasi Berhasil!</div>
            <div style="font-size:12px;color:var(--text-secondary);line-height:1.65;margin-bottom:20px">
                Berikan kode akses berikut kepada peserta untuk login ke portal magang.
            </div>

            <div style="position:relative;background:var(--bg);border:1px solid rgba(47,128,237,0.2);border-radius:var(--r-lg);padding:18px 20px 16px">
                <div style="font-size:9.5px;color:var(--text-muted);font-weight:700;letter-spacing:.14em;text-transform:uppercase;margin-bottom:10px">
                    Kode Akses Peserta
                </div>
                <div id="generated-code" style="font-family:'JetBrains Mono',monospace;font-size:26px;font-weight:700;color:var(--accent);letter-spacing:.12em">
                    MAG-XXXX
                </div>
                <button onclick="copyCode()" title="Salin kode" class="btn-icon"
                    style="position:absolute;top:12px;right:12px;width:28px;height:28px">
                    <i data-lucide="copy" style="width:12px;height:12px"></i>
                </button>
                <div id="copy-hint" style="font-size:10px;color:var(--green);margin-top:8px;display:none">
                    <i data-lucide="check" style="width:10px;height:10px;vertical-align:-1px"></i> Tersalin!
                </div>
            </div>
        </div>
        <div class="modal-ftr">
            <button onclick="closeSuccessModal()" class="btn-primary" style="flex:1;justify-content:center">
                <i data-lucide="check" style="width:13px;height:13px"></i> Selesai
            </button>
        </div>
    </div>
</div>
