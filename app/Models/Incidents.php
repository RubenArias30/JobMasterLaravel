<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidents extends Model
{
    use HasFactory;

    protected $fillable = ['incident_type', 'description', 'date', 'status', 'employees_id'];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class)->withDefault();
    }

    public function user()
    {
        return $this->hasOne(User::class)->withDefault();
    }
}
