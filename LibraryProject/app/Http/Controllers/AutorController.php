<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allauthor = Autor::all();
        return view('authors.autor', compact('allauthor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authorcreate = new Autor;
        $authorcreate->nombre_autor = $request->nombre_autor;
        $authorcreate->nacionalidad = $request->nacionalidad;

        //dd($authorcreate);
        $authorcreate->save();

        return back()->with('mensaje', 'Autor Creado!!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $authorupdate = Autor::findOrFail($id);
        $authorupdate->nombre_autor = $request->nombre_autor;
        $authorupdate->nacionalidad = $request->nacionalidad;

        //dd($authorupdate);
        $authorupdate->save();

        return back()->with('mensaje', 'Autor Editado!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $authordelete = Autor::findOrFail($id);
        $authordelete->delete();

        return back()->with('mensaje', 'Autor Eliminado!!');
    }
}
