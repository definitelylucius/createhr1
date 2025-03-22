<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = ['employee_id', 'government_id_local_path', 'tax_forms_local_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id', 'id');
}


}