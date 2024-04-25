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

        $invoices = Invoices::with('clients','companies','concepts')->get();

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
            'concepts.*.concept' => 'required',
            'concepts.*.price' => 'required|numeric|min:0',
            'concepts.*.quantity' => 'required|numeric|min:1',
            'concepts.*.concept_discount' => 'nullable|numeric|min:0',
            'concepts.*.concept_iva' => 'nullable|numeric|min:0',
            'concepts.*.concept_irpf' => 'nullable|numeric|min:0',

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

    public function update(Request $request, $id)
    {
        // Buscar la factura por su ID
        $invoice = Invoices::find($id);

        // Verificar si la factura existe
        if (!$invoice) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        // Validar los datos
        $request->validate([
            // Agrega aquí las reglas de validación según tus necesidades
        ]);

        // Actualizar la factura
        $invoice->update([
            'subtotal' => $request->subtotal,
            'invoice_discount' => $request->invoice_discount,
            'invoice_iva' => $request->invoice_iva,
            'invoice_irpf' => $request->invoice_irpf,
            'total' => $request->total,
        ]);

        // Actualizar el cliente asociado
        $client = Client::find($invoice->client_id);
        $client->update([
            'client_name' => $request->client_name,
            'client_telephone' => $request->client_telephone,
            'client_nif' => $request->client_nif,
            'client_email' => $request->client_email,
        ]);

        // Actualizar la compañía asociada
        $company = Company::find($invoice->company_id);
        $company->update([
            'company_name' => $request->company_name,
            'company_telephone' => $request->company_telephone,
            'company_nif' => $request->company_nif,
            'company_email' => $request->company_email,
        ]);

        // Actualizar la dirección asociada
        $address = Address::find($company->address_id);
        $address->update([
            'street' => $request->street,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
        ]);

        // Eliminar los conceptos existentes asociados a la factura
        $invoice->concepts()->delete();

        // Crear los nuevos conceptos
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

        // Verificar si la factura existe
        if (!$invoice) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        // Finalmente, eliminar al empleado
        $invoice->delete();

        return response()->json(['message' => 'Factura eliminada exitosamente']);
    }

    public function show($id)
{
    $employee = Invoices::with('addresses', 'clients', 'companies', 'concepts')->find($id);

    if (!$employee) {
        return response()->json(['message' => 'Presupuesto no encontrado'], 404);
    }

    return response()->json($employee);
}
}
