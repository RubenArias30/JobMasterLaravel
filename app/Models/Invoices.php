<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $fillable = ['subtotal', 'discount', 'invoice_iva', 'invoice_irpf', 'total', 'company_id', 'client_id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function concepts()
    {
        return $this->hasMany(Concept::class);
    }
}
