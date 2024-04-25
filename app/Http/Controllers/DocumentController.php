<?php

namespace App\Http\Controllers;
use App\Models\Documents;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
     // Método para obtener todos los documentos
     public function index(Request $request, $employeeId)
     {
         // Filtrar los documentos por el ID del empleado
         $documents = Documents::whereHas('employees', function($query) use ($employeeId) {
             $query->where('id', $employeeId);
         })->get();

         return response()->json($documents);
     }

     // Método para almacenar un nuevo documento
     public function store(Request $request)
     {
         return Documents::create($request->all());
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

