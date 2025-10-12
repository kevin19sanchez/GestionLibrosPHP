<?php
    declare(strict_types=1);

    final class Usuario{
        private $id;
        private $nombre;
        private $apellido;
        private $correo;

        public function __construct( $id,  $nombre,  $apellido,  $correo){
            $this->id = $id;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->correo = $correo;
        }

    public function getId() {return $this->id;}
	public function getNombre() {return $this->nombre;}
	public function getApellido() {return $this->apellido;}
	public function getCorreo() {return $this->correo;}
    }
?>