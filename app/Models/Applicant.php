<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'skills',
        'experience',
        'education',
        'resume_file',
        'resume_original_name',
        'raw_text',
        'parsed_data',
        'user_id'
    ];

    protected $casts = [
        'parsed_data' => 'array',
        'skills' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getResumeUrlAttribute()
    {
        return $this->resume_file ? asset('storage/'.$this->resume_file) : null;
    }
}