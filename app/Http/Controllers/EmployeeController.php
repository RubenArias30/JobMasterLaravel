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
        $employees = Employees::with('addresses', 'users')->whereHas('users', function($query) {
            $query->where('roles', '!=', 'admin');
        })->get();

        return response()->json($employees);
    }

    public function store(Request $request)
    {
        try {
            $userId = Auth::id();
            $request->validate([
                'name' => 'required',
                'surname' => 'required',
                'email' => 'required|email',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:masculino,femenino,otro',
                'telephone' => 'required',
                'street' => 'required',
                'city' => 'required',
                'postal_code' => 'required',
                'nif' => 'required',
                'password' => 'required',

            ]);

            $file = $request->file('photo');
            $uploadPath = "img/employees/";
            $originalName = $file->getClientOriginalName();
            $file->move($uploadPath, $originalName);

            // Crear un nuevo registro de empleado
            $employee = new Employees();
            $employee->name = $request['name'];
            $employee->surname = $request['surname'];
            $employee->email = $request['email'];
            $employee->date_of_birth = $request['date_of_birth'];
            $employee->telephone = $request['telephone'];
            $employee->country = $request['country'];
            $employee->photo = 'http://localhost:8000/img/employees/' . $originalName;
            $employee->users_id = $userId;

            // Guardar los datos de dirección
            $address = new Address();
            $address->street = $request['street'];
            $address->city = $request['city'];
            $address->postal_code = $request['postal_code'];
            $address->save();

            $employee->address_id = $address->id;

            // Guardar los datos de credenciales
            $credentials = new User();
            $credentials->nif = $request['nif'];
            $credentials->password = bcrypt($request->input('password'));
            $credentials->roles = 'empleado';
            $credentials->save();

            // Relacionar las credenciales con el empleado
            $employee->users_id = $credentials->id;

            $employee->save();

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el empleado: ' . $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
{
    try {
        // Buscamos al empleado por su ID
        $employee = Employees::find($id);

        // Verificamos si el empleado existe
        if (!$employee) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        // Validar los datos de entrada antes de actualizar
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'telephone' => 'required|string',
            'country' => 'required|string',
            'nif' => 'required|string',
        ]);

        // Actualizamos los datos del empleado
        $employee->update($request->only([
            'name',
            'surname',
            'email',
            'date_of_birth',
            'gender',
            'telephone',
            'country',
        ]));

        // Actualizar la imagen del empleado si se proporciona una nueva
        if ($request->hasFile('photo')) {
            // Eliminamos la foto anterior si existe
            if ($employee->photo) {
                // Eliminamos la imagen anterior
                $previousPhotoPath = public_path($employee->photo);
                if (file_exists($previousPhotoPath)) {
                    unlink($previousPhotoPath);
                }
            }

            // Subimos la nueva foto
            $file = $request->file('photo');
            $originalName = $file->getClientOriginalName();
            $photoPath = 'img/employees/' . $originalName;
            $file->move(public_path('img/employees'), $originalName);

            // Actualizamos el campo 'photo' en la base de datos
            $employee->photo = $photoPath;
        }

        // Actualizamos los datos de credenciales
        if ($employee->users) {
            $employee->users->update([
                'nif' => $request->input('nif'),
            ]);
        }

        return response()->json($employee, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al actualizar el empleado: ' . $e->getMessage()], 500);
    }
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

    public function show($id)
    {
        $employee = Employees::with('addresses', 'users')->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        return response()->json($employee);
    }

}

