<?php

namespace App\Http\Controllers;
use App\Models\Documents;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
     // Método para obtener todos los documentos
     public function index(Request $request)
    {
        $employeeId = $request->query('employeeId');

        // Filtrar los documentos por el ID del empleado
        $documents = Documents::where('employee_id', $employeeId)->get();

        return response()->json($documents);
    }

     // Método para almacenar un nuevo documento
     public function store(Request $request)
     {
         return Documents::create($request->all());
     }

     // Método para eliminar un documento por su ID
     public function destroy(Documents $document)
     {
         $document->delete();
         return response()->noContent();
     }

     public function getDocumentsByEmployee($employeeId)
     {
         // Obtener documentos por ID de empleado
         $documents = Documents::where('employees_id', $employeeId)->get();

         // Comprobar si se encontraron documentos
         if ($documents->isEmpty()) {
             return response()->json(['message' => 'No hay documentos disponibles para este empleado.'], 404);
         }

         return response()->json($documents, 200);
     }

}

