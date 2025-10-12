<?php
    require_once __DIR__ . '/../src/service/BibliotecaService.php';

    $servicio = new BibliotecaService();
    $mensaje = '';

    /**
     * PROCERSAR FORMULARIOS
     */

    /** AGREGAR LIBRO */
    if ($_POST['accion'] ?? null === 'agregar_libro') {
        $titulo = trim($_POST['titulo'] ?? '');
        $anio = $_POST['anio'] ?? '';
        $id_categoria = $_POST['id_categoria'] ?? null;
        $editorial = trim($_POST['editorial'] ?? '');
        $ISBN = trim($_POST['ISBN'] ?? '');
        $autores = $_POST['autores'] ?? [];

        if ($titulo && $anio && $id_categoria && $editorial && $ISBN && !empty($autores)) {
            //insertar libro
            if ($servicio->agregarLibros($titulo, $anio, $id_categoria, $editorial, $ISBN)) {
                //obtener el ID del libro insertado
                $ultimo_id = $servicio->obtenerTodosLosLibros();
                $id_libro = end($ultimo_id)->getId();

                //insertar relaciones en libros_autor
                $conexion_path = __DIR__ . '/../config/conection.php';
                if(file_exists($conexion_path)){
                    $conexion_temp = include $conexion_path;
                    if($conexion_temp instanceof mysqli){
                        foreach ($autores as $id_autor) {
                            $stmt = $conexion_temp->prepare("INSERT INTO libros_autor (id_libro, id_autor) VALUES (?, ?)");
                            $stmt->bind_param("ii", $id_libro, $id_autor);
                            $stmt->execute();
                            $stmt->close();
                        }
                        $conexion_temp->close();
                    }else{
                        $mensaje = "❌ Error: conexión no válida para insertar autores.";
                    }
                }else{
                    $mensaje = "❌ Error: archivo de conexión no encontrado.";
                }
                $mensaje = "✅ Libro agregado correctamente.";
            }else{
                $mensaje = "❌ Error al agregar el libro.";
            }
        }else{
            $mensaje = "❌ Todos los campos son obligatorios.";
        }
    }

    /**BUSCAR LIBRO */
    $libros_mostrar = $servicio->obtenerTodosLosLibros();
    if ($_POST['accion'] ?? null === 'buscar') {
        $titulo = !empty($_POST['buscar_titulo']) ? $_POST['buscar_titulo'] : null;
        $id_autor = !empty($_POST['buscar_autor']) ? $_POST['buscar_autor'] : null;
        $id_categoria = !empty($_POST['buscar_categoria']) ? $_POST['buscar_categoria'] : null;
        $libros_mostrar = $servicio->buscarLibros($titulo, $id_autor, $id_categoria);
    }

    /** PRESTAR LIBRO */
    if ($_POST['accion'] ?? null === 'prestar') {
        $id_libro = $_POST['id_libro_prestamo'] ?? null;
        $id_usuario = $_POST['id_usuario_prestamo'] ?? null;
        if ($id_libro && $id_usuario) {
            if ($servicio->prestarLibro($id_libro, $id_usuario)) {
                $mensaje = "✅ Libro prestado correctamente.";
            } else {
                $mensaje = "❌ No se pudo prestar el libro (puede estar ya prestado).";
            }
        } else {
            $mensaje = "❌ Seleccione libro y usuario.";
        }
    }

    /** DEVOLVER LIBRO */
    if ($_POST['accion'] ?? null === 'devolver') {
        $id_prestamo = $_POST['id_prestamo_devolver'] ?? null;
        if ($id_prestamo) {
            if ($servicio->devolverLibro($id_prestamo)) {
                $mensaje = "✅ Libro devuelto correctamente.";
            } else {
                $mensaje = "❌ Error al devolver el libro.";
            }
        } else {
            $mensaje = "❌ Seleccione un préstamo.";
        }
    }

    /*** CARGAR DATOS PARA LOS FORMULARIOS */
    $autores_lista = $servicio->obtenerTodosLosAutores();
    $categorias_lista = $servicio->obtenerTodasLasCategorias();
    $usuarios_lista = [];

    /**$usuarios_lista[] = new class { public $id = 1; public $nombre = "Juan Pérez"; };
    $usuarios_lista[] = new class { public $id = 2; public $nombre = "María López"; };
*/
    $conexion_usuarios = include __DIR__ . '/../config/conection.php';
    $result = $conexion_usuarios->query("SELECT id, nombre, apellido FROM Usuarios");
    
    while($fila = $result->fetch_assoc()){
        $usuarios_lista[] = (object) $fila;
    }
    $conexion_usuarios->close();

    $prestamos_activos = $servicio->obtenerPrestamosActivos();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sistema de Biblioteca</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .form-section { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }
        label { display: inline-block; width: 120px; }
        input, select { padding: 5px; margin: 5px 0; }
        button { padding: 8px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        .error { color: red; }
        .success { color: green; }
        ul { list-style-type: none; padding: 0; }
        li { border-bottom: 1px solid #eee; padding: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Sistema de Gestión de Biblioteca</h1>

        <?php if ($mensaje): ?>
            <p class="<?= strpos($mensaje, '❌') !== false ? 'error' : 'success' ?>">
                <?= htmlspecialchars($mensaje) ?>
            </p>
        <?php endif; ?>

        <!-- Formulario: Agregar Libro -->
        <div class="form-section">
            <h2>➕ Agregar Libro</h2>
            <form method="POST">
                <input type="hidden" name="accion" value="agregar_libro">
                <p><label>Título:</label> <input type="text" name="titulo" required></p>
                <p><label>Año:</label> <input type="number" name="anio" min="1000" max="2025" required></p>
                <p><label>Categoría:</label>
                    <select name="id_categoria">
                        <option value="">-- Seleccione --</option>
                        <?php foreach($categorias_lista as $cat): ?>
                            <option value="<?= $cat->getId() ?>"><?= htmlspecialchars($cat->getNombreCategoria()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p><label>Editorial:</label> <input type="text" name="editorial"></p>
                <p><label>ISBN:</label> <input type="text" name="ISBN" required></p>
                <p><label>Autores:</label><br>
                    <?php foreach($autores_lista as $autor): ?>
                        <label>
                            <input type="checkbox" name="autores[]" value="<?= $autor->getId() ?>">
                            <?= htmlspecialchars($autor->getNombreAutor()) ?>
                        </label><br>
                    <?php endforeach; ?>
                </p>
                <button type="submit">Agregar Libro</button>
            </form>
        </div>

        <!-- Formulario: Buscar Libros -->
        <div class="form-section">
            <h2>🔍 Buscar Libros</h2>
            <form method="post">
                <input type="hidden" name="accion" value="buscar">
                <p><label>Título:</label> <input type="text" name="buscar_titulo"></p>
                <p><label>Autor:</label>
                    <select name="buscar_autor">
                        <option value="">Cualquiera</option>
                        <?php foreach($autores_lista as $autor): ?>
                            <option value="<?= $autor->getId() ?>"><?= htmlspecialchars($autor->getNombreAutor()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p><label>Categoria:</label>
                    <select name="buscar_categoria">
                        <option value="">Cualquiera</option>
                        <?php foreach($categorias_lista as $cat): ?>
                            <option value="<?= $cat->getId() ?>"><?= htmlspecialchars($cat->getNombreCategoria()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <button type="submit">Buscar</button>
                <a href="index.php">Mostrar todos</a>
            </form>
        </div>

        <!-- Formulario: Prestar Libro -->
        <div class="form-section">
            <h2>📥 Prestar Libro</h2>
            <form method="post">
                <input type="hidden" name="accion" value="prestar">
                <p><label>Libro:</label>
                    <select name="id_libro_prestamo">
                        <option value="">-- Disponibles --</option>
                        <?php
                            $todos_libros = $servicio->obtenerTodosLosLibros();
                            foreach($todos_libros as $libro){
                                if($libro->getEstado() === 'disponible'){
                                    echo '<option value="' . $libro->getId() . '">' . htmlspecialchars($libro->getTitulo()) . '</option>';
                                }
                            }
                        ?>
                    </select>
                </p>
                <p><label>Usuario:</label>
                    <select name="id_usuario_prestamo">
                        <?php foreach($usuarios_lista as $usr): ?>
                            <option value="<?= $usr->id ?>"><?= htmlspecialchars($usr->nombre . ' ' . $usr->apellido) ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <button type="submit">Prestar</button>
            </form>
        </div>

        <!-- Formulario: Devolver Libro -->
        <div class="form-section">
            <h2>📤 Devolver Libro</h2>
            <form method="post">
                <input type="hidden" name="accion" value="devolver">
                <p><label>Prestamo activo:</label>
                    <select name="id_prestamo_devolver">
                        <option value="">-- Selecione --</option>
                        <?php foreach($prestamos_activos as $prestamo): ?>
                            <option value="<?= $prestamo->getId() ?>">
                                <?= htmlspecialchars($prestamo->getLibro()->getTitulo()) ?> -
                                <?= htmlspecialchars($prestamo->getUsuario()->getNombre()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <button type="submit">Devolver</button>
            </form>
        </div>

        <!-- Listado de Libros -->
        <div class="form-section">
            <h2>📖 Libros en la Biblioteca (<?= count($libros_mostrar) ?>)</h2>
            <?php if (empty($libros_mostrar)): ?>
                <p>No se encontraron libros.</p>
            <?php else: ?>
                <ul>
                    <?php foreach($libros_mostrar as $libro): ?>
                        <li>
                            <strong><?= htmlspecialchars($libro->getTitulo()) ?></strong>  
                            (<?= $libro->getAnio() ?>)  
                            - <em>Estado: <?= $libro->getEstado() ?></em><br>
                            Categoría: <?= htmlspecialchars($libro->getCategoria()->getNombreCategoria()) ?><br>
                            Autores: 
                            <?php
                            $nombres = [];
                            foreach ($libro->getAutores() as $autor) {
                                $nombres[] = htmlspecialchars($autor->getNombreAutor());
                            }
                            echo implode(', ', $nombres);
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>