<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index($employeeId)
{
    $employee = Employees::find($employeeId);

    if (!$employee) {
        return response()->json(['error' => 'Empleado no encontrado'], 404);
    }

    $schedules = $employee->schedules;

    return response()->json($schedules, 200);
}

    public function store(Request $request, $id)
    {
        // Encuentra al empleado
        $employee = Employees::find($id);

        if (!$employee) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }

        // Valida los datos
        $request->validate([
            'title' => 'required',
            'start_datetime' => 'required',
            'end_datetime' => 'required',
        ]);

        // Crea el horario
        $schedule = new Schedule([
            'title' => $request->title,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
        ]);

        // Asocia el horario con el empleado
        $employee->schedules()->save($schedule);

        return response()->json($schedule, 201);
    }

    public function show($id)
    {
        $employee = Employees::findOrFail($id);
        return response()->json($employee);
    }
}
