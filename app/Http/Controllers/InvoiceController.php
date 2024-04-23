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
            'client_name' => 'required',
            'client_telephone' => 'required',
            'client_nif' => 'required',
            'client_email' => 'required|email',
            'company_name' => 'required',
            'company_telephone' => 'required',
            'company_nif' => 'required',
            'company_email' => 'required|email',
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
        $company->company_name = $request->input('company_name');
        $company->company_telephone = $request->input('company_telephone');
        $company->company_nif = $request->input('company_nif');
        $company->company_email = $request->input('company_email');
        $company->address_id = $address->id;
        $company->save();

        // Cliente
        $client = new Client();
        $client->client_name = $request->input('client_name');
        $client->client_telephone = $request->input('client_telephone');
        $client->client_nif = $request->input('client_nif');
        $client->client_email = $request->input('client_email');
        $client->address_id = $address->id;
        $client->save();


        $subTotal = 0;
        foreach ($request->input('concepts') as $conceptData) {
            $subTotal += $conceptData['price'] * $conceptData['quantity'];
        }
        //Invoice
        $invoice = new Invoices();
        $invoice->subtotal = $subTotal;
        $invoice->invoice_discount = $request->invoice_discount;
        $invoice->invoice_iva = $request->invoice_iva;
        $invoice->invoice_irpf = $request->invoice_irpf;
        $invoice->total = $request->total;
        $invoice->company_id = $company->id;
        $invoice->client_id = $client->id;
        $invoice->save();

        //Concepto
        foreach ($request->input('concepts') as $conceptData) {
            $concept = new Concept();
            $concept->concept = $conceptData['concept'];
            $concept->price = $conceptData['price'];
            $concept->quantity = $conceptData['quantity'];
            $concept->concept_discount = $conceptData['concept_discount'];
            $concept->concept_iva = $conceptData['concept_iva'];
            $concept->concept_irpf = $conceptData['concept_irpf'];
            $concept->invoices_id = $invoice->id;
            $concept->save();
        }


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
