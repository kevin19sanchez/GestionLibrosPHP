<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) {

        $query = Libro::with(['categoria', 'autores']);

        ///BUSQUEDA GLOBAL
        if($request->filled('buscar')){
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar){
                $q->where('titulo', 'LIKE', "%{$buscar}%")
                ->orWhere('ISBN', 'LIKE', "%{$buscar}%");
            })
            ->orWhereHas('autores', function ($q) use ($buscar){
                $q->where('nombre_autor', 'LIKE', "%{$buscar}%");
            });
        }

        //filtro por autor
        if($request->filled('id_autor')){
            $query->whereHas('autores', function ($q) use ($request){
                $q->where('id', $request->id_autor);
            });
        }

        //filtro por categoria
        if($request->filled('id_categoria')){
            $query->where('id_categoria', $request->id_categoria);
        }

        ///filtro por disponibilidad
        if($request->filled('disponibilidad')){
            $query->where('estado', $request->disponibilidad);
        }

        ///rango de años
        if($request->filled('anio_desde')){
            $query->where('anio', $request->anio_desde);
        }

        if($request->filled('anio_hasta')){
            $query->where('anio', '<=', $request->anio_hasta);
        }

        //obtener resultados
        $libros = $query->paginate(10);
        $autores = Autor::all();
        $categoria = Categoria::all();

        $allautores = Autor::all();
        $allcategorias = Categoria::all();
        return view('dashboard.dash',
        compact('allautores', 'allcategorias', 'libros','autores','categoria'));
    }
}
