<?php

namespace App\Http\Controllers;

use App\Models\Absences;
use App\Models\Employees;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absences = Absences::with('employee,user')->get();
        return response()->json($absences);
    }

    public function store(Request $request)
{
    $userId = Auth::id(); // Get the authenticated user's ID

    $validatedData = $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'motive' => 'required|string|max:255',
        'type' => 'required|string|in:Vacaciones,Enfermedad,Maternidad/Paternidad,Compensatorias,Baja,Otros',
        'employee_id' => 'required|exists:employees,id'
    ]);

    // Check if the authenticated user's ID is available
    if (!$userId) {
        return response()->json(['error' => 'User ID not found'], 500);
    }

    // Create the absence record
    $absence = new Absences();
    $absence->start_date = $validatedData['start_date'];
    $absence->end_date = $validatedData['end_date'];
    $absence->motive = $validatedData['motive'];
    $absence->type = $validatedData['type'];
    $absence->employee_id = $userId;

    // Save the absence record
    $absence->save();

    return response()->json($absence, 201);
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
