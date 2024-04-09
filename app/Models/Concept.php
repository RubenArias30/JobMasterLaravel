<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    use HasFactory;

    protected $fillable = ['concept', 'price', 'quantity', 'discount', 'concept_iva', 'concept_irpf', 'invoices_id'];

    public function invoices()
    {
        return $this->belongsTo(Invoices::class, 'invoices_id');
    }
}
