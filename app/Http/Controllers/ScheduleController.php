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

    // Verifica si ya existe un horario en el rango de fechas especificado
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


//     public function destroy($employeeId, $scheduleId)
// {
//     // Encuentra al empleado
//     $employee = Employees::find($employeeId);

//     if (!$employee) {
//         return response()->json(['error' => 'Empleado no encontrado'], 404);
//     }

//     // Encuentra el horario a eliminar
//     $schedule = $employee->schedules()->find($scheduleId);

//     if (!$schedule) {
//         return response()->json(['error' => 'Horario no encontrado'], 404);
//     }

//     // Elimina el horario
//     $schedule->delete();

//     return response()->json(null, 204); // Retorna una respuesta vacía con el código 204 (No Content) para indicar que se eliminó el horario correctamente
// }
public function delete(Request $request, $employeeId, $scheduleId)
{
    // Valida los datos
    $request->validate([
        'schedule_id' => 'required|exists:schedules,id',
    ]);

    // Accede al schedule_id desde la solicitud
    $scheduleId = $request->schedule_id;

    // Encuentra el horario del empleado
    $schedule = Schedule::where('id', $scheduleId)
                        ->where('employees_id', $employeeId)
                        ->first();

    if (!$schedule) {
        return response()->json(['error' => 'Horario no encontrado'], 404);
    }

    // Elimina el horario
    $schedule->delete();

    return response()->json(['message' => 'Horario eliminado correctamente']);
}


}
