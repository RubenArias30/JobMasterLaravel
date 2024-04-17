<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Client;
use App\Models\Company;
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

    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'name' => 'required',
            'telephone' => 'required',
            'nif' => 'required',
            'email' => 'required|email',
            'street' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
        ]);

        // Dirección
        $address = new Address();
        $address->street = $request->input('street');
        $address->city = $request->input('city');
        $address->postal_code = $request->input('postal_code');
        $address->save();

        // Compañia
        $company = new Company();
        $company->name = $request->input('name');
        $company->telephone = $request->input('telephone');
        $company->nif = $request->input('nif');
        $company->email = $request->input('email');
        $company->address_id = $address->id;
        $company->save();

         // Cliente
         $client = new Client();
         $client->name = $request->input('name');
         $client->telephone = $request->input('telephone');
         $client->nif = $request->input('nif');
         $client->email = $request->input('email');
         $client->address_id = $address->id;
         $client->save();

        //Invoice
        // $invoice = new Invoices();
        // $invoice->subtotal = $request->subtotal;
        // $invoice->discount = $request->discount;
        // $invoice->invoice_iva = $request->invoice_iva;
        // $invoice->invoice_irpf = $request->invoice_irpf;
        // $invoice->total = $request->total;
        // $invoice->company_id = $company->id;
        // $invoice->client_id = $client->id;
        // $invoice->save();

        //Concepto
        $concept = new Concept();
        $concept->concept = $request->concept;
        $concept->price = $request->price;
        $concept->quantity = $request->quantity;
        $concept->discount = $request->discount;
        $concept->concept_iva = $request->concept_iva;
        $concept->concept_irpf = $request->concept_irpf;
        $concept->invoices_id = "2";
        $concept->save();

        return response()->json($company, 201);
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

