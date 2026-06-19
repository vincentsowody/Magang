<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantDocument extends Model
{
    use HasFactory;

    // ─────────────────────────────────────────────────────
    // FIX: Pastikan 'applicant_id' ada di $fillable supaya
    // mass-assignment dengan nilai NULL tidak diblokir.
    // ─────────────────────────────────────────────────────
    protected $fillable = [
        'applicant_id',   // nullable — NULL untuk dokumen admin
        'name',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'type',
        'status',
        'notes',
    ];

    protected $casts = [
        'file_size'    => 'integer',
        'applicant_id' => 'integer',
    ];

    // ─────────────────────────────────────────────────────
    // Relasi ke Applicant (nullable, untuk dokumen admin)
    // ─────────────────────────────────────────────────────
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    // ─────────────────────────────────────────────────────
    // Accessor: ukuran file dalam format manusia
    // ─────────────────────────────────────────────────────
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes < 1024)        return $bytes . ' B';
        if ($bytes < 1_048_576)   return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1_048_576, 1) . ' MB';
    }

    // ─────────────────────────────────────────────────────
    // Helper: label tipe dokumen
    // ─────────────────────────────────────────────────────
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'cv'              => 'Curriculum Vitae',
            'transkrip'       => 'Transkrip Nilai',
            'ktm'             => 'Kartu Tanda Mahasiswa',
            'surat_pengantar' => 'Surat Pengantar Kampus',
            default           => 'Dokumen Lainnya',
        };
    }

    // ─────────────────────────────────────────────────────
    // Scope: dokumen admin (tanpa peserta)
    // ─────────────────────────────────────────────────────
    public function scopeAdminDocs($query)
    {
        return $query->whereNull('applicant_id');
    }

    // ─────────────────────────────────────────────────────
    // Scope: dokumen milik peserta
    // ─────────────────────────────────────────────────────
    public function scopeApplicantDocs($query)
    {
        return $query->whereNotNull('applicant_id');
    }
}