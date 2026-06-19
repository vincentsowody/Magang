        <div id="logout-modal" class="modal-backdrop">
            <div class="modal-box" style="max-width:360px">
                <div class="modal-body text-center">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2)">
                        <i data-lucide="log-out" style="width:22px;height:22px;color:#f87171"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Keluar Dashboard?</h3>
                    <p class="text-sm text-slate-400">Sesi Anda akan berakhir. Anda perlu login ulang untuk melanjutkan.</p>
                </div>
                <div class="modal-ftr">
                    <button onclick="closeLogoutModal()" class="flex-1 py-2.5 rounded-10px font-semibold text-sm transition-all" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:#94a3b8;border-radius:10px;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">Batal</button>
                    <button onclick="handleLogout()" class="flex-1 py-2.5 rounded-10px font-semibold text-sm text-white transition-all" style="background:#dc2626;border-radius:10px;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">Ya, Keluar</button>
                </div>
            </div>
        </div>
