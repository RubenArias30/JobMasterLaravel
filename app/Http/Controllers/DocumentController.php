<?php

namespace App\Http\Controllers;
use App\Models\Documents;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
     // Método para obtener todos los documentos
     public function index()
     {
         return Documents::all();
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
