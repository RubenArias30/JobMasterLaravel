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

    // Obtener la fecha actual y el primer y último día del mes actual
    $currentDate = now();
    $firstDayOfMonth = $currentDate->startOfMonth()->toDateString();
    $lastDayOfMonth = $currentDate->endOfMonth()->toDateString();

    // Consultar los eventos asociados al empleado dentro del mes actual
    $schedules = $employee->schedules()
        ->whereBetween('start_datetime', [$firstDayOfMonth, $lastDayOfMonth])
        ->get();

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

        $existingSchedule = $employee->schedules()
        ->where(function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                // Caso 1: El nuevo horario comienza dentro de un horario existente
                $query->where('start_datetime', '>=', $request->start_datetime)
                      ->where('start_datetime', '<', $request->end_datetime);
            })
            ->orWhere(function ($query) use ($request) {
                // Caso 2: El nuevo horario termina dentro de un horario existente
                $query->where('end_datetime', '>', $request->start_datetime)
                      ->where('end_datetime', '<=', $request->end_datetime);
            })
            ->orWhere(function ($query) use ($request) {
                // Caso 3: El nuevo horario está completamente contenido dentro de un horario existente
                $query->where('start_datetime', '<=', $request->start_datetime)
                      ->where('end_datetime', '>=', $request->end_datetime);
            });
        })
        ->exists();

        if ($existingSchedule) {
            return response()->json(['error' => 'Ya existe un horario en el rango de fechas especificado'], 400);
        }

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

    public function checkExistingSchedule(Request $request, $id)
{
    // Encuentra al empleado
    $employee = Employees::find($id);

    if (!$employee) {
        return response()->json(['error' => 'Empleado no encontrado'], 404);
    }

    // Verificar si ya existe un horario en el rango de fechas especificado
    $existingSchedule = $employee->schedules()
        ->where(function ($query) use ($request) {
            $query->whereBetween('start_datetime', [$request->start_datetime, $request->end_datetime])
                  ->orWhereBetween('end_datetime', [$request->start_datetime, $request->end_datetime])
                  ->orWhere(function ($query) use ($request) {
                      $query->where('start_datetime', '<=', $request->start_datetime)
                            ->where('end_datetime', '>=', $request->end_datetime);
                  });
        })
        ->exists();

    return response()->json(['exists' => $existingSchedule]);
}


    public function show($id)
    {
        $employee = Employees::findOrFail($id);
        return response()->json($employee);
    }
}
