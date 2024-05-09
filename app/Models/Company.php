<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['company_name', 'company_telephone', 'company_nif', 'company_email','company_street','company_city','company_postal_code'];

    // public function address()
    // {
    //     return $this->belongsTo(Address::class, 'address_id');
    // }
}
