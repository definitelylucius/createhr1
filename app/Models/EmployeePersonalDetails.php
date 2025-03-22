<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePersonalDetails extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'full_name', 'phone', 'email', 'age', 'birthday', 'gender', 'nationality'];
}
