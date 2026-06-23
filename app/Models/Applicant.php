<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'nim', 'email', 'phone', 'code',
        'univ', 'major', 'status', 'location', 'lokasi_penempatan',
        'internship_start', 'internship_end',
        'reply_letter_path', 'reply_letter_name', 'reply_letter_uploaded_at',
    ];

    protected $casts = [
        'status'                   => 'string',
        'location'                 => 'string',
        'internship_start'         => 'date',
        'internship_end'           => 'date',
        'reply_letter_uploaded_at' => 'datetime',
    ];

    // Durasi magang dalam bulan (dibulatkan), null jika periode belum diisi
    public function getInternshipDurationMonthsAttribute(): ?int
    {
        if (!$this->internship_start || !$this->internship_end) {
            return null;
        }
        return $this->internship_start->diffInMonths($this->internship_end) ?: 1;
    }

    protected $appends = ['internship_duration_months'];

    public function documents()
    {
        return $this->hasMany(ApplicantDocument::class);
    }

    public function notifications()
    {
        return $this->hasMany(ApplicantNotification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }
}   