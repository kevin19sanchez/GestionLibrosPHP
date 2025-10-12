<?php
    static $conexion_instance = null;
    
    if ($conexion_instance === null) {
        $host = "localhost";
        $usuario = "root";
        $contraseña = "";
        $base_de_datos = "biblioteca";

        $conexion_instance = new mysqli($host, $usuario, $contraseña, $base_de_datos);

        //verificar conexion
        if ($conexion_instance->connect_error) {
            die("Conexion Fallida: " . $conexion_instance->connect_error);
        }
    }
    return $conexion_instance;
?>