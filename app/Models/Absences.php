<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absences extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date', 'motive', 'employees_id'];

    public function employees()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }
}
