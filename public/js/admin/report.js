// ═══════════ LAPORAN ═══════════

async function loadReport() {
    try {
        const res = await authFetch(API_BASE_URL + '/report/summary');
        if (!res.ok) throw new Error('API error');
        const data = await res.json();
        renderReportOverview(data.overview);
        renderDonut(data.overview);
        renderMonthlyChart(data.by_month);
        renderUnivTable(data.by_univ);
        renderMajorTable(data.by_major);
    } catch (e) {
        // Demo fallback: generate dari data applicants yang sudah ada
        if (applicants.length > 0) {
            loadReportFromLocal();
        } else {
            showToast('Info', 'Tidak dapat memuat laporan dari server.', 'error');
        }
    }
}

function loadReportFromLocal() {
    const total = applicants.length;
    const accepted = applicants.filter(a => a.status === 'accepted').length;
    const rejected = applicants.filter(a => a.status === 'rejected').length;
    const pending = applicants.filter(a => a.status === 'pending').length;
    const kantor = applicants.filter(a => a.status === 'accepted' && a.location === 'kantor').length;
    const terminal = applicants.filter(a => a.status === 'accepted' && a.location === 'terminal').length;

    renderReportOverview({ total, accepted, rejected, pending, kantor, terminal });
    renderDonut({ total, accepted, rejected, pending });

    // By univ
    const univMap = {};
    applicants.forEach(a => {
        if (!univMap[a.univ]) univMap[a.univ] = { total: 0, accepted: 0, rejected: 0, pending: 0 };
        univMap[a.univ].total++;
        univMap[a.univ][a.status]++;
    });
    const byUniv = Object.entries(univMap)
        .sort((x, y) => y[1].total - x[1].total)
        .map(([univ, v]) => ({
            univ,
            ...v
        }));

    // By major
    const majMap = {};
    applicants.forEach(a => {
        if (!majMap[a.major]) majMap[a.major] = { total: 0, accepted: 0 };
        majMap[a.major].total++;
        if (a.status === 'accepted') majMap[a.major].accepted++;
    });
    const byMajor = Object.entries(majMap).sort((x, y) => y[1].total - x[1].total).slice(0, 10).map(([major, v]) => ({ major, ...v }));
    renderMajorTable(byMajor);

    // Monthly — group by created_at (date string)
    const monthMap = {};
    applicants.forEach(a => {
        if (!a.created_at) return;
        const d = new Date(a.created_at);
        const key = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
        const label = d.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
        if (!monthMap[key]) monthMap[key] = { month: key, label, total: 0, accepted: 0, rejected: 0 };
        monthMap[key].total++;
        if (a.status === 'accepted') monthMap[key].accepted++;
        if (a.status === 'rejected') monthMap[key].rejected++;
    });
    renderMonthlyChart(Object.values(monthMap).sort((a, b) => a.month.localeCompare(b.month)));
}

function renderReportOverview(ov) {
    const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
    const num = (v) => (v === null || v === undefined) ? 0 : v;
    set('rep-total', num(ov.total));
    set('rep-pending', num(ov.pending));
    set('rep-accepted', num(ov.accepted));
    set('rep-rejected', num(ov.rejected));
    set('rep-kantor', num(ov.kantor));
    set('rep-terminal', num(ov.terminal));
}

function renderDonut(ov) {
    const total = ov.total || 1;
    const accepted = ov.accepted || 0;
    const rejected = ov.rejected || 0;
    const pending = ov.pending || 0;
    const CIRC = 2 * Math.PI * 60; // ~376.99

    const aFrac = accepted / total;
    const rFrac = rejected / total;
    const pFrac = pending / total;

    // Draw segments starting from top (offset = -94 = -CIRC/4)
    const aLen = aFrac * CIRC;
    const rLen = rFrac * CIRC;
    const pLen = pFrac * CIRC;

    const accEl = document.getElementById('donut-accepted');
    const rejEl = document.getElementById('donut-rejected');
    const penEl = document.getElementById('donut-pending');

    if (accEl) { accEl.setAttribute('stroke-dasharray', `${aLen} ${CIRC - aLen}`);
        accEl.setAttribute('stroke-dashoffset', CIRC * 0.25); }
    if (rejEl) { rejEl.setAttribute('stroke-dasharray', `${rLen} ${CIRC - rLen}`);
        rejEl.setAttribute('stroke-dashoffset', CIRC * 0.25 - aLen); }
    if (penEl) { penEl.setAttribute('stroke-dasharray', `${pLen} ${CIRC - pLen}`);
        penEl.setAttribute('stroke-dashoffset', CIRC * 0.25 - aLen - rLen); }

    const ctr = document.getElementById('donut-center-num');
    if (ctr) ctr.textContent = ov.total || 0;

    const legA = document.getElementById('leg-accepted');
    const legP = document.getElementById('leg-pending');
    const legR = document.getElementById('leg-rejected');
    if (legA) legA.textContent = accepted;
    if (legP) legP.textContent = pending;
    if (legR) legR.textContent = rejected;
}

