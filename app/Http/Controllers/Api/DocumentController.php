<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ApplicantDocument;
use App\Models\ApplicantNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    // ────────────────────────────────────────────────────
    // CLIENT: Ambil semua dokumen milik peserta
    // GET /api/applicant/{code}/documents
    // ────────────────────────────────────────────────────
    public function index(string $code)
    {
        $applicant = Applicant::where('code', strtoupper($code))->first();
        if (!$applicant) {
            return response()->json(['message' => 'Kode tidak valid'], 404);
        }

        $docs = $applicant->documents()->latest()->get()->map(fn($d) => [
            'id'          => $d->id,
            'name'        => $d->name,
            'type'        => $d->type,
            'type_label'  => $d->getTypeLabel(),
            'file_name'   => $d->file_name,
            'file_size'   => $d->file_size_human,
            'mime_type'   => $d->mime_type,
            'status'      => $d->status,
            'notes'       => $d->notes,
            'url'         => $d->file_path ? asset('storage/' . $d->file_path) : null,
            'uploaded_at' => $d->created_at->format('d M Y, H:i'),
        ]);

        return response()->json(['data' => $docs]);
    }

    // ────────────────────────────────────────────────────
    // CLIENT: Upload dokumen baru
    // POST /api/applicant/{code}/documents
    // ────────────────────────────────────────────────────
    public function store(Request $request, string $code)
    {
        $applicant = Applicant::where('code', strtoupper($code))->first();
        if (!$applicant) {
            return response()->json(['message' => 'Kode tidak valid'], 404);
        }

        $validated = $request->validate([
            'file' => 'required|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
            'type' => 'required|in:cv,transkrip,ktm,surat_pengantar,lainnya',
            'name' => 'nullable|string|max:100',
        ]);

        $doc = $this->saveFile($request->file('file'), $applicant->id, [
            'applicant_id' => $applicant->id,
            'type'         => $validated['type'],
            'name'         => $request->name ?: $this->typeLabel($validated['type']),
            'status'       => 'pending',
        ]);

        ApplicantNotification::create([
            'applicant_id' => $applicant->id,
            'title'        => 'Dokumen Berhasil Diupload',
            'message'      => "Dokumen \"{$doc->name}\" berhasil diunggah dan sedang menunggu verifikasi dari tim HRD.",
            'type'         => 'document',
            'meta'         => ['document_id' => $doc->id, 'doc_name' => $doc->name],
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil diupload',
            'data'    => $this->formatDoc($doc),
        ], 201);
    }

    // ────────────────────────────────────────────────────
    // CLIENT: Ganti file dokumen yang sudah ada
    // POST /api/applicant/{code}/documents/{id}/replace
    // ────────────────────────────────────────────────────
    public function replace(Request $request, string $code, int $id)
    {
        $applicant = Applicant::where('code', strtoupper($code))->first();
        if (!$applicant) {
            return response()->json(['message' => 'Kode tidak valid'], 404);
        }

        $doc = ApplicantDocument::where('id', $id)
            ->where('applicant_id', $applicant->id)
            ->first();

        if (!$doc) {
            return response()->json(['message' => 'Dokumen tidak ditemukan'], 404);
        }

        if ($doc->status === 'approved') {
            return response()->json(['message' => 'Dokumen yang sudah disetujui tidak dapat diganti'], 403);
        }

        $request->validate([
            'file' => 'required|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        Storage::disk('public')->delete($doc->file_path);

        $file     = $request->file('file');
        $origName = $file->getClientOriginalName();
        $ext      = $file->getClientOriginalExtension();
        $safeName = Str::slug(pathinfo($origName, PATHINFO_FILENAME)) . '-' . time() . '.' . $ext;
        $path     = $file->storeAs("applicant-docs/{$applicant->id}", $safeName, 'public');

        $doc->update([
            'file_name' => $origName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status'    => 'pending',
            'notes'     => null,
        ]);

        ApplicantNotification::create([
            'applicant_id' => $applicant->id,
            'title'        => 'Dokumen Berhasil Diganti',
            'message'      => "File dokumen \"{$doc->name}\" telah diganti dan kembali menunggu verifikasi HRD.",
            'type'         => 'document',
            'meta'         => ['document_id' => $doc->id],
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil diganti',
            'data'    => $this->formatDoc($doc->fresh()),
        ]);
    }

    // ────────────────────────────────────────────────────
    // CLIENT: Hapus dokumen (hanya status pending)
    // DELETE /api/applicant/{code}/documents/{id}
    // ────────────────────────────────────────────────────
    public function destroy(string $code, int $id)
    {
        $applicant = Applicant::where('code', strtoupper($code))->first();
        if (!$applicant) {
            return response()->json(['message' => 'Kode tidak valid'], 404);
        }

        $doc = ApplicantDocument::where('id', $id)
            ->where('applicant_id', $applicant->id)
            ->first();

        if (!$doc) {
            return response()->json(['message' => 'Dokumen tidak ditemukan'], 404);
        }

        if ($doc->status !== 'pending') {
            return response()->json(['message' => 'Dokumen yang sudah diverifikasi tidak bisa dihapus'], 403);
        }

        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return response()->json(['message' => 'Dokumen dihapus']);
    }

    // ────────────────────────────────────────────────────
    // ADMIN: Upload dokumen lokasi (kantor / terminal)
    // POST /api/admin/documents
    // ────────────────────────────────────────────────────
    public function adminStore(Request $request)
    {
        Log::info('[ADMIN UPLOAD] Request diterima', [
            'has_file'    => $request->hasFile('file'),
            'inputs'      => $request->except('file'),
            'content_type'=> $request->header('Content-Type'),
        ]);

        try {
            $validated = $request->validate([
                'file'     => 'required|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip',
                'name'     => 'required|string|max:150',
                'location' => 'required|in:kantor,terminal',
                'type'     => 'nullable|string|max:20',
            ]);

            Log::info('[ADMIN UPLOAD] Validasi lolos', ['name' => $validated['name'], 'location' => $validated['location']]);

            $file     = $request->file('file');
            $origName = $file->getClientOriginalName();
            $ext      = $file->getClientOriginalExtension();
            $safeName = Str::slug(pathinfo($origName, PATHINFO_FILENAME)) . '-' . time() . '.' . $ext;

            Log::info('[ADMIN UPLOAD] Menyimpan file', [
                'original_name' => $origName,
                'safe_name'     => $safeName,
                'size'          => $file->getSize(),
                'mime'          => $file->getMimeType(),
                'disk_path'     => "admin-docs/{$request->location}",
            ]);

            $path = $file->storeAs(
                "admin-docs/{$request->location}",
                $safeName,
                'public'
            );

            if (!$path) {
                Log::error('[ADMIN UPLOAD] storeAs() mengembalikan false — periksa permission folder storage/app/public');
                return response()->json(['error' => 'Gagal menyimpan file ke storage. Pastikan storage:link sudah dijalankan dan folder dapat ditulis.'], 500);
            }

            Log::info('[ADMIN UPLOAD] File tersimpan di', ['path' => $path]);

            // FIX UTAMA: applicant_id di-set NULL secara eksplisit
            // (kolom sudah nullable di migration yang benar)
            $doc = ApplicantDocument::create([
                'applicant_id' => null,          // ← dokumen milik admin, bukan peserta
                'name'         => $request->name,
                'file_name'    => $origName,
                'file_path'    => $path,
                'mime_type'    => $file->getMimeType(),
                'file_size'    => $file->getSize(),
                'type'         => 'lainnya',
                'status'       => 'approved',
                'notes'        => $request->location, // simpan lokasi di kolom notes
            ]);

            Log::info('[ADMIN UPLOAD] Record berhasil disimpan ke DB', ['id' => $doc->id]);

            $url = asset('storage/' . $path);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload',
                'data'    => [
                    'id'          => $doc->id,
                    'name'        => $doc->name,
                    'file_name'   => $doc->file_name,
                    'file_path'   => $doc->file_path,
                    'file_size'   => $doc->file_size_human,
                    'mime_type'   => $doc->mime_type,
                    'location'    => $doc->notes,
                    'status'      => $doc->status,
                    'url'         => $url,
                    'uploaded_at' => $doc->created_at->format('d M Y, H:i'),
                ],
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('[ADMIN UPLOAD] Validasi gagal', ['errors' => $e->errors()]);
            return response()->json([
                'error'   => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {
            Log::error('[ADMIN UPLOAD] Exception tidak terduga', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan server: ' . $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => basename($e->getFile()),
            ], 500);
        }
    }

    // ────────────────────────────────────────────────────
    // ADMIN: List dokumen admin (kantor & terminal)
    // GET /api/admin/documents
    // ────────────────────────────────────────────────────
    public function adminIndex(Request $request)
    {
        // FIX: query dokumen admin (applicant_id IS NULL),
        // bukan dokumen peserta (applicant_id IS NOT NULL)
        $query = ApplicantDocument::whereNull('applicant_id')->latest();

        // Filter opsional berdasarkan lokasi (kolom notes menyimpan 'kantor'/'terminal')
        if ($request->location && in_array($request->location, ['kantor', 'terminal'])) {
            $query->where('notes', $request->location);
        }

        $docs = $query->get()->map(function ($d) {
            return [
                'id'          => $d->id,
                'name'        => $d->name,
                'file_name'   => $d->file_name,
                'file_path'   => $d->file_path,
                'file_size'   => $d->file_size_human,
                'mime_type'   => $d->mime_type,
                'location'    => $d->notes,          // 'kantor' atau 'terminal'
                'status'      => $d->status,
                'url'         => $d->file_path ? asset('storage/' . $d->file_path) : null,
                'uploaded_at' => $d->created_at->format('d M Y, H:i'),
            ];
        });

        // Kelompokkan per lokasi agar frontend mudah mengonsumsi
        $grouped = [
            'kantor'   => $docs->where('location', 'kantor')->values(),
            'terminal' => $docs->where('location', 'terminal')->values(),
        ];

        return response()->json([
            'success' => true,
            'data'    => $grouped,
            'total'   => $docs->count(),
        ]);
    }

    // ────────────────────────────────────────────────────
    // ADMIN: Hapus dokumen admin
    // DELETE /api/admin/documents/{id}
    // ────────────────────────────────────────────────────
    public function adminDestroy(int $id)
    {
        $doc = ApplicantDocument::whereNull('applicant_id')->find($id);
        if (!$doc) {
            return response()->json(['message' => 'Dokumen tidak ditemukan'], 404);
        }

        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        Log::info('[ADMIN DELETE] Dokumen dihapus', ['id' => $id]);

        return response()->json(['message' => 'Dokumen dihapus', 'success' => true]);
    }

    // ────────────────────────────────────────────────────
    // ADMIN: List dokumen satu peserta untuk review
    // GET /api/admin/applicants/{id}/documents
    // ────────────────────────────────────────────────────
    public function applicantDocuments(Request $request, int $id)
    {
        $docs = ApplicantDocument::with('applicant')
            ->where('applicant_id', $id)
            ->latest()
            ->get()
            ->map(fn($d) => [
                'id'          => $d->id,
                'name'        => $d->name,
                'type'        => $d->type,
                'type_label'  => $d->getTypeLabel(),
                'file_name'   => $d->file_name,
                'file_size'   => $d->file_size_human,
                'mime_type'   => $d->mime_type,
                'status'      => $d->status,
                'notes'       => $d->notes,
                'url'         => $d->file_path ? asset('storage/' . $d->file_path) : null,
                'uploaded_at' => $d->created_at->format('d M Y, H:i'),
            ]);

        return response()->json(['data' => $docs]);
    }

    // ────────────────────────────────────────────────────
    // ADMIN: Verifikasi dokumen peserta
    // PATCH /api/admin/documents/{id}/verify
    // ────────────────────────────────────────────────────
    public function verify(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'notes'  => 'nullable|string|max:255',
        ]);

        $doc = ApplicantDocument::with('applicant')->whereNotNull('applicant_id')->findOrFail($id);
        $doc->update(['status' => $request->status, 'notes' => $request->notes ?? null]);

        // Hanya kirim notifikasi untuk approved/rejected
        if (in_array($request->status, ['approved', 'rejected'])) {
            $label   = $request->status === 'approved' ? 'disetujui ✅' : 'ditolak ❌';
            $message = $request->status === 'approved'
                ? "Dokumen \"{$doc->name}\" Anda telah diverifikasi dan disetujui oleh tim HRD."
                : "Dokumen \"{$doc->name}\" Anda ditolak. Catatan: " . ($request->notes ?: 'Harap unggah ulang dokumen yang sesuai.');

            ApplicantNotification::create([
                'applicant_id' => $doc->applicant_id,
                'title'        => "Dokumen {$label}",
                'message'      => $message,
                'type'         => 'document',
                'meta'         => ['document_id' => $doc->id, 'new_status' => $request->status],
            ]);
        }

        return response()->json(['message' => 'Verifikasi berhasil', 'data' => $doc]);
    }

    // ────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ────────────────────────────────────────────────────
    private function saveFile($file, int $applicantId, array $attrs): ApplicantDocument
    {
        $origName = $file->getClientOriginalName();
        $ext      = $file->getClientOriginalExtension();
        $safeName = Str::slug(pathinfo($origName, PATHINFO_FILENAME)) . '-' . time() . '.' . $ext;
        $path     = $file->storeAs("applicant-docs/{$applicantId}", $safeName, 'public');

        return ApplicantDocument::create(array_merge($attrs, [
            'file_name' => $origName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]));
    }

    private function formatDoc(ApplicantDocument $d): array
    {
        return [
            'id'          => $d->id,
            'name'        => $d->name,
            'type'        => $d->type,
            'type_label'  => $d->getTypeLabel(),
            'file_name'   => $d->file_name,
            'file_size'   => $d->file_size_human,
            'status'      => $d->status,
            'notes'       => $d->notes,
            'url'         => $d->file_path ? asset('storage/' . $d->file_path) : null,
            'uploaded_at' => $d->updated_at->format('d M Y, H:i'),
        ];
    }

    private function typeLabel(string $type): string
    {
        return match($type) {
            'cv'              => 'Curriculum Vitae',
            'transkrip'       => 'Transkrip Nilai',
            'ktm'             => 'Kartu Tanda Mahasiswa',
            'surat_pengantar' => 'Surat Pengantar Kampus',
            default           => 'Dokumen Lainnya',
        };
    }
}