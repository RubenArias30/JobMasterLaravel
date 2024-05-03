<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendancesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function registerEntry(Request $request)
    {
        // Obtiene el ID del usuario autenticado
        $userId = auth()->user()->id;

        // Crear un nuevo registro de asistencia para la entrada del empleado
        $attendance = new Attendance();
        $attendance->date = now()->toDateString(); // Obtener la fecha actual
        $attendance->start_time = now()->toTimeString(); // Obtener la hora actual
        $attendance->current_time = now()->toTimeString(); // Guardar el tiempo actual como current_time
        $attendance->employees_id = $userId; // Asignar el ID del usuario autenticado como el ID del empleado
        $attendance->save();

        // Retorna una respuesta de éxito
        return response()->json(['message' => 'Entrada registrada correctamente'], 201);
    }


    public function registerExit(Request $request)
    {
        // Obtener el ID del usuario autenticado
        $userId = auth()->user()->id;

        // Buscar el registro de asistencia más reciente del usuario autenticado
        $attendance = Attendance::where('employees_id', $userId)->latest()->first();

        // Si no se encuentra un registro de asistencia para el usuario autenticado, retornar un mensaje de error
        if (!$attendance) {
            return response()->json(['error' => 'No se encontró un registro de asistencia para este usuario'], 404);
        }

        // Actualizar el registro de asistencia con la hora de salida solo si aún no se ha registrado
        if (!$attendance->end_time) {
            $attendance->end_time = now()->toTimeString();
            $attendance->save();
            return response()->json(['message' => 'Salida registrada correctamente'], 200);
        } else {
            return response()->json(['error' => 'El usuario ya ha registrado la salida anteriormente'], 400);
        }
    }

    // public function getUpdateTime($id)
    // {
    //     // Obtener el ID del usuario autenticado
    //     $userId = auth()->user()->id;

    //     // Buscar la entrada de asistencia por su ID y el ID del usuario autenticado
    //     $attendance = Attendance::where('id', $id)
    //         ->where('employees_id', $userId)
    //         ->first();

    //     if (!$attendance) {
    //         // Si no se encuentra la entrada o no pertenece al usuario, puedes manejar el caso aquí
    //         return response()->json(['error' => 'No se encontró la entrada de asistencia'], 404);
    //     }

    //     // Devolver el tiempo actual de la entrada
    //     return $attendance->current_time;
    // }
    public function getCurrentTime()
    {
        return response()->json(['current_time' => now()->toTimeString()]);
    }


    public function getStartTime()
    {
        $userId = auth()->user()->id;
        $attendance = Attendance::where('employees_id', $userId)->latest()->first();

        if ($attendance && $attendance->start_time) {
            return response()->json(['start_time' => $attendance->start_time], 200);
        } else {
            return response()->json(['error' => 'No se encontró el tiempo de inicio'], 404);
        }
    }


}
