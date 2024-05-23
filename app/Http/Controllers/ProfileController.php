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
            // Get the ID of the authenticated user
            $userId = Auth::user()->id;

        // Find the profile of the employee associated with the authenticated user
        $employee = Employees::where('users_id', $userId)
            ->with(['addresses', 'users']) // Cargar datos de las relaciones
            ->first();

            return response()->json($employee);
            // // Check if the employee profile was found
            // if ($employee) {
            //     // If found, return the employee profile data
            //     return response()->json(['success' => true, 'profile' => $employee]);
            // } else {
            //     // If the profile was not found, return an error message
            //     return response()->json(['success' => false, 'message' => 'Perfil de empleado no encontrado'], 404);
            // }
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            return response()->json(['success' => false, 'message' => 'Ha ocurrido un error al obtener el perfil del empleado'], 500);
        }
    }

}
