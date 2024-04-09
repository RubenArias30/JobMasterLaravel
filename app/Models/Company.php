<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'telephone', 'nif', 'email', 'address_id'];

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
