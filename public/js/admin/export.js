function toggleExportMenu() {
    const menu = document.getElementById('export-menu');
    menu.classList.toggle('open');
    const close = (e) => {
        if (!document.getElementById('export-btn').contains(e.target)) {
            menu.classList.remove('open');
            document.removeEventListener('click', close);
        }
    };
    setTimeout(() => document.addEventListener('click', close), 100);
}

// Helper pengganti optional chaining (?.) — di environment Anda
// sebelumnya tanda "?." pernah berubah jadi "? ." (ada spasi),
// yang langsung membuat SELURUH file gagal di-parse browser
// (semua function di file ini jadi "is not defined"). Pakai
// helper biasa supaya aman dari masalah itu.
function getElVal(id, fallback) {
    const el = document.getElementById(id);
    return (el && el.value) ? el.value : fallback;
}

function getFilteredData() {
    const statusF = getElVal('export-filter-status', 'all');
    const locF = getElVal('export-filter-loc', 'all');
    return applicants.filter(a =>
        (statusF === 'all' || a.status === statusF) &&
        (locF === 'all' || a.location === locF)
    );
}

// FIX BUG: link <a> langsung ke URL export-csv tidak bisa membawa
// header Authorization, jadi sejak endpoint ini dilindungi token,
// klik export akan selalu gagal (401). Sekarang diambil lewat
// authFetch() lalu didownload sebagai blob.
async function exportCsv() {
    // FIX BUG: dropdown export sudah diganti jadi CSS hover-based (lihat
    // report-view.blade.php, pakai class "group"/"group-hover"), elemen
    // id="export-menu" sudah tidak ada di HTML lagi. Baris lama ini bikin
    // TypeError (Cannot read properties of null) dan menghentikan fungsi
    // sebelum proses export sempat berjalan -- export CSV selalu gagal diam-diam.
    const status = getElVal('export-filter-status', 'all');
    const loc = getElVal('export-filter-loc', 'all');
    const url = `${API_BASE_URL}/report/export-csv?status=${status}&location=${loc}`;
    try {
        // FIX BUG: authFetch() tidak pernah didefinisikan (ReferenceError),
        // export CSV selalu gagal. Route ini sudah tidak diproteksi
        // auth:sanctum lagi, jadi fetch() biasa sudah cukup.
        const res = await fetch(url);
        if (!res.ok) throw new Error('Export gagal');
        const blob = await res.blob();
        const blobUrl = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = blobUrl;
        link.download = `laporan-pkl-${new Date().toISOString().slice(0,10)}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(blobUrl);
        showToast('Export Berhasil', 'File CSV berhasil diunduh.', 'success');
    } catch (e) {
        if (applicants.length > 0) exportCsvLocal();
        else showToast('Gagal', 'Tidak dapat mengekspor data.', 'error');
    }
}

function exportExcel() {
    // FIX BUG: sama seperti exportCsv() -- elemen "export-menu" sudah tidak
    // ada lagi sejak dropdown export diganti jadi CSS hover-based.
    const rows = getFilteredData();
    const sL = { pending: 'Pending', accepted: 'Diterima', rejected: 'Ditolak' };
    const lL = { kantor: 'Head Office', terminal: 'Terminal Ops' };
    const wsData = [
        ['No', 'Kode Akses', 'Nama Lengkap', 'NIM', 'Universitas', 'Program Studi', 'Status', 'Penempatan', 'Tanggal Daftar']
    ];
    rows.forEach((a, i) => wsData.push([
        i + 1, a.code, a.name, a.nim, a.univ, a.major,
        sL[a.status] || a.status,
        a.location ? (lL[a.location] || a.location) : '-',
        a.created_at ? new Date(a.created_at).toLocaleString('id-ID') : '-'
    ]));
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(wsData);
    // Column widths
    ws['!cols'] = [5, 14, 24, 16, 30, 26, 12, 14, 20].map(w => ({ wch: w }));
    // Style header row
    const hRange = XLSX.utils.decode_range(ws['!ref']);
    for (let C = hRange.s.c; C <= hRange.e.c; C++) {
        const addr = XLSX.utils.encode_cell({ r: 0, c: C });
        if (!ws[addr]) continue;
        ws[addr].s = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '1E3A5F' } }, alignment: { horizontal: 'center' } };
    }
    XLSX.utils.book_append_sheet(wb, ws, 'Kandidat PKL');
    // Summary sheet
    const total = rows.length;
    const acc = rows.filter(r => r.status === 'accepted').length;
    const rej = rows.filter(r => r.status === 'rejected').length;
    const pen = rows.filter(r => r.status === 'pending').length;
    const sumData = [
        ['LAPORAN KANDIDAT PKL — INJOURNEY AIRPORTS'],
        [`Tanggal Cetak: ${new Date().toLocaleString('id-ID')}`],
        [],
        ['Ringkasan'],
        ['Total Kandidat', total],
        ['Diterima', acc],
        ['Ditolak', rej],
        ['Pending', pen],
        ['Head Office', rows.filter(r => r.location === 'kantor').length],
        ['Terminal Ops', rows.filter(r => r.location === 'terminal').length],
        [],
        ['Acceptance Rate', total > 0 ? Math.round(acc / total * 100) + '%' : '0%'],
    ];
    const ws2 = XLSX.utils.aoa_to_sheet(sumData);
    ws2['!cols'] = [{ wch: 22 }, { wch: 12 }];
    XLSX.utils.book_append_sheet(wb, ws2, 'Ringkasan');
    XLSX.writeFile(wb, `laporan-pkl-${new Date().toISOString().slice(0,10)}.xlsx`);
    showToast('Export Berhasil', `${rows.length} data diekspor ke Excel.`, 'success');
}

// ── PDF EXPORT — template rapi ──
// Perbaikan dari versi sebelumnya:
// 1. Header & nomor halaman sekarang konsisten di SETIAP halaman
//    (sebelumnya cuma muncul di halaman pertama, halaman 2+ tampak
//    polos langsung lanjut tabel).
// 2. Kartu statistik dirapikan & dipusatkan, ada border tipis.
// 3. Ditambah blok tanda tangan (Dibuat / Diperiksa / Disetujui)
//    di akhir dokumen — lazim dipakai untuk laporan resmi HRD.
// 4. a.univ / a.major dijaga dengan fallback string kosong supaya
//    tidak crash kalau datanya null.
function exportPdf() {
    // FIX BUG: sama seperti exportCsv()/exportExcel() -- "export-menu"
    // sudah tidak ada lagi sejak dropdown export diganti jadi CSS hover-based.
    if (!window.jspdf) { showToast('Error', 'Library PDF belum siap, coba lagi.', 'error'); return; }
    const { jsPDF } = window.jspdf;
    const rows = getFilteredData();
    const sL = { pending: 'Pending', accepted: 'Diterima', rejected: 'Ditolak' };
    const lL = { kantor: 'Head Office', terminal: 'Terminal Ops' };

    const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
    const W = doc.internal.pageSize.getWidth();
    const H = doc.internal.pageSize.getHeight();
    const MARGIN = 14;
    const printedAt = new Date().toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' });

    // ── Header ringkas, digambar ulang di SETIAP halaman ──
    function drawPageHeader(pageNum, totalPages) {
        doc.setFillColor(10, 15, 30);
        doc.rect(0, 0, W, 20, 'F');
        // Aksen garis cyan tipis di bawah header.
        doc.setFillColor(0, 185, 232);
        doc.rect(0, 20, W, 0.6, 'F');

        doc.setTextColor(255, 255, 255);
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text('LAPORAN KANDIDAT PKL', MARGIN, 9);

        doc.setFontSize(7.5);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(148, 163, 184);
        doc.text('InJourney Airports  ·  Rekrutmen Magang Batch 2025', MARGIN, 15);

        doc.setFontSize(7.5);
        doc.text(`Halaman ${pageNum} / ${totalPages}`, W - MARGIN, 9, { align: 'right' });
        doc.text(`Dicetak ${printedAt}`, W - MARGIN, 15, { align: 'right' });
    }

    // ── Kartu statistik (hanya tampil sekali, di bawah header halaman 1) ──
    function drawStatsRow(top) {
        const stats = [
            { label: 'Total Pelamar', val: rows.length, color: [59, 130, 246] },
            { label: 'Diterima', val: rows.filter(r => r.status === 'accepted').length, color: [34, 197, 94] },
            { label: 'Menunggu Review', val: rows.filter(r => r.status === 'pending').length, color: [245, 158, 11] },
            { label: 'Ditolak', val: rows.filter(r => r.status === 'rejected').length, color: [239, 68, 68] },
        ];
        const usableWidth = W - MARGIN * 2;
        const gap = 6;
        const cardW = (usableWidth - gap * (stats.length - 1)) / stats.length;
        const cardH = 18;

        stats.forEach((s, i) => {
            const x = MARGIN + i * (cardW + gap);
            doc.setFillColor(20, 28, 46);
            doc.setDrawColor(s.color[0], s.color[1], s.color[2]);
            doc.setLineWidth(0.35);
            doc.roundedRect(x, top, cardW, cardH, 2.2, 2.2, 'FD');
            // Bar warna kecil di sisi kiri kartu.
            doc.setFillColor(...s.color);
            doc.roundedRect(x, top, 2.2, cardH, 1, 1, 'F');

            doc.setTextColor(...s.color);
            doc.setFontSize(15);
            doc.setFont('helvetica', 'bold');
            doc.text(String(s.val), x + cardW / 2 + 2, top + 9.5, { align: 'center' });

            doc.setTextColor(148, 163, 184);
            doc.setFontSize(7);
            doc.setFont('helvetica', 'normal');
            doc.text(s.label.toUpperCase(), x + cardW / 2 + 2, top + 14.5, { align: 'center' });
        });
        return top + cardH + 8;
    }

    const truncate = (str, max) => {
        const s = String(str || '').trim();
        return s.length > max ? s.slice(0, max - 1) + '…' : (s || '-');
    };

    drawPageHeader(1, 1); // ditimpa ulang dgn total halaman benar setelah autoTable
    const tableStartY = drawStatsRow(26);

    doc.setFontSize(9);
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(226, 232, 240);
    doc.text('Rincian Data Kandidat', MARGIN, tableStartY - 2.5);

    const tableData = rows.map((a, i) => [
        i + 1,
        a.code || '-',
        truncate(a.name, 28),
        a.nim || '-',
        truncate(a.univ, 30),
        truncate(a.major, 24),
        sL[a.status] || a.status || '-',
        a.location ? (lL[a.location] || a.location) : '-',
    ]);

    doc.autoTable({
        startY: tableStartY,
        head: [
            ['No', 'Kode', 'Nama', 'NIM', 'Universitas', 'Jurusan', 'Status', 'Lokasi']
        ],
        body: tableData,
        theme: 'grid',
        styles: { fontSize: 8, cellPadding: 3, textColor: [148, 163, 184], lineColor: [30, 42, 60], lineWidth: 0.3 },
        headStyles: { fillColor: [17, 24, 39], textColor: [255, 255, 255], fontStyle: 'bold', fontSize: 8 },
        alternateRowStyles: { fillColor: [14, 20, 34] },
        bodyStyles: { fillColor: [10, 15, 30] },
        columnStyles: {
            0: { cellWidth: 8, halign: 'center' },
            1: { cellWidth: 22, font: 'courier' },
            2: { cellWidth: 34, textColor: [241, 245, 249] },
            3: { cellWidth: 22, font: 'courier' },
            4: { cellWidth: 44 },
            5: { cellWidth: 36 },
            6: { cellWidth: 16, halign: 'center' },
            7: { cellWidth: 22, halign: 'center' },
        },
        didDrawCell: (data) => {
            if (data.section === 'body' && data.column.index === 6) {
                const v = data.cell.raw;
                const color = v === 'Diterima' ? [34, 197, 94] : v === 'Ditolak' ? [239, 68, 68] : null;
                if (color) {
                    doc.setTextColor(...color);
                    doc.text(v, data.cell.x + data.cell.width / 2, data.cell.y + data.cell.height / 2 + 0.5, { align: 'center' });
                    doc.setTextColor(148, 163, 184);
                }
            }
        },
        margin: { left: MARGIN, right: MARGIN, top: 26 },
        // Header singkat digambar ulang di setiap halaman baru
        // (kartu statistik & judul tabel cukup sekali di hal. 1).
        didDrawPage: (data) => {
            if (data.pageNumber > 1) drawPageHeader(data.pageNumber, doc.internal.getNumberOfPages());
        },
    });

    // ── Blok tanda tangan di akhir dokumen ──
    let sigY = doc.lastAutoTable.finalY + 16;
    if (sigY > H - 40) {
        doc.addPage();
        sigY = 26;
    }
    const sigCols = ['Dibuat oleh', 'Diperiksa oleh', 'Disetujui oleh'];
    const sigColW = (W - MARGIN * 2) / sigCols.length;
    doc.setFontSize(8.5);
    sigCols.forEach((label, i) => {
        const x = MARGIN + i * sigColW;
        doc.setTextColor(148, 163, 184);
        doc.setFont('helvetica', 'normal');
        doc.text(label, x, sigY);
        doc.setDrawColor(60, 72, 92);
        doc.setLineWidth(0.3);
        doc.line(x, sigY + 18, x + sigColW - 20, sigY + 18);
        doc.setTextColor(100, 116, 139);
        doc.setFontSize(7);
        doc.text('Nama & Tanda Tangan', x, sigY + 22);
        doc.setFontSize(8.5);
    });

    // ── Footer halaman pertama (sebelumnya hilang setelah refactor header) ──
    const totalPages = doc.internal.getNumberOfPages();
    doc.setPage(1);
    drawPageHeader(1, totalPages);
    for (let i = 1; i <= totalPages; i++) {
        doc.setPage(i);
        doc.setFontSize(7);
        doc.setTextColor(100, 116, 139);
        doc.text(`Halaman ${i} dari ${totalPages}`, W / 2, H - 6, { align: 'center' });
        doc.text('RAHASIA — Dokumen Internal HRD InJourney Airports', MARGIN, H - 6);
        doc.text(`Total: ${rows.length} data`, W - MARGIN, H - 6, { align: 'right' });
    }

    doc.save(`laporan-pkl-${new Date().toISOString().slice(0, 10)}.pdf`);
    showToast('Export Berhasil', `PDF berhasil dibuat (${rows.length} data, ${totalPages} halaman).`, 'success');
}

function exportCsvLocal(statusFilter, locFilter) {
    let rows = applicants;
    if (statusFilter !== 'all') rows = rows.filter(a => a.status === statusFilter);
    if (locFilter !== 'all') rows = rows.filter(a => a.location === locFilter);

    const statusLabel = { pending: 'Pending', accepted: 'Diterima', rejected: 'Ditolak' };
    const locLabel = { kantor: 'Head Office', terminal: 'Terminal Ops' };

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
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `laporan-kandidat-pkl-${new Date().toISOString().slice(0,10)}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);

    showToast('Export Berhasil', `${rows.length} data berhasil diekspor.`, 'success');
}