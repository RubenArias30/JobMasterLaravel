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
        return $this->belongsTo(Users::class, 'users_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
