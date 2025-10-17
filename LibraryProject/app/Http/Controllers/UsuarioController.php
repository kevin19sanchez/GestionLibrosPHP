<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allusers = Usuario::all();
        return view('users.users', compact('allusers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usercreate = new Usuario;
        $usercreate->nombre = $request->nombre;
        $usercreate->apellido = $request->apellido;
        $usercreate->correo = $request->correo;

        //dd($usercreate);
        $usercreate->save();

        return back()->with('mensaje', 'Usuario Creado!!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $userupdate = Usuario::findOrFail($id);
        $userupdate->nombre = $request->nombre;
        $userupdate->apellido = $request->apellido;
        $userupdate->correo = $request->correo;

        //dd($userupdate);
        $userupdate->save();

        return back()->with('mensaje', 'Usuario Editado!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $userdelete = Usuario::findOrFail($id);
        $userdelete->delete();

        return back()->with('mensaje', 'Usuario Eliminado!!');
    }
}
