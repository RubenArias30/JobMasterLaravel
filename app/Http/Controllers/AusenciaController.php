<?php

namespace App\Http\Controllers;
use App\Models\Ausencia;

use Illuminate\Http\Request;

class AusenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ausencias = Ausencia::with('employee')->get();
        return response()->json($ausencias);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'motive' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id'
        ]);

        $ausencia = Ausencia::create($validatedData);
        return response()->json($ausencia, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
