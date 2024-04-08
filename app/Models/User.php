<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User  extends Authenticatable
{
    use HasFactory;

    // protected $fillable = ['dni', 'password', 'roles', 'empleado_id'];
    protected $fillable = ['nif', 'password', 'roles'];

    // public function empleado()
    // {
    //     return $this->belongsTo(Empleado::class);
    // }
}
