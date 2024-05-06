<?php

namespace App\Http\Controllers;

use App\Models\Absences;

use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absences = Absences::with('employee', 'user')->get();
        return response()->json($absences);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type_absence' => 'required|string',
            'motive' => 'required|string',
        ]);

        // // Create the absence record
        // $absence = Absences::create($validatedData);
         // Create a new absence record

         $absence = new Absences();
         $absence->start_date = $request->input('start_date');
         $absence->end_date = $request->input('end_date');
         $absence->type_absence = $request->input('type_absence');
         $absence->motive = $request->input('motive');
         $absence->employees_id = $request->input('employee_id');
         $absence->save();



        // Return a response
        return response()->json(['message' => 'Absence created successfully', 'data' => $absence], 201);


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
      $absence = Absences::find($id);
      $absence->delete();

      return response()->json(['message' => 'Absence deleted successfully'], 200);
    }



}
