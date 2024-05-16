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

    public function showSchedule(Request $request)
    {
        // Obtener el empleado autenticado
        $employeeId = $request->user()->id;

        // Verificar si el empleado existe
        if (!$employeeId) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }

        // Obtener los horarios del empleado
        $schedule = Schedule::where('employees_id', $employeeId)->get();

        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {

        // Encuentra el horario por su ID
        $schedule = Schedule::find($id);

        // Verifica si el horario existe
        if (!$schedule) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        // Valida los datos del formulario
        $request->validate([
            'title' => 'required',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
        ]);

        //  // Formatea las fechas correctamente
        // $start_datetime = date('Y-m-d H:i:s', strtotime($request->input('start_datetime')));
        // $end_datetime = date('Y-m-d H:i:s', strtotime($request->input('end_datetime')));
        // Actualiza los datos del horario
        $schedule->title = $request->input('title');
        $schedule->start_datetime = $request->input('start_datetime');
        $schedule->end_datetime = $request->input('end_datetime');

        // Guarda los cambios en la base de datos
        $schedule->save();

        // Devuelve una respuesta JSON con un mensaje de éxito y los datos del horario actualizados
        return response()->json(['message' => 'Horario actualizado correctamente', 'schedule' => $schedule], 200);
    }


    public function deleteEvent($id)
    {
        $event = Schedule::find($id);
        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }
        $event->delete();
        return response()->json(['message' => 'Evento eliminado correctamente']);
    }
}
