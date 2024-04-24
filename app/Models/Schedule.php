<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'start_datetime', 'end_datetime', 'employees_id'];

    public function employees()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }
}
