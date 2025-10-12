<?php
    declare(strict_types=1);

    final class Autor{
        private $id;
        private $nombre_autor;
        private $nacionalidad;

        public function __construct($id, $nombre_autor, $nacionalidad) {
            $this->id = $id;
            $this->nombre_autor = $nombre_autor;
            $this->nacionalidad = $nacionalidad;
        }

    public function getId(){return $this->id;}
	public function getNombreAutor() {return $this->nombre_autor;}
	public function getNacionalidad() {return $this->nacionalidad;}
    }
?>