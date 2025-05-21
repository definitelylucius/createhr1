<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentProcess extends Model
{
    use HasFactory;

    protected $table = 'recruitment_process'; // Add this line

    protected $fillable = [
        'application_id',
        'stage',
        'scheduled_at',
        'completed_at',
        'notes',
        'interviewer',
        'location',
        'meeting_link',
        'passed'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'passed' => 'boolean'
    ];

    // In RecruitmentProcess.php
    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'application_id', 'id');
    }
    // In JobApplication model
public function user()
{
    return $this->belongsTo(User::class);
}

public function job()
{
    return $this->belongsTo(Job::class);
}
}