<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getProfile(Request $request)
    {
   
        // Obtener el ID del usuario autenticado
        $userId = auth()->user()->id;
        // Buscar el perfil del empleado asociado al usuario autenticado
        $employee = Employees::where('users_id', $userId)->first();
        dd($userId);
        dd($employee);

        // Verificar si se encontró el perfil del empleado
        if ($employee) {
            // Si se encontró, devolver los datos del perfil del empleado
            return response()->json($employee);
        } else {
            // Si no se encontró el perfil, retornar un mensaje de error o un código de estado HTTP adecuado
            return response()->json(['error' => 'Perfil de empleado no encontrado'], 404);
        }
    }

}
