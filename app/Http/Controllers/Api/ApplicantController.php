<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ApplicantNotification;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    // ADMIN: Ambil semua data
    public function index()
    {
        return response()->json(Applicant::latest()->get());
    }

    // ADMIN: Tambah Peserta Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:150',
            'nim'   => 'required|string|max:50',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'univ'  => 'required|string|max:150',
            'major' => 'required|string|max:100',
        ]);

        $lastId = Applicant::max('id') ?? 0;
        $code   = 'MAG-2025-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $applicant = Applicant::create([
            'name'   => $validated['name'],
            'nim'    => $validated['nim'],
            'email'  => $validated['email'] ?? null,
            'phone'  => $validated['phone'] ?? null,
            'univ'   => $validated['univ'],
            'major'  => $validated['major'],
            'code'   => $code,
            'status' => 'pending',
        ]);

        // Notifikasi selamat datang
        ApplicantNotification::create([
            'applicant_id' => $applicant->id,
            'title'        => 'Selamat Datang di Portal PKL InJourney! 🎉',
            'message'      => "Halo {$applicant->name}, pendaftaran Anda telah diterima. Kode akses Anda adalah {$code}. Silakan lengkapi dokumen persyaratan melalui menu Upload Dokumen.",
            'type'         => 'info',
        ]);

        return response()->json([
            'message' => 'Sukses',
            'code'    => $code,
            'data'    => $applicant,
        ]);
    }

    // ADMIN: Update Status, Lokasi Spesifik, dan Masa Magang
    public function update(Request $request, string $id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Validasi form saat status yang dipilih adalah "accepted"
        $rules = ['status' => 'required|string|in:pending,accepted,rejected'];
        
        if ($request->status === 'accepted') {
            $rules['lokasi_penempatan'] = 'required|string|max:255';
            $rules['tanggal_mulai']     = 'required|date';
            $rules['tanggal_selesai']   = 'required|date|after_or_equal:tanggal_mulai';
        }

        $request->validate($rules);

        $oldStatus = $applicant->status;
        $newStatus = $request->status;

        // Persiapkan data yang akan diupdate
        $dataToUpdate = ['status' => $newStatus];

        if ($newStatus === 'accepted') {
            $dataToUpdate['lokasi_penempatan'] = $request->lokasi_penempatan;
            $dataToUpdate['tanggal_mulai']     = $request->tanggal_mulai;
            $dataToUpdate['tanggal_selesai']   = $request->tanggal_selesai;
        } else {
            // Kosongkan data penempatan jika status diubah kembali ke pending/rejected
            $dataToUpdate['lokasi_penempatan'] = null;
            $dataToUpdate['tanggal_mulai']     = null;
            $dataToUpdate['tanggal_selesai']   = null;
        }

        $applicant->update($dataToUpdate);

        // Kirim notifikasi ke peserta jika status berubah
        if ($oldStatus !== $newStatus) {
            // Kita pass model applicant yang sudah terupdate agar data lokasi/tanggal bisa dibaca di notifikasi
            $this->sendStatusNotification($applicant, $newStatus);
        }

        return response()->json(['message' => 'Update berhasil', 'data' => $applicant]);
    }

    // ADMIN: Hapus Data
    public function destroy(string $id)
    {
        $applicant = Applicant::find($id);
        if ($applicant) $applicant->delete();
        return response()->json(['message' => 'Data dihapus']);
    }

    // CLIENT: Cek Login Peserta
    public function checkStatus(Request $request)
    {
        $code      = strtoupper($request->code);
        $applicant = Applicant::where('code', $code)->first();

        if (!$applicant) {
            return response()->json(['message' => 'Kode tidak valid'], 404);
        }

        $unread = $applicant->unreadNotifications()->count();

        return response()->json([
            'status' => 'success',
            'data'   => array_merge($applicant->toArray(), ['unread_count' => $unread]),
        ]);
    }

    // ── Private ──────────────────────────────────────────

    private function sendStatusNotification(Applicant $applicant, string $status): void
    {
        $map = [
            'accepted' => [
                'title'   => '🎉 Selamat! Anda Diterima',
                'message' => "Selamat {$applicant->name}! Lamaran PKL Anda telah **diterima**.\n"
                    . ($applicant->lokasi_penempatan ? "Anda ditempatkan di divisi **{$applicant->lokasi_penempatan}**.\n" : '')
                    . ($applicant->tanggal_mulai && $applicant->tanggal_selesai ? "Masa magang Anda akan berlangsung dari **" . date('d M Y', strtotime($applicant->tanggal_mulai)) . "** hingga **" . date('d M Y', strtotime($applicant->tanggal_selesai)) . "**.\n" : '')
                    . "Segera lengkapi dokumen persyaratan dan pantau info selanjutnya di portal ini.",
            ],
            'rejected' => [
                'title'   => 'Hasil Seleksi PKL',
                'message' => "Halo {$applicant->name}, kami mengucapkan terima kasih atas minat Anda. Setelah melalui proses seleksi, mohon maaf lamaran PKL Anda belum dapat kami terima untuk batch ini. Semoga sukses di kesempatan berikutnya.",
            ],
            'pending' => [
                'title'   => 'Status Dikembalikan ke Review',
                'message' => "Halo {$applicant->name}, status lamaran Anda dikembalikan ke tahap review. Tim HRD akan segera memproses kembali berkas Anda.",
            ],
        ];

        $notif = $map[$status] ?? null;
        if (!$notif) return;

        ApplicantNotification::create([
            'applicant_id' => $applicant->id,
            'title'        => $notif['title'],
            'message'      => $notif['message'],
            'type'         => 'status_change',
            'meta'         => [
                'old_status'        => null,
                'new_status'        => $status,
                'lokasi_penempatan' => $applicant->lokasi_penempatan,
                'tanggal_mulai'     => $applicant->tanggal_mulai,
                'tanggal_selesai'   => $applicant->tanggal_selesai,
            ],
        ]);

        // Kirim email jika ada
        if ($applicant->email) {
            $this->sendEmailNotification($applicant, $notif['title'], $notif['message']);
        }
    }

    private function sendEmailNotification(Applicant $applicant, string $title, string $message): void
    {
        try {
            \Illuminate\Support\Facades\Mail::send(
                'emails.status-notification',
                ['applicant' => $applicant, 'title' => $title, 'message' => $message],
                fn($m) => $m
                    ->to($applicant->email, $applicant->name)
                    ->subject("[InJourney PKL] {$title}")
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Email notif gagal: ' . $e->getMessage());
        }
    }
}