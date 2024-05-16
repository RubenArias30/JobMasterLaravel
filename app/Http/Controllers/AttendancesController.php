<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employees;
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

        // Crear un nuevo registro de asistencia
        $attendance = new Attendance();

        // Configurar los datos del nuevo registro
        $attendance->date = now()->toDateString(); // Obtener la fecha actual
        $attendance->start_time = now()->toTimeString(); // Obtener la hora actual
        $attendance->current_time = now()->toTimeString(); // Guardar el tiempo actual como current_time
        $attendance->employees_id = $userId; // Asignar el ID del usuario autenticado como el ID del empleado
        $attendance->status = 'present'; // Actualiza el estado del empleado como "presente"

        // Guardar el nuevo registro en la base de datos
        $attendance->save();

        // Retorna una respuesta de éxito con código 201 (Created)
        return response()->json(['message' => 'Entrada registrada correctamente como presente'], 201);
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
            $attendance->status = 'finalized';
            $attendance->save();
            return response()->json(['message' => 'Salida registrada correctamente'], 200);
        } else {
            return response()->json(['error' => 'El usuario ya ha registrado la salida anteriormente'], 400);
        }
    }

    public function getEmployeeStatus()
    {
        // Obtener el ID de todos los empleados
        $employeeIds = Employees::pluck('id');

        // Filtrar el ID del administrador para excluirlo
        $adminId = 1;
        $employeeIds = $employeeIds->reject(function ($employeeId) use ($adminId) {
            return $employeeId === $adminId;
        });

        // Obtener el ID de los empleados con al menos una asistencia presente
        $presentEmployeeIds = Attendance::whereIn('employees_id', $employeeIds)
            ->where('status', 'present')
            ->distinct()
            ->pluck('employees_id');

        // Calcular el número de empleados inactivos
        $inactiveEmployeeCount = count($employeeIds) - count($presentEmployeeIds);

        return response()->json([
            'present_employee_count' => count($presentEmployeeIds),
            'inactive_employee_count' => $inactiveEmployeeCount
        ]);
    }


}
