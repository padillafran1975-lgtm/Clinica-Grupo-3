<?php
// Incluir la conexión
require_once __DIR__ . '/clases/conexion.php'; 

// 1. Verificar que se haya enviado un ID válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    $id_medico = mysqli_real_escape_string($conexion, $_GET['id']);
    
    // 2. Crear la consulta SQL de ELIMINACIÓN
    $sql = "DELETE FROM Medicos WHERE id_medico = '$id_medico'";
    
    // 3. Ejecutar la consulta
    if (mysqli_query($conexion, $sql)) {
        // Redirigir con mensaje de éxito
        header("Location: listar_medicos.php?mensaje=eliminado");
        exit();
    } else {
        // Redirigir con mensaje de error
        header("Location: listar_medicos.php?mensaje=error");
        exit();
    }

} else {
    // ID inválido
    header("Location: listar_medicos.php?mensaje=error_id");
    exit();
}

// Cerrar conexión
mysqli_close($conexion);
?>
