<div id="doc-review-modal" class="modal-backdrop">
    <div class="modal-box" style="max-width:600px;width:100%;display:flex;flex-direction:column;max-height:85vh">

        <!-- Header -->
        <div class="modal-hdr" style="display:flex;align-items:flex-start;justify-content:space-between;flex-shrink:0">
            <div>
                <h3 class="text-lg font-bold text-white" style="display:flex;align-items:center;gap:8px">
                    <div style="width:28px;height:28px;border-radius:7px;background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.2);display:flex;align-items:center;justify-content:center">
                        <i data-lucide="file-check-2" style="width:13px;height:13px;color:var(--accent)"></i>
                    </div>
                    Review Dokumen Peserta
                </h3>
                <p style="font-size:12px;color:rgba(100,116,139,0.6);margin-top:3px" id="doc-review-subtitle">—</p>
            </div>
            <button onclick="closeDocReviewModal()" class="btn-icon">
                <i data-lucide="x" style="width:15px;height:15px"></i>
            </button>
        </div>

        <!-- Scrollable body -->
        <div class="modal-body" style="overflow-y:auto;flex:1;padding-top:4px">
            <div id="doc-review-list" style="display:flex;flex-direction:column;gap:8px"></div>
            <div id="doc-review-empty" style="display:none;padding:40px;text-align:center;color:var(--text-muted);font-size:12px">
                <i data-lucide="file-x" style="width:28px;height:28px;opacity:.35;margin:0 auto 10px;display:block"></i>
                Peserta belum mengupload dokumen apapun.
            </div>
        </div>

        <!-- Footer -->
        <div class="modal-ftr" style="flex-shrink:0;display:flex;gap:8px">
            <button onclick="closeDocReviewModal()"
                style="flex:1;padding:10px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;font-size:13px;font-weight:600;color:rgba(148,163,184,.7);cursor:pointer">
                Tutup
            </button>
        </div>

    </div>
</div>
