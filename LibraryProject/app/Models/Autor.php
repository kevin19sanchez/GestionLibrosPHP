<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $fillable = [
        'nombre_autor',
        'nacionalidad'
    ];

    public function libros(){
        return $this->belongsToMany(Libro::class, 'libros__autors', 'id_libro', 'id_autor');
    }
}
