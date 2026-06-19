<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantNotification extends Model
{
    protected $table = 'applicant_notifications';

    protected $fillable = [
        'applicant_id', 'title', 'message', 'type', 'is_read', 'meta',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'meta'    => 'array',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
