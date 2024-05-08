<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

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

    public function show($id)
    {
        $event = Schedule::find($id);
        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }
        return response()->json($event);
    }

    public function update(Request $request, $id)
    {

        $event = Schedule::find($id);

        if (!$event) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        // Actualizar los datos del horario
        $event->title = $request->input('title');
        $event->start_datetime = $request->input('start_datetime');
        $event->end_datetime = $request->input('end_datetime');

         // Guardar los cambios en la base de datos
         $event->save();

         return response()->json(['message' => 'Horario actualizado correctamente', 'schedule' => $event], 200);
    }


    public function deleteEvent($id)
    {
        // $event = Schedule::find($id);
        // if (!$event) {
        //     return response()->json(['error' => 'Evento no encontrado'], 404);
        // }
        // $event->delete();
        // return response()->json(['message' => 'Evento eliminado correctamente']);
    }
}
