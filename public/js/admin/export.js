            function toggleExportMenu() {
                const menu = document.getElementById('export-menu');
                menu.classList.toggle('open');
                const close = (e) => { if (!document.getElementById('export-btn').contains(e.target)) { menu.classList.remove('open'); document.removeEventListener('click', close); } };
                setTimeout(() => document.addEventListener('click', close), 100);
            }

            function getFilteredData() {
                const statusF = document.getElementById('export-filter-status')?.value || 'all';
                const locF    = document.getElementById('export-filter-loc')?.value || 'all';
                return applicants.filter(a =>
                    (statusF === 'all' || a.status === statusF) &&
                    (locF === 'all' || a.location === locF)
                );
            }

            function exportCsv() {
                document.getElementById('export-menu').classList.remove('open');
                const status = document.getElementById('export-filter-status')?.value || 'all';
                const loc    = document.getElementById('export-filter-loc')?.value || 'all';
                const url    = `${API_BASE_URL}/report/export-csv?status=${status}&location=${loc}`;
                const link = document.createElement('a');
                link.href = url;
                link.download = `laporan-pkl-${new Date().toISOString().slice(0,10)}.csv`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                setTimeout(() => { if (applicants.length > 0) exportCsvLocal(); }, 1800);
            }

            function exportExcel() {
                document.getElementById('export-menu').classList.remove('open');
                const rows = getFilteredData();
                const sL = { pending:'Pending', accepted:'Diterima', rejected:'Ditolak' };
                const lL = { kantor:'Head Office', terminal:'Terminal Ops' };
                const wsData = [
                    ['No','Kode Akses','Nama Lengkap','NIM','Universitas','Program Studi','Status','Penempatan','Tanggal Daftar']
                ];
                rows.forEach((a,i) => wsData.push([
                    i+1, a.code, a.name, a.nim, a.univ, a.major,
                    sL[a.status]||a.status,
                    a.location ? (lL[a.location]||a.location) : '-',
                    a.created_at ? new Date(a.created_at).toLocaleString('id-ID') : '-'
                ]));
                const wb = XLSX.utils.book_new();
                const ws = XLSX.utils.aoa_to_sheet(wsData);
                // Column widths
                ws['!cols'] = [5,14,24,16,30,26,12,14,20].map(w=>({wch:w}));
                // Style header row
                const hRange = XLSX.utils.decode_range(ws['!ref']);
                for (let C = hRange.s.c; C <= hRange.e.c; C++) {
                    const addr = XLSX.utils.encode_cell({r:0, c:C});
                    if (!ws[addr]) continue;
                    ws[addr].s = { font:{bold:true,color:{rgb:'FFFFFF'}}, fill:{fgColor:{rgb:'1E3A5F'}}, alignment:{horizontal:'center'} };
                }
                XLSX.utils.book_append_sheet(wb, ws, 'Kandidat PKL');
                // Summary sheet
                const total = rows.length;
                const acc = rows.filter(r=>r.status==='accepted').length;
                const rej = rows.filter(r=>r.status==='rejected').length;
                const pen = rows.filter(r=>r.status==='pending').length;
                const sumData = [
                    ['LAPORAN KANDIDAT PKL — INJOURNEY AIRPORTS'],
                    [`Tanggal Cetak: ${new Date().toLocaleString('id-ID')}`],
                    [],
                    ['Ringkasan'],
                    ['Total Kandidat', total],
                    ['Diterima', acc],
                    ['Ditolak', rej],
                    ['Pending', pen],
                    ['Head Office', rows.filter(r=>r.location==='kantor').length],
                    ['Terminal Ops', rows.filter(r=>r.location==='terminal').length],
                    [],
                    ['Acceptance Rate', total > 0 ? Math.round(acc/total*100)+'%' : '0%'],
                ];
                const ws2 = XLSX.utils.aoa_to_sheet(sumData);
                ws2['!cols'] = [{wch:22},{wch:12}];
                XLSX.utils.book_append_sheet(wb, ws2, 'Ringkasan');
                XLSX.writeFile(wb, `laporan-pkl-${new Date().toISOString().slice(0,10)}.xlsx`);
                showToast('Export Berhasil', `${rows.length} data diekspor ke Excel.`, 'success');
            }

            function exportPdf() {
                document.getElementById('export-menu').classList.remove('open');
                if (!window.jspdf) { showToast('Error', 'Library PDF belum siap, coba lagi.', 'error'); return; }
                const { jsPDF } = window.jspdf;
                const rows = getFilteredData();
                const sL = { pending:'Pending', accepted:'Diterima', rejected:'Ditolak' };
                const lL = { kantor:'Head Office', terminal:'Terminal Ops' };
                const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
                const W = doc.internal.pageSize.getWidth();
                // Header
                doc.setFillColor(10, 15, 30);
                doc.rect(0, 0, W, 24, 'F');
                doc.setTextColor(255,255,255);
                doc.setFontSize(14); doc.setFont('helvetica','bold');
                doc.text('LAPORAN KANDIDAT PKL', 14, 11);
                doc.setFontSize(9); doc.setFont('helvetica','normal');
                doc.setTextColor(148,163,184);
                doc.text('InJourney Airports · Rekrutmen Magang Batch 2025', 14, 18);
                doc.text(`Dicetak: ${new Date().toLocaleString('id-ID')}`, W - 14, 18, {align:'right'});
                // Stats row
                const stats = [
                    {label:'Total', val: rows.length, color:[59,130,246]},
                    {label:'Diterima', val: rows.filter(r=>r.status==='accepted').length, color:[34,197,94]},
                    {label:'Pending', val: rows.filter(r=>r.status==='pending').length, color:[245,158,11]},
                    {label:'Ditolak', val: rows.filter(r=>r.status==='rejected').length, color:[239,68,68]},
                ];
                stats.forEach((s,i) => {
                    const x = 14 + i * 60;
                    doc.setFillColor(26,36,58); doc.roundedRect(x, 28, 54, 16, 2, 2, 'F');
                    doc.setTextColor(...s.color); doc.setFontSize(14); doc.setFont('helvetica','bold');
                    doc.text(String(s.val), x + 27, 38, {align:'center'});
                    doc.setTextColor(148,163,184); doc.setFontSize(7); doc.setFont('helvetica','normal');
                    doc.text(s.label.toUpperCase(), x + 27, 42, {align:'center'});
                });
                // Table
                const tableData = rows.map((a,i) => [
                    i+1, a.code, a.name, a.nim,
                    a.univ.length > 28 ? a.univ.slice(0,25)+'...' : a.univ,
                    a.major.length > 22 ? a.major.slice(0,19)+'...' : a.major,
                    sL[a.status]||a.status,
                    a.location ? (lL[a.location]||a.location) : '-',
                ]);
                doc.autoTable({
                    startY: 48,
                    head: [['No','Kode','Nama','NIM','Universitas','Jurusan','Status','Lokasi']],
                    body: tableData,
                    theme: 'grid',
                    styles: { fontSize: 8, cellPadding: 3, textColor: [148,163,184], lineColor: [30,42,60], lineWidth: 0.3 },
                    headStyles: { fillColor: [17,24,39], textColor: [255,255,255], fontStyle: 'bold', fontSize: 8 },
                    alternateRowStyles: { fillColor: [14,20,34] },
                    bodyStyles: { fillColor: [10,15,30] },
                    columnStyles: {
                        0:{cellWidth:8,halign:'center'},
                        1:{cellWidth:22,font:'courier'},
                        2:{cellWidth:34,textColor:[241,245,249]},
                        3:{cellWidth:22,font:'courier'},
                        4:{cellWidth:44},
                        5:{cellWidth:36},
                        6:{cellWidth:16,halign:'center'},
                        7:{cellWidth:22,halign:'center'},
                    },
                    didDrawCell: (data) => {
                        if (data.section === 'body' && data.column.index === 6) {
                            const v = data.cell.raw;
                            if (v==='Diterima') { doc.setTextColor(34,197,94); doc.text(v, data.cell.x+data.cell.width/2, data.cell.y+data.cell.height/2+0.5, {align:'center'}); doc.setTextColor(148,163,184); }
                            else if (v==='Ditolak') { doc.setTextColor(239,68,68); doc.text(v, data.cell.x+data.cell.width/2, data.cell.y+data.cell.height/2+0.5, {align:'center'}); doc.setTextColor(148,163,184); }
                        }
                    },
                    margin: { left: 14, right: 14 },
                });
                // Footer
                const pages = doc.internal.getNumberOfPages();
                for (let i = 1; i <= pages; i++) {
                    doc.setPage(i);
                    doc.setFontSize(7); doc.setTextColor(100,116,139);
                    doc.text(`Halaman ${i} dari ${pages}`, W/2, doc.internal.pageSize.getHeight()-6, {align:'center'});
                    doc.text('RAHASIA — Dokumen Internal HRD InJourney Airports', 14, doc.internal.pageSize.getHeight()-6);
                }
                doc.save(`laporan-pkl-${new Date().toISOString().slice(0,10)}.pdf`);
                showToast('Export Berhasil', `PDF berhasil dibuat (${rows.length} data).`, 'success');
            }

            function exportCsvLocal(statusFilter, locFilter) {
                let rows = applicants;
                if (statusFilter !== 'all') rows = rows.filter(a => a.status === statusFilter);
                if (locFilter !== 'all') rows = rows.filter(a => a.location === locFilter);

                const statusLabel = { pending: 'Pending', accepted: 'Diterima', rejected: 'Ditolak' };
                const locLabel    = { kantor: 'Head Office', terminal: 'Terminal Ops' };

                const header = ['No', 'Kode Akses', 'Nama', 'NIM', 'Universitas', 'Jurusan', 'Status', 'Penempatan', 'Tanggal Daftar'];
                const csvRows = [
    header,
    ...rows.map(row => [
        row.id,
        row.name,
        row.nim
    ])
];

                const csv = '\uFEFF' + csvRows.map(r => r.map(v => `"${String(v).replace(/"/g, '""')}"`).join(',')).join('\n');
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const url  = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `laporan-kandidat-pkl-${new Date().toISOString().slice(0,10)}.csv`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);

                showToast('Export Berhasil', `${rows.length} data berhasil diekspor.`, 'success');
            }
