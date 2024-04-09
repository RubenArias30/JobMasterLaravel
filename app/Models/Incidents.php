<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidents extends Model
{
    use HasFactory;

    protected $fillable = ['incident_type', 'description', 'date', 'employees_id'];

    public function employees()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }
}
