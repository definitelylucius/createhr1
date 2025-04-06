<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParsedResume extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'skills',
        'experience_years',
        'education',
        'job_history',
        'raw_data'
    ];

    protected $casts = [
        'skills' => 'array',
        'job_history' => 'array'
    ];

    public function document()
    {
        return $this->belongsTo(CandidateDocument::class);
    }
}