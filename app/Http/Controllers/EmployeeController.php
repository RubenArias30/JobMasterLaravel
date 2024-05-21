<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Employees;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employees::with('addresses', 'users')->whereHas('users', function ($query) {
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
            //$employee->photo = 'http://jobmaster.es/img/employees/' . $originalName;
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
                'street' => 'required|string',
                'city' => 'required|string',
                'postal_code' => 'required',
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

            // Actualizar la dirección del empleado
            $employee->addresses->update([
                'street' => $request->input('street'),
                'city' => $request->input('city'),
                'postal_code' => strlen($request->input('postal_code')) === 4 ? '0' . $request->input('postal_code') : $request->input('postal_code'),
            ]);

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

    public function updatePhoto(Request $request, $id)
    {
        try {
            $employee = Employees::findOrFail($id);

            // Verificar si hay una nueva foto enviada
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $extension = $photo->getClientOriginalExtension();

                // Validar que el archivo sea una imagen
                if (!in_array($extension, ['png', 'jpg', 'jpeg'])) {
                    throw new \Exception('El archivo no es una imagen válida. La foto existente se mantendrá.');
                }

                // Eliminar la foto existente
                if ($employee->photo) {
                    $existingPhotoPath = public_path('img/employees/') . basename($employee->photo);
                    if (file_exists($existingPhotoPath)) {
                        unlink($existingPhotoPath);
                    }
                }

                // Mover la nueva foto al directorio de imágenes
                $fileName = 'employee_' . $id . '.' . $extension;
                $photo->move(public_path('img/employees'), $fileName);
                $employee->photo = 'http://localhost:8000/img/employees/' . $fileName . '?timestamp=' . now()->timestamp;
                $employee->save();
            }

            return response()->json($employee);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la foto del empleado: ' . $e->getMessage()], 500);
        }
    }


    public function checkNifExists($nif)
    {
        try {
            // Buscar un empleado con el mismo NIF en la base de datos
            $existingEmployee = Employees::whereHas('users', function ($query) use ($nif) {
                $query->where('nif', $nif);
            })->first();

            // Si se encuentra un empleado con el mismo NIF, devuelve true
            if ($existingEmployee) {
                return response()->json(true);
            }

            // Si no se encuentra ningún empleado con el mismo NIF, devuelve false
            return response()->json(false);
        } catch (\Exception $e) {
            // Si ocurre algún error, devuelve un mensaje de error
            return response()->json(['error' => 'Error al verificar el NIF: ' . $e->getMessage()], 500);
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

