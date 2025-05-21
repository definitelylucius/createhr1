<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'stage_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'meeting_link',
        'timezone',
        'calendar_event_id',
        'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function stage()
    {
        return $this->belongsTo(HiringProcessStage::class);
    }
}