<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
    header("location: ../index.html");
    exit;
}

// Verificar que venga el ID
if (!isset($_GET['id'])) {
    $_SESSION['message'] = 'ID de cita no especificado';
    $_SESSION['message_type'] = 'error';
    header("Location: appointment.php");
    exit;
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Cita.php';

// Crear conexión y modelo
$database = new Database();
$db = $database->getConnection();
$citaModel = new Cita($db);

// Obtener ID de la cita a cancelar
$id_cita = $_GET['id'];


error_log("=== DELETE APPOINTMENT ===");
error_log("ID recibido: " . $id_cita);

// Cancelar la cita
$resultado = $citaModel->cancelar($id_cita);

error_log("Resultado: " . print_r($resultado, true));

// Guardar mensaje en sesión
if ($resultado['success']) {
    $_SESSION['message'] = $resultado['message'];
    $_SESSION['message_type'] = 'success';
} else {
    $_SESSION['message'] = $resultado['message'];
    $_SESSION['message_type'] = 'error';
}

// Redirigir de vuelta a las citas
header("Location: appointment.php");
exit();
?>
