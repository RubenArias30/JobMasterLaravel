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

     public function store(Request $request, $employeeId)
    {
        // Primero, validamos la solicitud
        $request->validate([
            'type' => 'required',
            'name' => 'required',
            'description' => 'required',
            'date' => 'required|date',
            'file' => 'required|mimes:pdf,doc,docx|max:2048', // Adjust the max file size as needed
        ]);

        // Verificamos si el empleado existe
        $employee = Employees::find($employeeId);
        if (!$employee) {
            return response()->json(['error' => 'El empleado no existe'], 404);
        }

        // Guardamos el documento asociado al empleado
        $document = new Documents();
        $document->type = $request->input('type');
        $document->name = $request->input('name');
        $document->description = $request->input('description');
        $document->date = $request->input('date');
        // Guardamos el archivo en storage (o en el sistema de archivos, dependiendo de tu configuración)
        $document->file = $request->file('file')->store('documents');
        // Asociamos el documento al empleado
        $document->employee_id = $employeeId;
        $document->save();

        return response()->json(['message' => 'Documento agregado al empleado correctamente', 'document' => $document], 201);
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

