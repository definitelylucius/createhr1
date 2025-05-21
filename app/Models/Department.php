<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'head_id',
        'parent_id'
    ];

    public function head()
    {
        return $this->belongsTo(Employee::class, 'head_id');
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function job()
    {
        return $this->hasMany(Job::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}