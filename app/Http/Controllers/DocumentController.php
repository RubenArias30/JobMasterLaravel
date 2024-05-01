<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    // Método para obtener todos los documentos
    public function index(Request $request, $employeeId)
    {
        // Encuentra el empleado por su ID
        $employee = Employees::findOrFail($employeeId);

        // Obtén los documentos asociados al empleado
        $documents = $employee->documents;

        return response()->json($documents);
    }

    public function store(Request $request, $employeeId)
    {
        // Valida los datos del formulario
        $validatedData = $request->validate([
            'type_documents' => 'required',
            'name' => 'required',
            'description' => 'required',
            'date' => 'required|date',
            'route' => 'required', // Puedes agregar más reglas de validación según tus necesidades
        ]);

        // Encuentra el empleado por su ID
        $employee = Employees::findOrFail($employeeId);

        // Crea un nuevo documento asociado al empleado
        $document = new Documents($validatedData);
        $employee->documents()->save($document);

        // Retorna el documento recién creado
        return response()->json($document, 201);
    }

    // Método para eliminar un documento por su ID
    public function destroy($id)
    {
        // Busca el documento por su ID
        $document = Documents::find($id);

        // Verifica si el documento existe
        if (!$document) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }

        // Obtén el ID del empleado asociado al documento
        $employeeId = $document->employees_id;

        // Elimina el documento de la base de datos
        $document->delete();

        return response()->json(['message' => 'Documento eliminado correctamente']);
    }
}
