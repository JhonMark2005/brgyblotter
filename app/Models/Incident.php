<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    public const STATUSES = [
        'pending'             => 'Pending',
        'under_investigation' => 'Under Investigation',
        'resolved'            => 'Resolved',
        'dismissed'           => 'Dismissed',
    ];

    protected $fillable = [
        'complainant_name',
        'respondent_name',
        'incident_type',
        'date',
        'location',
        'description',
        'status',
        'complainant_email',
        'respondent_email',
        'hearing_date',
        'hearing_notes',
        'recorded_by',
    ];

    protected $casts = [
        'date'         => 'date',
        'hearing_date' => 'datetime',
    ];

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
