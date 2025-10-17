<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allcategory = Libro::with('categoria')->get();
        $viewbooks = Libro::all();
        return view('books.books', compact('allcategory', 'viewbooks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bookcreate = new Libro;
        $bookcreate->titulo = $request->titulo;
        $bookcreate->id_categoria = $request->id_categoria;
        $bookcreate->anio = $request->anio;
        $bookcreate->editorial = $request->editorial;
        $bookcreate->ISBN = $request->ISBN;
        $bookcreate->estado = $request->estado;

        //dd($bookcreate);
        $bookcreate->save();

        return back()->with('mensaje', 'Libro Creado!!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bookupdate = Libro::findOrFail($id);
        $bookupdate->titulo = $request->titulo;
        $bookupdate->id_categoria = $request->id_categoria;
        $bookupdate->anio = $request->anio;
        $bookupdate->editorial = $request->editorial;
        $bookupdate->ISBN = $request->ISBN;
        $bookupdate->estado = $request->estado;

        //dd($bookupdate);
        $bookupdate->save();

        return back()->with('mensaje', 'Libro Editado!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bookdelete = Libro::findOrFail($id);
        $bookdelete->delete();

        return back()->with('mensaje', 'Libro Eliminado!!');
    }
}
