<?php

namespace App\Http\Controllers;

use App\Models\Absences;
use App\Models\Employees;

use Illuminate\Http\Request;

class AbsenceController extends Controller
{
public function index(Request $request)
{
  $type = $request->query('type');

  $query = Absences::with('employee', 'user')
      ->whereHas('employee', function ($query) {
          $query->where('id', '!=', 1);
      });

  if ($type) {
      $query->where('type_absence', $type);
  }

  $absences = $query->get();

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
    public function show(Absences $absence)
    {
        return response()->json($absence);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type_absence' => 'required|string',
            'motive' => 'required|string',
        ]);

        // Find the absence record by ID
        $absence = Absences::findOrFail($id);

        // Update the absence record with the validated data
        $absence->start_date = $validatedData['start_date'];
        $absence->end_date = $validatedData['end_date'];
        $absence->type_absence = $validatedData['type_absence'];
        $absence->motive = $validatedData['motive'];
        // Optionally, you can also update other fields if needed

        // Save the updated absence record
        $absence->save();

        // Return a response
        return response()->json(['message' => 'Absence updated successfully', 'data' => $absence], 200);
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