function renderMonthlyChart(byMonth) {
    const container = document.getElementById('monthly-chart');
    if (!container) return;
    if (!byMonth || !byMonth.length) {
        container.innerHTML = '<div style="color:rgba(100,116,139,0.5);font-size:12px;width:100%;text-align:center;padding-top:60px">Belum ada data</div>';
        return;
    }
    const max = Math.max(...byMonth.map(m => m.total), 1);
    container.innerHTML = byMonth.map(m => {
        const pct = Math.round((m.total / max) * 100);
        const aPct = Math.round((m.accepted / m.total) * 100) || 0;
        return `
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;min-width:0">
                        <div style="font-size:10px;color:#94a3b8;font-family:'DM Mono',monospace">${m.total}</div>
                        <div style="width:100%;background:rgba(255,255,255,0.05);border-radius:4px 4px 0 0;position:relative;overflow:hidden" title="${m.label}: ${m.total} kandidat">
                            <div style="height:${Math.max(pct * 1.3, 4)}px;width:100%;background:linear-gradient(180deg,rgba(0,185,232,0.7),rgba(0,185,232,0.2));border-radius:4px 4px 0 0;position:relative">
                                <div style="position:absolute;bottom:0;left:0;right:0;height:${aPct}%;background:rgba(74,222,128,0.4);border-radius:4px 4px 0 0"></div>
                            </div>
                        </div>
                        <div style="font-size:9px;color:rgba(100,116,139,0.6);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;text-align:center">${m.label || m.month}</div>
                    </div>`;
    }).join('');
}

function renderUnivTable(byUniv) {
    const tbody = document.getElementById('univ-tbody');
    const count = document.getElementById('univ-count');
    if (!tbody) return;
    if (count) count.textContent = ((byUniv && byUniv.length) || 0) + ' universitas';
    if (!byUniv || !byUniv.length) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:32px;color:rgba(100,116,139,0.5)">Belum ada data</td></tr>';
        return;
    }
    tbody.innerHTML = byUniv.map((u, i) => {
        const ratio = u.total > 0 ? Math.round((u.accepted / u.total) * 100) : 0;
        return `<tr>
                        <td style="font-size:12px;color:#e2e8f0;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${u.univ}">
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:24px;height:24px;border-radius:6px;background:rgba(0,185,232,0.1);border:1px solid rgba(0,185,232,0.15);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#00b9e8;flex-shrink:0">${(i+1)}</div>
                                <span style="overflow:hidden;text-overflow:ellipsis">${u.univ}</span>
                            </div>
                        </td>
                        <td style="text-align:center;font-weight:700;color:#e2e8f0;font-family:'DM Mono',monospace">${u.total}</td>
                        <td style="text-align:center"><span style="color:#4ade80;font-weight:600;font-family:'DM Mono',monospace">${u.accepted}</span></td>
                        <td style="text-align:center"><span style="color:#f87171;font-weight:600;font-family:'DM Mono',monospace">${u.rejected}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="flex:1;height:5px;border-radius:3px;background:rgba(255,255,255,0.06);overflow:hidden">
                                    <div style="height:100%;width:${ratio}%;background:${ratio >= 50 ? '#4ade80' : '#fbbf24'};border-radius:3px;transition:width 0.6s ease"></div>
                                </div>
                                <span style="font-size:10px;color:#94a3b8;width:28px;text-align:right">${ratio}%</span>
                            </div>
                        </td>
                    </tr>`;
    }).join('');
}

function renderMajorTable(byMajor) {
    const tbody = document.getElementById('major-tbody');
    if (!tbody) return;
    if (!byMajor || !byMajor.length) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:32px;color:rgba(100,116,139,0.5)">Belum ada data</td></tr>';
        return;
    }
    const maxTotal = Math.max(...byMajor.map(m => m.total), 1);
    tbody.innerHTML = byMajor.map((m, i) => {
        const pct = Math.round((m.total / maxTotal) * 100);
        return `<tr>
                        <td style="color:rgba(100,116,139,0.6);font-size:11px;width:28px;font-family:'DM Mono',monospace">${String(i+1).padStart(2,'0')}</td>
                        <td style="font-size:12px;color:#e2e8f0;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${m.major}">${m.major}</td>
                        <td style="text-align:center;font-weight:700;color:#e2e8f0;font-family:'DM Mono',monospace">${m.total}</td>
                        <td style="text-align:center"><span style="color:#4ade80;font-weight:600;font-family:'DM Mono',monospace">${m.accepted}</span></td>
                        <td>
                            <div style="height:5px;border-radius:3px;background:rgba(255,255,255,0.06);overflow:hidden">
                                <div style="height:100%;width:${pct}%;background:linear-gradient(90deg,#00b9e8,rgba(0,185,232,0.4));border-radius:3px;transition:width 0.6s ease"></div>
                            </div>
                        </td>
                    </tr>`;
    }).join('');
}