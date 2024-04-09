<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Users  extends Authenticatable
{
    use HasFactory;


    protected $fillable = ['nif', 'password', 'roles'];

    public function employees()
    {
        return $this->hasOne(Employees::class);
    }
}
