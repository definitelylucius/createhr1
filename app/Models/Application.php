<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
// Define all status constants
const STATUS_FINAL_INTERVIEW_PASSED = 'final_interview_passed';
const STATUS_PRE_EMPLOYMENT_COMPLETED = 'pre_employment_completed';
const STATUS_PENDING = 'pending';
const STATUS_IN_PROGRESS = 'in_progress';
const STATUS_DOCUMENTS_COMPLETED = 'documents_completed';
  
    protected $table = 'job_applications';
    protected $fillable = ['user_id', 'job_id', 'name', 'email', 'resume', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','name','email');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'id','title','department');

}

public function preEmploymentDocument()
{
    return $this->hasOne(PreEmploymentDocument::class);
}



// Status check method
public function preEmploymentStatus()
{
    if (!$this->preEmploymentDocument) {
        return self::STATUS_PENDING;
    }
    
    if ($this->status === self::STATUS_PRE_EMPLOYMENT_COMPLETED) {
        return self::STATUS_PRE_EMPLOYMENT_COMPLETED;
    }
    
    if ($this->preEmploymentDocument->allVerified()) {
        return self::STATUS_DOCUMENTS_COMPLETED;
    }
    
    if ($this->preEmploymentDocument->scheduled_date) {
        return self::STATUS_IN_PROGRESS;
    }
    
    return self::STATUS_PENDING;
}



}