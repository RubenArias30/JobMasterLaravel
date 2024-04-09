<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'start_time', 'end_time', 'absense_type', 'employees_id'];

    public function employees()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }
}