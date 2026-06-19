<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Ringkasan statistik untuk laporan
     */
    public function summary()
    {
        $all = Applicant::all();

        $total      = $all->count();
        $pending    = $all->where('status', 'pending')->count();
        $accepted   = $all->where('status', 'accepted')->count();
        $rejected   = $all->where('status', 'rejected')->count();
        $kantor     = $all->where('status', 'accepted')->where('location', 'kantor')->count();
        $terminal   = $all->where('status', 'accepted')->where('location', 'terminal')->count();

        // Kelompokkan per universitas
        $byUniv = $all->groupBy('univ')->map(fn($g) => [
            'total'    => $g->count(),
            'accepted' => $g->where('status', 'accepted')->count(),
            'rejected' => $g->where('status', 'rejected')->count(),
            'pending'  => $g->where('status', 'pending')->count(),
        ])->sortByDesc(fn($v) => $v['total'])->values()->map(fn($v, $k) => array_merge(['univ' => $all->groupBy('univ')->keys()[$k]], $v));

        // Kelompokkan per jurusan
        $byMajor = $all->groupBy('major')->map(fn($g) => [
            'total'    => $g->count(),
            'accepted' => $g->where('status', 'accepted')->count(),
        ])->sortByDesc(fn($v) => $v['total'])->take(10)->values()->map(fn($v, $k) => array_merge(['major' => $all->groupBy('major')->keys()[$k]], $v));

        // Per bulan registrasi
        $byMonth = $all->groupBy(fn($a) => \Carbon\Carbon::parse($a->created_at)->format('Y-m'))
            ->map(fn($g, $month) => [
                'month'    => $month,
                'label'    => \Carbon\Carbon::parse($month)->translatedFormat('M Y'),
                'total'    => $g->count(),
                'accepted' => $g->where('status', 'accepted')->count(),
                'rejected' => $g->where('status', 'rejected')->count(),
            ])->sortKeys()->values();

        return response()->json([
            'overview' => compact('total', 'pending', 'accepted', 'rejected', 'kantor', 'terminal'),
            'by_univ'  => $byUniv->values(),
            'by_major' => $byMajor->values(),
            'by_month' => $byMonth->values(),
        ]);
    }

    /**
     * Export CSV semua kandidat
     */
    public function exportCsv(Request $request)
    {
        $status   = $request->query('status', 'all');
        $location = $request->query('location', 'all');

        $query = Applicant::query();
        if ($status !== 'all')   $query->where('status', $status);
        if ($location !== 'all') $query->where('location', $location);

        $applicants = $query->latest()->get();

        $filename = 'laporan-kandidat-pkl-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate',
        ];

        $callback = function () use ($applicants) {
            $handle = fopen('php://output', 'w');
            // BOM untuk Excel agar bisa baca UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['No', 'Kode Akses', 'Nama', 'NIM', 'Universitas', 'Jurusan', 'Status', 'Penempatan', 'Tanggal Daftar']);

            $statusLabel   = ['pending' => 'Pending', 'accepted' => 'Diterima', 'rejected' => 'Ditolak'];
            $locationLabel = ['kantor' => 'Head Office', 'terminal' => 'Terminal Ops'];

            foreach ($applicants as $i => $a) {
                fputcsv($handle, [
                    $i + 1,
                    $a->code,
                    $a->name,
                    $a->nim,
                    $a->univ,
                    $a->major,
                    $statusLabel[$a->status] ?? $a->status,
                    $a->location ? ($locationLabel[$a->location] ?? $a->location) : '-',
                    \Carbon\Carbon::parse($a->created_at)->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}