<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewcategory = Categoria::all();
        return view('categorys.category', compact('viewcategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = new Categoria;
        $category->nombre_categoria = $request->nombre_categoria;

        //dd($category);

        $category->save();

        return back()->with('mensaje', 'Categoria Agregada!!!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categoryupdate = Categoria::findOrFail($id);
        $categoryupdate->nombre_categoria = $request->nombre_categoria;

        //dd($categoryupdate);
        $categoryupdate->save();

        return back()->with('mensaje', 'Categoria Editada!!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categorydelete = Categoria::findOrFail($id);
        $categorydelete->delete();

        return back()->with('mensaje', 'Categoria Eliminada!!!');
    }
}
