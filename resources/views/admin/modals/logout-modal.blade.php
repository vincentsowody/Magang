<div id="logout-modal" class="modal-backdrop">
    <div class="modal-box" style="max-width:340px;text-align:center">
        <div class="modal-body" style="padding:28px 24px 20px">
            <div style="width:50px;height:50px;border-radius:14px;background:var(--red-dim);border:1px solid rgba(239,68,68,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                <i data-lucide="log-out" style="width:22px;height:22px;color:var(--red)"></i>
            </div>
            <div style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:6px">Keluar dari Sistem?</div>
            <div style="font-size:12.5px;color:var(--text-secondary);line-height:1.65">
                Sesi Anda akan berakhir. Diperlukan login ulang untuk mengakses dashboard.
            </div>
        </div>
        <div class="modal-ftr">
            <button onclick="closeLogoutModal()" class="btn-ghost" style="flex:1;justify-content:center">Batal</button>
            <button onclick="handleLogout()" style="flex:1;justify-content:center;background:#dc2626;border:none;color:#fff;font-size:12px;font-weight:700;padding:8px 16px;border-radius:var(--r-sm);display:inline-flex;align-items:center;gap:6px;cursor:pointer;transition:background .15s"
                onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                <i data-lucide="log-out" style="width:13px;height:13px"></i> Ya, Keluar
            </button>
        </div>
    </div>
</div>
