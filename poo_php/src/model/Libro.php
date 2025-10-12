<?php
    declare(strict_types=1);

    final class Libro{
        private $id;
        private $titulo;
        private $anio;
        private Categoria $categoria;
        private $editorial;
        private $ISBN;
        private $estado;
        private $autores = [];

        public function __construct($id, $titulo, $anio, $categoria, $editorial, $ISBN, $estado) {
            $this->id = $id;
            $this->titulo = $titulo;
            $this->anio = $anio;
            $this->categoria = $categoria;
            $this-> editorial = $editorial;
            $this->ISBN = $ISBN;
            $this->estado = $estado;        
        }

    public function getId(){return $this->id;}
	public function getTitulo() {return $this->titulo;}
	public function getAnio() {return $this->anio;}
	public function getCategoria() {return $this->categoria;}
	public function getEditorial() {return $this->editorial;}
	public function getISBN() {return $this->ISBN;}
	public function getEstado() {return $this->estado;}

    public function agregarAutor(Autor $autor) {
        $this->autores[] = $autor;
    }

    public function getAutores() {
        return $this->autores;
    }

    }
?>