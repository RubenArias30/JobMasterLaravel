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
        $employee = new Employee();
        $employee->name = $validatedData['name'];
        $employee->surname = $validatedData['surname'];
        $employee->email = $validatedData['email'];
        $employee->date_of_birth = $validatedData['date_of_birth'];
        $employee->telephone = $validatedData['telephone'];
        $employee->country = $validatedData['country'];

        $employee->save();


        return response()->json($employees, 201);
    }
}

