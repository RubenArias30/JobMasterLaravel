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

        /**
     * Method to get all documents associated with an employee.
     * @param Request $request
     * @param int $employeeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $employeeId)
    {
        // Find the employee by their ID
        $employee = Employees::findOrFail($employeeId);

        // Get the documents associated with the employee
        $documents = $employee->documents;

        return response()->json($documents);
    }

   /**
     * Method to get documents of the authenticated employee.
     * @return \Illuminate\Http\JsonResponse
     */public function myDocuments()
{
        // Get the authenticated employee
        $employee = Auth::user()->id;

        // Check if the employee exists
        if (!$employee) {
        return response()->json(['message' => 'Empleado no encontrado'], 404);
    }

        // Get the documents associated with the logged-in employee
        $documents = Documents::where('employees_id', $employee)->get();

    return response()->json($documents);
}

  /**
     * Method to store a new document associated with an employee.
     * @param Request $request
     * @param int $employeeId
     * @return \Illuminate\Http\JsonResponse
     */
public function store(Request $request, $employeeId)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'type_documents' => 'required|string',
        'name' => 'required|string',
        'description' => 'required|string',
        'date' => 'required|date',
        'file' => 'required|file|mimes:pdf,doc,docx',
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

   /**
     * Method to delete a document by its ID.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */  
      public function destroy($id)
    {
        // Find the document by its ID
        $document = Documents::find($id);

        // Check if the document exists
        if (!$document) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }

        // Get the ID of the employee associated with the document
        $employeeId = $document->employees_id;

        // Delete the document from the database
        $document->delete();

        return response()->json(['message' => 'Documento eliminado correctamente']);
    }

    /**
     * Method to download a document by its ID.
     * @param int $documentId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
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
