<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

  
    protected $table = 'job_applications';
    protected $fillable = ['user_id', 'job_id', 'name', 'email', 'resume', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','name','email');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id','job_title','department');

}



}