<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'test_type',
        'scheduled_at',
        'completed_at',
        'score',
        'is_passed',
        'administered_by',
        'notes'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_passed' => 'boolean',
        'score' => 'decimal:2'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function administeredBy()
    {
        return $this->belongsTo(User::class, 'administered_by');
    }
}