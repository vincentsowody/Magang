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
        $lastId = Applicant::max('id') ?? 0;
        $code   = 'MAG-2025-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $applicant = Applicant::create([
            'name'   => $request->name,
            'nim'    => $request->nim,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'univ'   => $request->univ,
            'major'  => $request->major,
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

    // ADMIN: Update Status & Lokasi — dengan notifikasi otomatis
    public function update(Request $request, string $id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $oldStatus = $applicant->status;
        $newStatus = $request->status;
        $location  = ($newStatus === 'accepted') ? $request->location : null;

        $updateData = [
            'status'             => $newStatus,
            'location'           => $location,
            'lokasi_penempatan'  => $request->lokasi_penempatan,
            'internship_start'   => $request->internship_start,
            'internship_end'     => $request->internship_end,
        ];

        $applicant->update($updateData);

        // Kirim notifikasi ke peserta jika status berubah
        if ($oldStatus !== $newStatus) {
            $this->sendStatusNotification($applicant, $newStatus, $location, $request->internship_start, $request->internship_end);
        }

        return response()->json(['message' => 'Update berhasil', 'data' => $applicant]);
    }

    // ADMIN: Upload Surat Balasan
    public function uploadReplyLetter(Request $request, string $id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'reply_letter' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Hapus file lama jika ada
        if ($applicant->reply_letter_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($applicant->reply_letter_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($applicant->reply_letter_path);
        }

        $file     = $request->file('reply_letter');
        $filename = 'surat-balasan-' . $applicant->code . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('reply-letters', $filename, 'public');

        $applicant->update([
            'reply_letter_path'        => $path,
            'reply_letter_name'        => $file->getClientOriginalName(),
            'reply_letter_uploaded_at' => now(),
        ]);

        // Notifikasi ke peserta
        ApplicantNotification::create([
            'applicant_id' => $applicant->id,
            'title'        => '📄 Surat Balasan Tersedia',
            'message'      => "Halo {$applicant->name}, surat balasan penerimaan magang Anda telah diunggah oleh HRD. Silakan hubungi tim HRD jika membutuhkan salinan fisik.",
            'type'         => 'info',
        ]);

        return response()->json([
            'message'     => 'Surat balasan berhasil diupload',
            'file_name'   => $applicant->reply_letter_name,
            'uploaded_at' => $applicant->reply_letter_uploaded_at,
        ]);
    }

    // ADMIN: Download / URL Surat Balasan
    public function getReplyLetter(string $id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant || !$applicant->reply_letter_path) {
            return response()->json(['message' => 'Surat balasan tidak ditemukan'], 404);
        }

        return response()->json([
            'url'         => \Illuminate\Support\Facades\Storage::disk('public')->url($applicant->reply_letter_path),
            'file_name'   => $applicant->reply_letter_name,
            'uploaded_at' => $applicant->reply_letter_uploaded_at,
        ]);
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

    private function sendStatusNotification(Applicant $applicant, string $status, ?string $location, ?string $startDate = null, ?string $endDate = null): void
    {
        $locationLabel = match ($location) {
            'kantor'   => 'Head Office (Kantor Pusat)',
            'terminal' => 'Terminal Ops (Operasional Bandara)',
            default    => null,
        };

        $map = [
            'accepted' => [
                'title'   => '🎉 Selamat! Anda Diterima',
                'message' => "Selamat {$applicant->name}! Lamaran PKL Anda telah **diterima**."
                    . ($locationLabel ? " Anda akan ditempatkan di **{$locationLabel}**." : '')
                    . ($startDate && $endDate ? " Masa magang: " . \Carbon\Carbon::parse($startDate)->format('d M Y') . " s/d " . \Carbon\Carbon::parse($endDate)->format('d M Y') . "." : '')
                    . " Segera lengkapi dokumen persyaratan dan pantau info selanjutnya di portal ini.",
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
                'old_status' => null,
                'new_status' => $status,
                'location'   => $location,
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