<?php
    declare(strict_types=1);

    final class Categoria{
        private $id;
        private $nombre_categoria;

        public function __construct($id, $nombre_categoria) {
            $this->id = $id;
            $this->nombre_categoria = $nombre_categoria;
        }

    public function getId(){return $this->id;}
	public function getNombreCategoria() {return $this->nombre_categoria;}
    }
?>