<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absences extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date', 'motive','type_absence', 'employees_id'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
