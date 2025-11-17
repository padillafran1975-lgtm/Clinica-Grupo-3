<?php
// Incluir conexión
require_once __DIR__ . '/clases/conexion.php';

// Verificar que se recibió un ID por GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_paciente = mysqli_real_escape_string($conexion, $_GET['id']);

    // Primero verificamos que el paciente exista
    $sql_check = "SELECT * FROM pacientes WHERE id_paciente = '$id_paciente'";
    $resultado = mysqli_query($conexion, $sql_check);

    if ($resultado && mysqli_num_rows($resultado) == 1) {
        // Paciente encontrado, procedemos a eliminar
        $sql_delete = "DELETE FROM pacientes WHERE id_paciente = '$id_paciente'";
        if (mysqli_query($conexion, $sql_delete)) {
            // Redirigir al listado con mensaje de éxito
            header("Location: listar_pacientes.php?mensaje=eliminado");
            exit();
        } else {
            // Error al eliminar
            header("Location: listar_pacientes.php?mensaje=error");
            exit();
        }
    } else {
        // No existe el paciente
        header("Location: listar_pacientes.php?mensaje=error_id");
        exit();
    }
} else {
    // No se recibió ID
    header("Location: listar_pacientes.php?mensaje=error_id");
    exit();
}
?>
