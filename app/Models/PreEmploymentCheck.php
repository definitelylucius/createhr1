<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreEmploymentCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'type',
        'status',
        'completed_at',
        'notes',
        'verified_by'
    ];

    protected $casts = [
        'completed_at' => 'date',
    ];

    // Relationship to candidate
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    // Relationship to user who verified
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helper methods for display
    public function getTypeNameAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->type));
    }

    public function getStatusNameAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    // Status colors for UI
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'completed' => 'green',
            'in_progress' => 'blue',
            'failed' => 'red',
            default => 'gray',
        };
    }
}