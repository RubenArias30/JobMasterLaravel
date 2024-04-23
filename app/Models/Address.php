<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['street','city', 'postal_code'];

    public function employee()
{
    return $this->belongsTo(Employees::class);
}
}
