<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'type',
        'file_path',
        'original_name'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function parsedResume()
    {
        return $this->hasOne(ParsedResume::class, 'document_id');
    }
}