<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
    // Validate the incoming request
    $validatedData = $request->validate([
        'type_documents' => 'required|string',
        'name' => 'required|string',
        'description' => 'required|string',
        'date' => 'required|date',
        'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
    ]);

    // Find the employee by ID
    $employee = Employees::findOrFail($employeeId);

    // Handle the file upload
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('documents', 'public');
        $validatedData['route'] = $filePath;
    }

    // Create a new document associated with the employee
    $document = new Documents($validatedData);
    $employee->documents()->save($document);

    // Return the newly created document
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

    public function download($documentId)
{
    // Find the document by ID
    $document = Documents::findOrFail($documentId);

    // Get the file path from the route field
    $filePath = $document->route;

    // Check if the file exists in storage
    if (Storage::disk('public')->exists($filePath)) {
        // If the file exists, return it as a download
        return response()->download(storage_path('app/public/' . $filePath));
    }

    // If the file doesn't exist, handle the error accordingly
    // Here, I'm returning a response with a JSON message, but you might want to adjust this
    return response()->json(['message' => 'File not found'], 404);
}
}
