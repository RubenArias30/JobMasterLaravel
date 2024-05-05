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
    public function index()
    {
        // Obtener todas las incidencias
        $incidents = Incidents::with('employee')->get();
        return response()->json($incidents);
    }

    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'incident_type' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
        ]);

        // Crear una nueva incidencia
        $incident = new Incidents();
        $incident->incident_type = $request->incident_type;
        $incident->description = $request->description;
        $incident->date = $request->date;
        // Asignar el ID del empleado que está creando la incidencia
        $incident->employees_id = auth()->user()->id;

        $incident->save();

        return response()->json($incident, 201);
    }

    public function show()
    {
        $employeeId = auth()->user()->id;
        $incidents = Incidents::where('employees_id', $employeeId)->get();
        return response()->json($incidents);
    }

    public function updateStatus(Request $request, $id)
    {
        // Buscar la incidencia por su ID
        $incident = Incidents::findOrFail($id);

        // Validar el nuevo estado
        $request->validate([
            'status' => 'required|in:completed,pending',
        ]);

        // Actualizar el estado de la incidencia
        $incident->status = $request->status;
        $incident->save();

        return response()->json($incident, 200);
    }



    public function destroy($id)
    {
        // Buscar la incidencia por su ID y eliminarla
        $incident = Incidents::findOrFail($id);
        $incident->delete();

        return response()->json(null, 204);
    }
}
