<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'title',
        'description',
        'type',
        'due_date',
        'completed_at',
        'status',
        'assigned_to',
        'completed_by',
        'notes'
    ];


    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}