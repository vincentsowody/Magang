<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ApplicantNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * GET /api/applicant/{code}/notifications
     * Ambil notifikasi peserta
     */
    public function index(string $code)
    {
        $applicant = Applicant::where('code', strtoupper($code))->first();
        if (!$applicant) {
            return response()->json(['message' => 'Kode tidak valid'], 404);
        }

        $notifications = $applicant->notifications()
            ->latest()
            ->take(30)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'title'      => $n->title,
                'message'    => $n->message,
                'type'       => $n->type,
                'is_read'    => $n->is_read,
                'meta'       => $n->meta,
                'created_at' => $n->created_at->diffForHumans(),
                'time'       => $n->created_at->format('d M Y, H:i'),
            ]);

        $unread = $applicant->unreadNotifications()->count();

        return response()->json([
            'data'          => $notifications,
            'unread_count'  => $unread,
        ]);
    }

    /**
     * POST /api/applicant/{code}/notifications/read-all
     * Tandai semua notifikasi sudah dibaca
     */
    public function readAll(string $code)
    {
        $applicant = Applicant::where('code', strtoupper($code))->first();
        if (!$applicant) {
            return response()->json(['message' => 'Kode tidak valid'], 404);
        }

        $applicant->unreadNotifications()->update(['is_read' => true]);

        return response()->json(['message' => 'Semua notifikasi ditandai dibaca']);
    }

    /**
     * PATCH /api/applicant/{code}/notifications/{id}/read
     * Tandai satu notifikasi dibaca
     */
    public function read(string $code, int $id)
    {
        $applicant = Applicant::where('code', strtoupper($code))->first();
        if (!$applicant) return response()->json(['message' => 'Kode tidak valid'], 404);

        ApplicantNotification::where('id', $id)
            ->where('applicant_id', $applicant->id)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'OK']);
    }
}
