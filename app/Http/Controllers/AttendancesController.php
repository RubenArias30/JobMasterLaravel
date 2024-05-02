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

}
