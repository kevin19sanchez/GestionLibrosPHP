<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Usuario;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alllibros = Libro::all();
        $allusuarios = Usuario::all();
        $allprestamo = Prestamo::all();
        return view('loans.loans', compact('alllibros', 'allusuarios', 'allprestamo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_usuarios' => 'required|exists:usuarios,id',
            'id_libro' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolver' => 'required|date|after:fecha_prestamo',
        ]);

        //verificar si el libro esta disponible
        $libro = Libro::findOrFail($request->id_libro);
        if ($libro->estado !== 'disponible') {
            return redirect()->back()->withErrors(['El libro no está disponible para préstamo.']);
        }

        $loanscreate = new Prestamo;
        $loanscreate->id_libro = $request->id_libro;
        $loanscreate->id_usuarios = $request->id_usuarios;
        $loanscreate->fecha_prestamo = $request->fecha_prestamo;
        $loanscreate->fecha_devolver = $request->fecha_devolver;
        $loanscreate->fecha_devuelto = null;

        //dd($loanscreate);
        $loanscreate->save();

        $libro->update(['estado' => 'prestado']);
        //dd($libro);

        return back()->with('mensaje', 'Prestamo Creado!!');
    }

    public function devolver(Request $request, $id){
        $prestamo = Prestamo::findOrFail($id);

        if($prestamo->fecha_devuelto){
            return back()->with('warning', 'Este préstamo ya fue devuelto.');
        }

        $prestamo->fecha_devuelto = now()->format('Y-m-d');
        $prestamo->save();

        Libro::where('id', $prestamo->id_libro)->update(['estado' => 'disponible']);

        return back()->with('mensaje', '¡Libro devuelto correctamente!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
