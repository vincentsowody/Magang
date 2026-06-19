<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_documents', function (Blueprint $table) {
            $table->id();

            // ─────────────────────────────────────────────────────────────────
            // FIX: applicant_id harus NULLABLE agar dokumen admin (tanpa peserta)
            // bisa disimpan dengan applicant_id = NULL.
            // nullOnDelete() → saat applicant dihapus, dokumennya tetap ada
            //                   dengan applicant_id = NULL (bukan ikut terhapus).
            // ─────────────────────────────────────────────────────────────────
            $table->foreignId('applicant_id')
                  ->nullable()
                  ->constrained('applicants')
                  ->nullOnDelete();

            $table->string('name');                               // Nama tampilan dokumen
            $table->string('file_name');                          // Nama file asli dari client
            $table->string('file_path');                          // Path relatif di storage/app/public
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();  // bytes

            $table->enum('type', ['cv', 'transkrip', 'ktm', 'surat_pengantar', 'lainnya'])
                  ->default('lainnya');

            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            // Kolom notes digunakan ganda:
            //   • Dokumen admin   → menyimpan lokasi ('kantor' | 'terminal')
            //   • Dokumen peserta → menyimpan catatan verifikasi dari admin
            $table->string('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_documents');
    }
};
