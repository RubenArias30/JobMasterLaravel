<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    // Método para obtener todos los documentos
    public function index(Request $request, $employeeId)
    {
        // Encuentra el empleado por su ID
        $employee = Employees::findOrFail($employeeId);

        // Obtén los documentos asociados al empleado
        $documents = $employee->documents;

        return response()->json($documents);
    }

    // Método para obtener los documentos del empleado autenticado
public function myDocuments()
{
    // Obtenemos el empleado autenticado
    $employee = Auth::user()->id;

    // Verificamos si el empleado existe
    if (!$employee) {
        return response()->json(['message' => 'Empleado no encontrado'], 404);
    }

    // Obtenemos los documentos asociados al empleado logeuado
    $documents = Documents::where('employees_id', $employee)->get();

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
            'route' => 'required',
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
