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
        // $request->validate([
        //     'name' => 'required',
        //     'telephone' => 'required',
        //     'nif' => 'required',
        //     'email' => 'required|email',
        //     'concept' => 'required',
        //     'price' => 'required',
        //     'quantity' => 'required',
        //     'discount' => 'required',
        //     'concept_iva' => 'required',
        //     'concept_irpf' => 'required',
        //     'subtotal' => 'required',
        //     'invoice_iva' => 'required',
        //     'invoice_irpf' => 'required',
        //     'total' => 'required',
        // ]);

        // Crear una nueva instancia del modelo Client
        // $client = new Client();
        // // Asignar los datos del formulario a la instancia del modelo
        // $client->name = $request->name;
        // $client->telephone = $request->telephone;
        // $client->nif = $request->nif;
        // $client->email = $request->email;
        // $client->address_id = $request->address_id;
        // // Guardar la instancia del modelo en la base de datos
        // $client->save();

        $address = new Address();
        $address->street = $request['street'];
        $address->city = $request['city'];
        $address->postal_code = $request['postal_code'];
        $address->save();

        $company = new Company();
        $company->name = $request['name'];
        $company->telephone = $request['telephone'];
        $company->nif = $request['nif'];
        $company->email = $request['email'];
        $company->address_id = $address->id; // Asignar el ID de la dirección
        $company->save();

        // // Crear una nueva instancia del modelo Invoice
        // $invoice = new Invoices();
        // // Asignar los datos del formulario a la instancia del modelo
        // $invoice->subtotal = $request->subtotal;
        // $invoice->discount = $request->discount;
        // $invoice->invoice_iva = $request->invoice_iva;
        // $invoice->invoice_irpf = $request->invoice_irpf;
        // $invoice->total = $request->total;
        // $invoice->company_id = $company->id; // Usar el ID de la empresa recién creada
        // $invoice->client_id = $client->id; // Usar el ID del cliente recién creado
        // // Guardar la instancia del modelo en la base de datos
        // $invoice->save();

        // Crear una nueva instancia del modelo Concept
        // $concept = new Concept();
        // // Asignar los datos del formulario a la instancia del modelo
        // $concept->concept = $request->concept;
        // $concept->price = $request->price;
        // $concept->quantity = $request->quantity;
        // $concept->discount = $request->discount;
        // $concept->concept_iva = $request->concept_iva;
        // $concept->concept_irpf = $request->concept_irpf;
        // $concept->invoices_id = $invoice->id; // Usar el ID de la factura recién creada
        // // Guardar la instancia del modelo en la base de datos
        // $concept->save();

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

