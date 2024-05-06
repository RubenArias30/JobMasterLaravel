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
        try {
            // Obtener el ID del usuario autenticado
            $userId = Auth::user()->id;

            // Buscar el perfil del empleado asociado al usuario autenticado
            $employee = Employees::where('users_id', $userId)->with('addresses','users')->first();

            return response()->json($employee);
            // // Verificar si se encontró el perfil del empleado
            // if ($employee) {
            //     // Si se encontró, devolver los datos del perfil del empleado
            //     return response()->json(['success' => true, 'profile' => $employee]);
            // } else {
            //     // Si no se encontró el perfil, retornar un mensaje de error
            //     return response()->json(['success' => false, 'message' => 'Perfil de empleado no encontrado'], 404);
            // }
        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir
            return response()->json(['success' => false, 'message' => 'Ha ocurrido un error al obtener el perfil del empleado'], 500);
        }
    }

}
