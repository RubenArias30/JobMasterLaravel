<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Employees;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employees::all();
        return response()->json($employees);
    }

    public function store(Request $request)
    {

        $userId = Auth::id();
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:masculino,femenino,otro',
            'telephone' => 'required',
            'street' => 'required',
            'number' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'nif' => 'required',
            'password' => 'required',

        ]);

        // Create a new employee record
        $employee = new Employees();
        $employee->name = $request['name'];
        $employee->surname = $request['surname'];
        $employee->email = $request['email'];
        $employee->date_of_birth = $request['date_of_birth'];
        $employee->telephone = $request['telephone'];
        $employee->country = $request['country'];
        $employee->photo = $request['photo'];
        $employee->users_id = $userId;

        // Guardar los datos de dirección
        $address = new Address();
        $address->street = $request['street'];
        $address->number = $request['number'];
        $address->city = $request['city'];
        $address->postal_code = $request['postal_code'];
        $address->save();

        $employee->address_id = $address->id;

        // Guardar los datos de credenciales
        $credentials = new User();
        $credentials->nif = $request['nif'];
        $credentials->password = bcrypt($request->input('password'));
        $credentials->roles = 'empleado'; // Asignar el rol de empleado
        $credentials->save();

        // Relacionar las credenciales con el empleado
        $employee->users_id = $credentials->id;


        $employee->save();


        return response()->json($employee, 201);
    }

    // Método para eliminar un empleado
    public function delete($id)
    {
        // Buscar al empleado por su ID
        $employee = Employees::find($id);

        // Verificar si el empleado existe
        if (!$employee) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        // Verificar si existen registros relacionados en la tabla users
        if ($employee->user) {
            // Si hay un usuario relacionado, eliminarlo
            $employee->user()->delete();
        }

        // Verificar si existen registros relacionados en la tabla addresses
        if ($employee->address) {
            // Si hay una dirección relacionada, eliminarla
            $employee->address()->delete();
        }

        // Finalmente, eliminar al empleado
        $employee->delete();

        return response()->json(['message' => 'Empleado eliminado exitosamente']);
    }

}

