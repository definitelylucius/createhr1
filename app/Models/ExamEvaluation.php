<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'evaluator_id',
        'score',
        'passed',
        'criteria_scores',
        'strengths',
        'weaknesses',
        'feedback',
        'completed_at'
    ];

    protected $casts = [
        'criteria_scores' => 'array',
        'completed_at' => 'datetime',
        'passed' => 'boolean',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class);
    }
    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }
}