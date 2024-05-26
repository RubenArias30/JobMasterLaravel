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
        /**
     * Method to retrieve all employees.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $employees = Employees::with('addresses', 'users')->whereHas('users', function ($query) {
            $query->where('roles', '!=', 'admin');
        })->get();

        return response()->json($employees);
    }
    /**
     * Method to store a new employee.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            // $uploadPath = "img/employees/";
            $uploadPath = "assets/employees/";
            $originalName = $file->getClientOriginalName();
            $file->move($uploadPath, $originalName);

            // Create a new employee record
            $employee = new Employees();
            $employee->name = $request['name'];
            $employee->surname = $request['surname'];
            $employee->email = $request['email'];
            $employee->date_of_birth = $request['date_of_birth'];
            $employee->telephone = $request['telephone'];
            $employee->country = $request['country'];
            // $employee->photo = 'http://localhost:8000/img/employees/' . $originalName;
            //$employee->photo = 'http://jobmaster.es/img/employees/' . $originalName;
            $employee->photo = url('assets/employees/' . $originalName);
            $employee->users_id = $userId;

            // Save address data
            $address = new Address();
            $address->street = $request['street'];
            $address->city = $request['city'];
            $address->postal_code = $request['postal_code'];
            $address->save();

            $employee->address_id = $address->id;

            // Save credentials data
            $credentials = new User();
            $credentials->nif = $request['nif'];
            $credentials->password = bcrypt($request->input('password'));
            $credentials->roles = 'empleado';
            $credentials->save();

            // Associate credentials with the employee
            $employee->users_id = $credentials->id;

            $employee->save();

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el empleado: ' . $e->getMessage()], 500);
        }
    }

  /**
     * Method to update employee details.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the employee by their ID
            $employee = Employees::find($id);

            // Check if the employee exists
            if (!$employee) {
                return response()->json(['message' => 'Empleado no encontrado'], 404);
            }

            // Validate input data before updating
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

            // Update employee data
            $employee->update($request->only([
                'name',
                'surname',
                'email',
                'date_of_birth',
                'gender',
                'telephone',
                'country',
            ]));

            // Update employee address
            $employee->addresses->update([
                'street' => $request->input('street'),
                'city' => $request->input('city'),
                'postal_code' => strlen($request->input('postal_code')) === 4 ? '0' . $request->input('postal_code') : $request->input('postal_code'),
            ]);

            // Update credentials data
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
    /**
     * Method to update employee photo.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhoto(Request $request, $id)
    {
        try {
            $employee = Employees::findOrFail($id);

            // Check if a new photo is sent
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $extension = $photo->getClientOriginalExtension();

                // Validate that the file is an image
                if (!in_array($extension, ['png', 'jpg', 'jpeg'])) {
                    throw new \Exception('El archivo no es una imagen válida. La foto existente se mantendrá.');
                }

                // Delete the existing photo
                if ($employee->photo) {
                    $existingPhotoPath = public_path('img/employees/') . basename($employee->photo);
                    if (file_exists($existingPhotoPath)) {
                        unlink($existingPhotoPath);
                    }
                }

                // Move the new photo to the images directory
                $fileName = 'employee_' . $id . '.' . $extension;
                $photo->move(public_path('img/employees'), $fileName);
                // $employee->photo = 'http://localhost:8000/img/employees/' . $fileName . '?timestamp=' . now()->timestamp;
                $employee->photo = 'https://jobmaster.es/img/employees/' . $fileName . '?timestamp=' . now()->timestamp;
                $employee->save();
            }

            return response()->json($employee);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la foto del empleado: ' . $e->getMessage()], 500);
        }
    }

 /**
     * Method to check if a NIF already exists.
     * @param string $nif
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNifExists($nif)
    {
        try {
            // Search for an employee with the same NIF in the database
            $existingEmployee = Employees::whereHas('users', function ($query) use ($nif) {
                $query->where('nif', $nif);
            })->first();

            // If an employee with the same NIF is found, return true
            if ($existingEmployee) {
                return response()->json(true);
            }

            // If no employee with the same NIF is found, return false
            return response()->json(false);
        } catch (\Exception $e) {
            // If an error occurs, return an error message
            return response()->json(['error' => 'Error al verificar el NIF: ' . $e->getMessage()], 500);
        }
    }

   /**
     * Method to delete an employee.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        // Find the employee by their ID
        $employee = Employees::find($id);

        // Check if the employee exists
        if (!$employee) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        // Check if there are related records in the users table
        if ($employee->users) {
            // If there is a related user, delete it
            $employee->users()->delete();
        }

        // Check if there are related records in the addresses table
        if ($employee->address) {
            // If there is a related address, delete it
            $employee->address()->delete();
        }

        // Finally, delete the employee
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

