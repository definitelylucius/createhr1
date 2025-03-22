<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboarding extends Model
{


    use HasFactory;

    protected $fillable = [
        'user_id',
        'orientation_completed',
        'documents_submitted',
        'policies_accepted'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
