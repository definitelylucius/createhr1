<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiringProcessStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'stage',
        'scheduled_at',
        'completed_at',
        'notes',
        'feedback',
        'interviewer',
        'result'
    ];

    protected $casts = [
        'feedback' => 'array',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function calendarEvent()
    {
        return $this->hasOne(CalendarEvent::class, 'stage_id');
    }
}