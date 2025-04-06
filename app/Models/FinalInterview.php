<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalInterview extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'interviewer_id',
        'scheduled_at',
        'notes',
        'status',
        'result',
        'feedback'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    protected $casts = [
        'scheduled_at' => 'datetime'
    ];
}