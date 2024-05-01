<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;

    protected $fillable = ['type_documents', 'name', 'description', 'date', 'route', 'employees_id'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }
}
