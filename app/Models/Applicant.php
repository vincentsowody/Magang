<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'nim', 'email', 'phone', 'code',
        'univ', 'major', 'status', 'location',
    ];

    protected $casts = [
        'status'   => 'string',
        'location' => 'string',
    ];

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
