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

    public function delete($id)
    {

        $invoice = Invoices::find($id);

        // Verificar si la factura existe
        if (!$invoice) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        // Finalmente, eliminar al empleado
        $invoice->delete();

        return response()->json(['message' => 'Factura eliminada exitosamente']);
    }

}

