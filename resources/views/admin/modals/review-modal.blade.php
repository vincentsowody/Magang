<div id="review-modal" class="modal-backdrop">
    <div class="modal-box" style="max-width:520px;width:100%;">

        {{-- Header --}}
        <div class="modal-hdr" style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:11px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="file-edit" style="width:18px;height:18px;color:#2563EB;"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;color:#0F172A;line-height:1.2;">Edit Status Pelamar</div>
                    <div style="font-size:11.5px;color:#64748B;margin-top:3px;">
                        Peserta: <strong id="edit-subtitle" style="color:#0F172A;">—</strong>
                    </div>
                    <input type="hidden" id="edit-id">
                </div>
            </div>
            <button onclick="closeReviewModal()"
                style="width:32px;height:32px;border-radius:8px;background:#F8FAFC;border:1px solid #E2E8F0;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;color:#64748B;flex-shrink:0;"
                onmouseover="this.style.background='#FEF2F2';this.style.color='#DC2626';this.style.borderColor='#FECACA'"
                onmouseout="this.style.background='#F8FAFC';this.style.color='#64748B';this.style.borderColor='#E2E8F0'">
                <i data-lucide="x" style="width:14px;height:14px;"></i>
            </button>
        </div>

        <div class="modal-body" style="display:flex;flex-direction:column;gap:18px;padding:20px 22px;">

            {{-- Status Selector --}}
            <div>
                <label style="font-size:12px;font-weight:600;color:#475569;display:block;margin-bottom:8px;">
                    Keputusan Akhir <span style="color:#DC2626;">*</span>
                </label>
                {{-- Custom status cards instead of plain select --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:8px;" id="status-card-row">
                    <label onclick="setReviewStatus('pending')" id="sc-pending"
                        style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px 8px;border:2px solid #FDE68A;border-radius:10px;background:#FFFBEB;cursor:pointer;transition:all .15s;text-align:center;">
                        <i data-lucide="clock" style="width:18px;height:18px;color:#D97706;"></i>
                        <span style="font-size:11.5px;font-weight:700;color:#D97706;">Menunggu</span>
                    </label>
                    <label onclick="setReviewStatus('accepted')" id="sc-accepted"
                        style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px 8px;border:2px solid #E2E8F0;border-radius:10px;background:#F8FAFC;cursor:pointer;transition:all .15s;text-align:center;">
                        <i data-lucide="check-circle-2" style="width:18px;height:18px;color:#94A3B8;"></i>
                        <span style="font-size:11.5px;font-weight:700;color:#94A3B8;">Diterima</span>
                    </label>
                    <label onclick="setReviewStatus('rejected')" id="sc-rejected"
                        style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px 8px;border:2px solid #E2E8F0;border-radius:10px;background:#F8FAFC;cursor:pointer;transition:all .15s;text-align:center;">
                        <i data-lucide="x-circle" style="width:18px;height:18px;color:#94A3B8;"></i>
                        <span style="font-size:11.5px;font-weight:700;color:#94A3B8;">Ditolak</span>
                    </label>
                </div>
                {{-- Hidden select untuk kompatibilitas dengan saveEdit() --}}
                <select id="edit-status" onchange="toggleLoc()" style="display:none;">
                    <option value="pending">Menunggu Review</option>
                    <option value="accepted">Diterima</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            {{-- Divider --}}
            <div style="height:1px;background:#F1F5F9;"></div>

            {{-- Penempatan — tampil jika Diterima --}}
            <div id="edit-loc-box" style="display:none;flex-direction:column;gap:16px;">

                {{-- Label info --}}
                <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:9px;">
                    <i data-lucide="info" style="width:14px;height:14px;color:#2563EB;flex-shrink:0;"></i>
                    <span style="font-size:12px;color:#1D4ED8;font-weight:500;">Isi penempatan dan masa magang untuk kandidat yang diterima.</span>
                </div>

                {{-- Lokasi Kerja --}}
                <div>
                    <label style="font-size:12px;font-weight:600;color:#475569;display:block;margin-bottom:8px;">
                        Lokasi Kerja <span style="color:#DC2626;">*</span>
                    </label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <label id="edit-loc-kantor-card" onclick="selectEditLocation('kantor')"
                            style="display:flex;align-items:center;gap:10px;padding:12px 14px;border:2px solid #E2E8F0;border-radius:10px;background:#F8FAFC;cursor:pointer;transition:all .15s;">
                            <div style="width:36px;height:36px;border-radius:9px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i data-lucide="building-2" style="width:16px;height:16px;color:#2563EB;"></i>
                            </div>
                            <div>
                                <div style="font-size:12.5px;font-weight:700;color:#0F172A;">Head Office</div>
                                <div style="font-size:10.5px;color:#64748B;">Kantor Pusat</div>
                            </div>
                        </label>
                        <label id="edit-loc-terminal-card" onclick="selectEditLocation('terminal')"
                            style="display:flex;align-items:center;gap:10px;padding:12px 14px;border:2px solid #E2E8F0;border-radius:10px;background:#F8FAFC;cursor:pointer;transition:all .15s;">
                            <div style="width:36px;height:36px;border-radius:9px;background:#F0FDFA;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i data-lucide="plane" style="width:16px;height:16px;color:#0D9488;"></i>
                            </div>
                            <div>
                                <div style="font-size:12.5px;font-weight:700;color:#0F172A;">Terminal Ops</div>
                                <div style="font-size:10.5px;color:#64748B;">Operasional Bandara</div>
                            </div>
                        </label>
                    </div>
                    <input type="radio" name="edit-placement" value="kantor"   id="edit-placement-kantor"   style="display:none;">
                    <input type="radio" name="edit-placement" value="terminal" id="edit-placement-terminal" style="display:none;">
                </div>

                {{-- Masa Magang --}}
                <div>
                    <label style="font-size:12px;font-weight:600;color:#475569;display:block;margin-bottom:8px;">
                        Masa Magang <span style="color:#DC2626;">*</span>
                    </label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:10px;">
                        <div>
                            <div style="font-size:10.5px;font-weight:600;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px;">Tanggal Mulai</div>
                            <input type="date" id="edit-start" onchange="updateDurationInfo()"
                                style="width:100%;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;color:#0F172A;padding:8px 12px;font-size:13px;font-family:'Inter',sans-serif;outline:none;transition:all .15s;"
                                onfocus="this.style.borderColor='#2563EB';this.style.boxShadow='0 0 0 3px rgba(37,99,235,.08)'"
                                onblur="this.style.borderColor='#E2E8F0';this.style.boxShadow='none'">
                        </div>
                        <div>
                            <div style="font-size:10.5px;font-weight:600;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px;">Tanggal Selesai</div>
                            <input type="date" id="edit-end" onchange="updateDurationInfo()"
                                style="width:100%;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;color:#0F172A;padding:8px 12px;font-size:13px;font-family:'Inter',sans-serif;outline:none;transition:all .15s;"
                                onfocus="this.style.borderColor='#2563EB';this.style.boxShadow='0 0 0 3px rgba(37,99,235,.08)'"
                                onblur="this.style.borderColor='#E2E8F0';this.style.boxShadow='none'">
                        </div>
                    </div>

                    {{-- Duration badge --}}
                    <div id="edit-duration-badge" style="display:none;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:9px 13px;align-items:center;gap:8px;">
                        <i data-lucide="calendar-check" style="width:14px;height:14px;color:#16A34A;flex-shrink:0;"></i>
                        <span id="edit-duration-info" style="font-size:12px;font-weight:600;color:#16A34A;"></span>
                    </div>

                    {{-- Quick duration buttons --}}
                    <div style="display:flex;align-items:center;gap:6px;margin-top:10px;flex-wrap:wrap;">
                        <span style="font-size:11px;color:#94A3B8;font-weight:500;">Cepat:</span>
                        @foreach([1=>'1 Bulan', 2=>'2 Bulan', 3=>'3 Bulan', 6=>'6 Bulan'] as $m => $label)
                        <button type="button" onclick="setEditDuration({{ $m }})"
                            style="font-size:11.5px;padding:4px 12px;border:1px solid #E2E8F0;border-radius:6px;background:#F8FAFC;color:#475569;cursor:pointer;transition:all .15s;font-family:'Inter',sans-serif;font-weight:600;"
                            onmouseover="this.style.borderColor='#2563EB';this.style.color='#2563EB';this.style.background='#EFF6FF'"
                            onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#475569';this.style.background='#F8FAFC'">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

        {{-- Footer --}}
        <div class="modal-ftr" style="display:flex;gap:8px;">
            <button onclick="closeReviewModal()"
                style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:600;font-family:'Inter',sans-serif;cursor:pointer;transition:all .2s;border:1px solid #E2E8F0;background:#F8FAFC;color:#475569;"
                onmouseover="this.style.background='#F1F5F9'"
                onmouseout="this.style.background='#F8FAFC'">
                Batal
            </button>
            <button onclick="saveEdit()"
                style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:600;font-family:'Inter',sans-serif;cursor:pointer;transition:all .2s;border:none;background:#0F172A;color:#fff;"
                onmouseover="this.style.background='#1E293B'"
                onmouseout="this.style.background='#0F172A'">
                <i data-lucide="save" style="width:14px;height:14px;"></i>
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<script>
// Status card selector untuk review modal
function setReviewStatus(val) {
    // Update hidden select (kompatibel dengan saveEdit() yang sudah ada)
    const sel = document.getElementById('edit-status');
    if (sel) { sel.value = val; sel.dispatchEvent(new Event('change')); }

    // Reset semua card ke state off
    const cards = {
        pending:  { bg: '#F8FAFC', border: '#E2E8F0', color: '#94A3B8' },
        accepted: { bg: '#F8FAFC', border: '#E2E8F0', color: '#94A3B8' },
        rejected: { bg: '#F8FAFC', border: '#E2E8F0', color: '#94A3B8' },
    };
    const active = {
        pending:  { bg: '#FFFBEB', border: '#FDE68A', color: '#D97706' },
        accepted: { bg: '#F0FDF4', border: '#BBF7D0', color: '#16A34A' },
        rejected: { bg: '#FEF2F2', border: '#FECACA', color: '#DC2626' },
    };

    ['pending', 'accepted', 'rejected'].forEach(s => {
        const el = document.getElementById('sc-' + s);
        if (!el) return;
        const st = (s === val) ? active[s] : cards[s];
        el.style.background   = st.bg;
        el.style.borderColor  = st.border;
        const icon = el.querySelector('i');
        const span = el.querySelector('span');
        if (icon) icon.style.color = st.color;
        if (span) span.style.color = st.color;
        if (s === val) {
            el.style.boxShadow = '0 0 0 2px ' + st.border;
            el.style.transform = 'translateY(-1px)';
        } else {
            el.style.boxShadow = 'none';
            el.style.transform = 'none';
        }
    });
}

// Override toggleLoc agar juga sync card visual
const _origToggleLoc = window.toggleLoc;
window.toggleLoc = function() {
    if (typeof _origToggleLoc === 'function') _origToggleLoc();
    const sel = document.getElementById('edit-status');
    if (sel) setReviewStatus(sel.value);
};

// Patch openReviewModal agar sync card saat modal dibuka
const _origOpenReview = window.openReviewModal;
window.openReviewModal = function(id) {
    if (typeof _origOpenReview === 'function') _origOpenReview(id);
    setTimeout(function() {
        const sel = document.getElementById('edit-status');
        if (sel) setReviewStatus(sel.value);
    }, 50);
};
</script>