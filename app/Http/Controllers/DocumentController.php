<?php

namespace App\Http\Controllers;
use App\Models\Documents;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
     // Método para obtener todos los documentos
     public function index()
     {
   // Obtener el tipo de documento "contracts"
   $type = 'contracts'; // Puedes ajustar este valor según tus necesidades

   // Obtener todos los documentos filtrados por el tipo de documento
   $documents = Document::where('type_documents', $type)->get();

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
}
