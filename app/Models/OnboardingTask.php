<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingTask extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'task_name', 'status', 'task_type'];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function job()
{
    return $this->belongsTo(Job::class);  // Assuming 'Job' is the related model
}
}
