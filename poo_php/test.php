<?php
    require_once __DIR__ . '/src/service/BibliotecaService.php';

    try {
        $servicio = new BibliotecaService();
        echo "<h2>✅ ¡Conexión y servicio funcionan correctamente!</h2>";

        // Prueba adicional: obtener categorías
        $categorias = $servicio->obtenerTodasLasCategorias();
        echo "<p>Categorías cargadas: " . count($categorias) . "</p>";

    } catch (Exception  $e) {
        echo '<h2>❌ Error:</h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>';
    }
?>