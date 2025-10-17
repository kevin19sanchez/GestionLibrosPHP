<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = [
        'titulo',
        'id_categoria',
        'anio',
        'editorial',
        'ISBN',
        'estado',
    ];

    public function categoria() {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function autores(){
        return $this->belongsToMany(Autor::class, 'libros__autors', 'id_libro', 'id_autor');
    }
}
