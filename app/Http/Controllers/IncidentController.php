<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incidents;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
     /**
     * Method to retrieve all incidents.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all incidents with employee details
        $incidents = Incidents::with('employee')->get();
        return response()->json($incidents);
    }
    /**
     * Method to create a new incident.
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'incident_type' => 'required|string',
            'description' => 'required|string|max:300',
            'date' => 'required|date',
        ]);

        // Create a new incident
        $incident = new Incidents();
        $incident->incident_type = $request->incident_type;
        $incident->description = $request->description;
        $incident->date = $request->date;
        // Assign the ID of the employee creating the incident
        $incident->employees_id = auth()->user()->id;

        $incident->save();

        return response()->json($incident, 201);
    }

    /**
     * Method to retrieve incidents of the authenticated employee.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $employeeId = auth()->user()->id;
        $incidents = Incidents::where('employees_id', $employeeId)->get();
        return response()->json($incidents);
    }

      /**
     * Method to update the status of an incident.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Find the incident by its ID
        $incident = Incidents::findOrFail($id);

        // Validate the new status
        $request->validate([
            'status' => 'required|in:completed,pending',
        ]);

        // Update the status of the incident
        $incident->status = $request->status;
        $incident->save();

        return response()->json($incident, 200);
    }


  /**
     * Method to delete an incident.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find and delete the incident by its ID
        $incident = Incidents::find($id);
        $incident->delete();

        return response()->json(null, 204);
    }
}
