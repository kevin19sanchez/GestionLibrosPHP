<?php
    require_once __DIR__ . '/../model/Libro.php';
    require_once __DIR__ . '/../model/Autor.php';
    require_once __DIR__ . '/../model/Categoria.php';
    require_once __DIR__ . '/../model/Usuario.php';
    require_once __DIR__ . '/../model/Prestamo.php';

    class BibliotecaService{
        // conexion a la base de datos
        private $conections;

        public function __construct(){
            //cargar la conexion
            $this->conections = require_once __DIR__ . '/../../config/conection.php';

            if (!$this->conections instanceof mysqli) {
                die("Error: no se pudo establecer una conexión válida con la base de datos.");
            }
        }

        /**
         * LIBROS
         */

        public function agregarLibros($titulo, $anio, $id_categoria, $editorial, $ISBN) {
            $estado = "disponible";
            $stmt = $this->conections->prepare(
                "INSERT INTO Libros (titulo, anio, id_categoria, editorial, ISBN, estado) VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param("sissis", $titulo, $anio, $id_categoria, $editorial, $ISBN, $estado);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        }

        public function obtenerLibroPorId($id){
            $stmt = $this->conections->prepare(
                "SELECT id, titulo, anio, id_categoria, editorial, ISBN, estado FROM Libros WHERE id = ?"
            );

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $fila = $resultado->fetch_assoc();
            $stmt->close();

            if(!$fila) return null;

            $category = $this->obtenerCategoriaPorId($fila["id_categoria"]);
            $libro = new Libro(
                $fila['id'],
                $fila['titulo'],
                $fila['anio'],
                $category,
                $fila['editorial'],
                $fila['ISBN'],
                $fila['estado']
            );

            $autores = $this->obtenerAutoresPorLibroId($fila['id']);
            foreach ($autores as $autor) {
                $libro->agregarAutor($autor);
            }

            return $libro;
        }

        public function obtenerTodosLosLibros() {
            $libros = [];
            $resultado = $this->conections->query(
                "SELECT id, titulo, anio, id_categoria, editorial, ISBN, estado FROM Libros"
            );

            while($fila = $resultado->fetch_assoc()){
                $categoria = $this->obtenerCategoriaPorId($fila['id_categoria']);
                $libro = new Libro(
                    $fila['id'],
                    $fila['titulo'],
                    $fila['anio'],
                    $categoria,
                    $fila['editorial'],
                    $fila['ISBN'],
                    $fila['estado']
                );

                $autores = $this->obtenerAutoresPorLibroId($fila['id']);
                foreach ($autores as $autor) {
                    $libro->agregarAutor($autor);
                }
                $libros[] = $libro;
            }
            return $libros;
        }

        public function buscarLibros($titulo = null, $id_autor = null, $id_categoria = null) {
            $sql = "SELECT DISTINCT l.id, l.titulo, l.anio, l.id_categoria, l.editorial, l.ISBN, l.estado 
                FROM Libros l
                LEFT JOIN libros_autor la ON l.id = la.id_libro
                WHERE 1=1";

            $params = [];
            $tipos = "";

            if ($titulo !== null) {
                $sql .= " AND l.titulo LIKE ?";
                $params[] = "%$titulo%";
                $tipos .= "s";
            }

            if ($id_autor !== null) {
                $sql .= " AND la.id_autor = ?";
                $params[] = $id_autor;
                $tipos .= "i";
            }

            if ($id_categoria !== null) {
                $sql .= " AND l.id_categoria = ?";
                $params[] = $id_categoria;
                $tipos .= "i";
            }

            $stmt = $this->conections->prepare($sql);
            if (!empty($params)) {
                $stmt->bind_param($tipos, ...$params);
            }

            $stmt->execute();
            $resultado = $stmt->get_result();
            $libros = [];

            while($fila = $resultado->fetch_assoc()){
                $categoria = $this->obtenerCategoriaPorId($fila['id_categoria']);
                $libro = new Libro(
                    $fila['id'],
                    $fila['titulo'],
                    $fila['anio'],
                    $categoria,
                    $fila['editorial'],
                    $fila['ISBN'],
                    $fila['estado']
                );

                $autores = $this->obtenerAutoresPorLibroId($fila['id']);
                foreach ($autores as $autor) {
                    $libro->agregarAutor($autor);
                }
                $libros[] = $libro;
            }
            $stmt->close();
            return $libros;
        }

        public function editarLibros($id, $titulo, $anio, $id_categoria, $editorial, $ISBN) {
            $stmt = $this->conections->prepare(
                "UPDATE Libros SET titulo = ?, anio = ?, id_categoria = ?, editorial = ?, ISBN = ? WHERE id = ?"
            );

            $stmt->bind_param("sissii", $titulo, $anio, $id_categoria, $editorial, $ISBN, $id);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        }

        public function eliminarLibro($id) {
            $this->conections->query("DELETE FROM libros_autor WHERE id_libro = ?");
            $stmt = $this->conections->prepare("DELETE FROM Libros WHERE id = ?");
            $stmt->bind_param("i", $id);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        }

        /**
         * AUTORES
         */

        public function obtenerTodosLosAutores() {
            $autores = [];
            $resultado = $this->conections->query("SELECT id, nombre_autor, nacionalidad FROM Autor");

            while ($fila = $resultado->fetch_assoc()) {
                $autores[] = new Autor($fila['id'], $fila['nombre_autor'], $fila['nacionalidad']);
            }
            return $autores;
        }

        private function obtenerAutoresPorLibroId($id_libro) {
            $autores = [];
            $stmt = $this->conections->prepare(
                "SELECT a.id, a.nombre_autor, a.nacionalidad 
                FROM Autor a 
                INNER JOIN libros_autor la ON a.id = la.id_autor 
                WHERE la.id_libro = ?"
            );

            $stmt->bind_param("i", $id_libro);
            $stmt->execute();
            $resultado = $stmt->get_result();

            while ($fila = $resultado->fetch_assoc()) {
                $autores[] = new Autor($fila['id'], $fila['nombre_autor'], $fila['nacionalidad']);
            }

            $stmt->close();
            return $autores;
        }

        /**
         * CATEGORIAS
         */

        public function obtenerTodasLasCategorias() {
            $categorias = [];
            $resultado = $this->conections->query("SELECT id, nombre_categoria FROM Categoria");

            while ($fila = $resultado->fetch_assoc()) {
                $categorias[] = new Categoria($fila['id'], $fila['nombre_categoria']);
            }

            return $categorias;
        }

        private function obtenerCategoriaPorId($id) {
            $stmt = $this->conections->prepare("SELECT id, nombre_categoria FROM Categoria WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $fila = $resultado->fetch_assoc();
            $stmt->close();

            if ($fila) {
                return new Categoria($fila['id'], $fila['nombre_categoria']);
            }
            
            return null;
        }

        /**
         * USUARIOS
         */

        public function obtenerUsuarioPorId($id) {
            $stmt = $this->conections->prepare("SELECT id, nombre, apellido, correo FROM Usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $fila = $resultado->fetch_assoc();
            $stmt->close();

            if ($fila) {
                return new Usuario($fila['id'], $fila['nombre'], $fila['apellido'], $fila['correo']);
            }

            return null;
        }

        /**
         * PRESTAMOS
         */

        public function prestarLibro($id_libro, $id_usuario, $dias_prestamo = 7) {
            $libro = $this->obtenerLibroPorId($id_libro);

            if(!$libro || $libro->getEstado() !== 'disponible'){
                return false;
            }

            $fecha_prestamo = date('Y-m-d H:i:s');
            $fecha_devolver = date('Y-m-d H:i:s', strtotime("+$dias_prestamo"));

            $stmt = $this->conections->prepare(
                "INSERT INTO Prestamo (id_libro, id_usuario, fecha_prestamo, fecha_devolver, fecha_devuelto) 
                VALUES (?, ?, ?, ?, NULL)"
            );

            $stmt->bind_param("iiss", $id_libro, $id_usuario, $fecha_prestamo, $fecha_devolver);
            $resultado = $stmt->execute();
            $stmt->close();

            if ($resultado) {
                $stmt = $this->conections->prepare("UPDATE Libros SET estado = 'prestado' WHERE id = ?");
                $stmt->bind_param("i", $id_libro);
                $stmt->execute();
                $stmt->close();
            }

            return $resultado;
        }

        public function devolverLibro($id_prestamo) {
            $fecha_devuelto = date('Y-m-d H:i:s');

            $stmt = $this->conections->prepare("SELECT id_libro FROM Prestamo WHERE id = ?");
            $stmt->bind_param("i", $id_prestamo);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $fila = $resultado->fetch_assoc();
            $stmt->close();

            if (!$fila) {
                return false;
            }

            $id_libro = $fila["id_libro"];

            $stmt = $this->conections->prepare("UPDATE Prestamo SET fecha_devuelto = ? WHERE id = ?");
            $stmt->bind_param("si", $fecha_devuelto, $id_prestamo);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->conections->prepare("UPDATE Libros SET estado = 'disponible' WHERE id = ?");
            $stmt->bind_param("i", $id_libro);
            $resultado = $stmt->execute();
            $stmt->close();

            return $resultado;
        }

        public function obtenerPrestamosActivos() {
            $prestamos = [];
            $resultado = $this->conections->query(
                "SELECT id, id_libro, id_usuario, fecha_prestamo, fecha_devolver, fecha_devuelto 
                FROM Prestamo WHERE fecha_devuelto IS NULL"
            );

            while($fila = $resultado->fetch_assoc()){
                $libro = $this->obtenerLibroPorId($fila['id_libro']);
                $usuario = $this->obtenerUsuarioPorId($fila['id_usuario']);

                if ($libro && $usuario) {
                    $prestamos[] = new Prestamo(
                        $fila['id'],
                        $libro,
                        $usuario,
                        $fila['fecha_prestamo'],
                        $fila['fecha_devolver'],
                        $fila['fecha_devuelto']
                    );
                }
            }
            return $prestamos;
        }

        public function __destruct(){
            if ($this->conections && $this->conections->ping()) {
                $this->conections->close();
            }
        }
    }
?>