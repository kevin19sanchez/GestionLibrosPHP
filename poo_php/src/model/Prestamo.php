<?php
    declare(strict_types=1);

    final class Prestamo{
        private $id;
        private Libro $libro;
        private Usuario $usuario;
        private $fecha_prestamo;
        private $fecha_devolver;
        private $fecha_devuelto;

        public function __construct( $id,  $libro,  $usuario,  $fecha_prestamo,  $fecha_devolver,  $fecha_devuelto){
            $this->id = $id;
            $this->libro = $libro;
            $this->usuario = $usuario;
            $this->fecha_prestamo = $fecha_prestamo;
            $this->fecha_devolver = $fecha_devolver;
            $this->fecha_devuelto = $fecha_devuelto;
        }

    public function getId(){return $this->id;}
	public function getLibro() {return $this->libro;}
	public function getUsuario() {return $this->usuario;}
	public function getFechaPrestamo() {return $this->fecha_prestamo;}
	public function getFechaDevolver() {return $this->fecha_devolver;}
	public function getFechaDevuelto() {return $this->fecha_devuelto;}
    }
?>