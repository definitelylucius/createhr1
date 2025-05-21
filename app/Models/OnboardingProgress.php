<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingProgress extends Model
{
    protected $fillable = ['id'	,'user_id',	'task_name'	,'status'	,'watch_time'	,'	video_length'	,'	completed_at	'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function allDocumentsUploaded()
{
    return $this->employment_contract && 
           $this->tax_forms && 
           $this->company_policies && 
           $this->training_materials;
}
}