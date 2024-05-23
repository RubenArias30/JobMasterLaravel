<?php

namespace App\Http\Controllers;


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

        $invoices = Invoices::with('clients','companies','concepts')->get();

        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        // Validate data
        $request->validate([
            'client_name' => 'required',
            'client_telephone' => 'required',
            'client_nif' => 'required',
            'client_email' => 'required|email',
            'client_street' => 'required',
            'client_city' => 'required',
            'client_postal_code' => 'required',
            'company_name' => 'required',
            'company_telephone' => 'required',
            'company_nif' => 'required',
            'company_email' => 'required|email',
            'company_street' => 'required',
            'company_city' => 'required',
            'company_postal_code' => 'required',
            'concepts.*.concept' => 'required',
            'concepts.*.price' => 'required|numeric|min:0',
            'concepts.*.quantity' => 'required|numeric|min:1',
            'concepts.*.concept_discount' => 'nullable|numeric|min:0',
            'concepts.*.concept_iva' => 'nullable|numeric|min:0',
            'concepts.*.concept_irpf' => 'nullable|numeric|min:0',

        ]);

        // ADdress
        // $address = new Address();
        // $address->street = $request->input('street');
        // $address->city = $request->input('city');
        // $address->postal_code = $request->input('postal_code');
        // $address->save();

        // Company
        $company = new Company();
        $company->company_name = $request->input('company_name');
        $company->company_telephone = $request->input('company_telephone');
        $company->company_nif = $request->input('company_nif');
        $company->company_email = $request->input('company_email');
        $company->company_street = $request->input('company_street');
        $company->company_city = $request->input('company_city');
        $company->company_postal_code = $request->input('company_postal_code');

        $company->save();

        // Client
        $client = new Client();
        $client->client_name = $request->input('client_name');
        $client->client_telephone = $request->input('client_telephone');
        $client->client_nif = $request->input('client_nif');
        $client->client_email = $request->input('client_email');
        $client->client_street = $request->input('client_street');
        $client->client_city = $request->input('client_city');
        $client->client_postal_code = $request->input('client_postal_code');
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

        //Concept
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

    public function update(Request $request, $id)
    {
        // Search by ID
        $invoice = Invoices::find($id);

        // Validate if exist
        if (!$invoice) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        // Validate data
        $request->validate([
        ]);

        //UPdate budget
        $invoice->update([
            'subtotal' => $request->subtotal,
            'invoice_discount' => $request->invoice_discount,
            'invoice_iva' => $request->invoice_iva,
            'invoice_irpf' => $request->invoice_irpf,
            'total' => $request->total,
        ]);

        // UPdate asociated client
        $client = Client::find($invoice->client_id);
        $client->update([
            'client_name' => $request->client_name,
            'client_telephone' => $request->client_telephone,
            'client_nif' => $request->client_nif,
            'client_email' => $request->client_email,
        ]);

        // UPdate asociated company
        $company = Company::find($invoice->company_id);
        $company->update([
            'company_name' => $request->company_name,
            'company_telephone' => $request->company_telephone,
            'company_nif' => $request->company_nif,
            'company_email' => $request->company_email,
        ]);

        
        // $address = Address::find($company->address_id);
        // $address->update([
        //     'street' => $request->street,
        //     'city' => $request->city,
        //     'postal_code' => $request->postal_code,
        // ]);

        // Delete the existing concepts associated with the invoice.
        $invoice->concepts()->delete();

        // Create new budget
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

        return response()->json(['message' => 'Factura actualizada exitosamente']);
    }


    public function delete($id)
    {

        $invoice = Invoices::find($id);

        //Verify if exists
        if (!$invoice) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        // Delete
        $invoice->delete();

        return response()->json(['message' => 'Factura eliminada exitosamente']);
    }

    public function show($id)
{
    $employee = Invoices::with('clients', 'companies', 'concepts')->find($id);

    if (!$employee) {
        return response()->json(['message' => 'Presupuesto no encontrado'], 404);
    }

    return response()->json($employee);
}
}
