<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'surname', 'email', 'date_of_birth', 'gender', 'telephone', 'country', 'photo', 'users_id', 'address_id', 'company_id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function addresses()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function documents()
    {
        return $this->hasMany(Documents::class);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incidents::class);
    }
    public function absences()
    {
        return $this->belongsTo(Absences::class);
    }
}
