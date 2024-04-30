<?php

namespace App\Http\Controllers;
use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
         // Validar los datos de la solicitud
         $validator = Validator::make($request->all(), [
             'type_documents' => 'required',
             'name' => 'required',
             'description' => 'required',
             'date' => 'required|date',
            //  'file' => 'required|mimes:pdf,doc,docx|max:2048', // Ajustar el tamaño máximo del archivo según sea necesario
         ]);

         // Si la validación falla, devolver los errores en una respuesta JSON
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }

          // Verificar si se ha enviado un archivo en la solicitud
        if ($request->hasFile('route')) {
            // Crear y almacenar el documento
            $document = new Documents();
            $document->type_documents = $request->input('type_documents');
            $document->name = $request->input('name');
            $document->description = $request->input('description');
            $document->date = $request->input('date');
            // Acceder al archivo de la solicitud y almacenarlo en el directorio 'documents'
            $document->route = $request->file('route')->store('documents');
            $document->employee_id = $employeeId; // Asociar el documento al empleado
            $document->save();

            // Devolver una respuesta JSON con el documento creado y un código de estado 201 (Created)
            return response()->json(['message' => 'Documento agregado al empleado correctamente', 'document' => $document], 201);
        } else {
            // Manejar el caso en que no se envió ningún archivo
            return response()->json(['error' => 'No se ha enviado ningún archivo en la solicitud'], 422);
        }
         // Devolver una respuesta JSON con el documento creado y un código de estado 201 (Created)
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

