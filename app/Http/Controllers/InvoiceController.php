<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoices;
use App\Models\Concept;

class InvoiceController extends Controller
{
    public function index()
    {
        // $invoices = Invoices::all();

         $invoices = Invoices::with('concepts')->get();

        return response()->json($invoices);
    }
}

