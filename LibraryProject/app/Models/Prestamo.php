<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $fillable = [
        'id_libro',
        'id_usuarios',
        'fecha_prestamo',
        'fecha_devolver',
        'fecha_devuelto'
    ];

    public function libro(){
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'id_usuarios');
    }
}
