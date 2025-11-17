<?php
// Datos de conexión
$host = "localhost"; 
$usuario = "root";      
$password = "";      
$base_datos = "init_db"; 

// Crear conexión
$conexion = mysqli_connect($host, $usuario, $password, $base_datos);

// Verificar conexión (QUITAMOS el 'echo' y solo manejamos el error)
if (!$conexion) {
    // Si la conexión falla, solo detenemos el script y mostramos el error SOLO para el desarrollador
    die("Error en la conexión: " . mysqli_connect_error());
}

// Si la conexión es exitosa, el script termina aquí y la variable $conexion está disponible
// para el archivo que lo incluya.
?>
