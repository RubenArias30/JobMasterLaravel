<?php

namespace App\Http\Controllers;
use App\Models\Employees; // Importa el modelo Employee

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
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'date_of_birth'=> 'required',
            'telephone' => 'required',
            'country' => 'required'

        ]);

        // $employees = Employees::create($request->all());

        // Create a new employee record
        $employee = new Employees();
        $employee->name = $request['name'];
        $employee->surname = $request['surname'];
        $employee->email = $request['email'];
        $employee->date_of_birth = $request['date_of_birth'];
        $employee->telephone = $request['telephone'];
        $employee->country = $request['country'];

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

        // Eliminar al empleado
        $employee->delete();

        return response()->json(['message' => 'Empleado eliminado exitosamente']);
    }
}

