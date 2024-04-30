<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employees; // Add this line below the namespace declaration

class Ausencia extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date',
        'end_date',
        'motive',
        'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class);
    }
}
