<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiringDecision extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'approved_by',
        'hire_date',
        'salary',
        'position',
        'department',
        'notes'
    ];

    protected $casts = [
        'hire_date' => 'date',
        // other casts...
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}