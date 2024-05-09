<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['client_name', 'client_telephone', 'client_nif', 'client_email','client_street','client_city','client_postal_code'];

    // public function address()
    // {
    //     return $this->belongsTo(Address::class, 'address_id');
    // }
}
