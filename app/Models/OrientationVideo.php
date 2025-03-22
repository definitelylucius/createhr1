<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrientationVideo extends Model
{
    use HasFactory;

    // Make sure to define the fillable fields
    protected $fillable = ['title', 'video_path', 'employee_id', 'uploaded_by', 'progress'];

    // Optionally, you can define relationships with Employee if needed
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}