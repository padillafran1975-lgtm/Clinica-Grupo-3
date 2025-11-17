<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';


if ($username === '' || $password === '') {
    echo "<script>alert('Por favor, ingrese su nombre de usuario y contraseña.'); window.location.href='index.html';</script>";
    exit;
}

require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/models/Usuario.php';

// Crear conexión
$database = new Database();
$db = $database->getConnection();

// Crear modelo de usuario
$usuarioModel = new Usuario($db);

// Intentar login
$resultado = $usuarioModel->login($username, $password);

if ($resultado['success']) {
    $user = $resultado['user'];
    
    // Obtener tipo de usuario (a, d, p)
    $usertype = $usuarioModel->obtenerTipoUsuario($user['rol_id']);
    

    // Guardar en sesión
$_SESSION['user'] = $user['correo'];
$_SESSION['username'] = $user['nombre'];
$_SESSION['userid'] = $user['id'];
$_SESSION['usertype'] = $usertype;
$_SESSION['rol_id'] = $user['rol_id'];
$_SESSION['rol_nombre'] = $user['rol_nombre'];

// Obtener ID según rol
$specific_id = $usuarioModel->obtenerIdEspecifico($user['id'], $usertype);

if ($usertype == 'p') {
    $_SESSION['id_paciente'] = $specific_id;
}

if ($usertype == 'd') {
    $_SESSION['id_medico'] = $specific_id;
}

    
    // Redirigir según tipo de usuario
    switch($usertype) {
        case 'p': // Paciente
            header("Location: views/dashboard-patient.php");
            break;
        case 'd': // Medico
            header("Location: views/dashboard-doctor.php");
            break;
        case 'a': // Admin
            header("Location: views/dashboard-admin.php");
            break;
        case 'e': // Encargada
        header("Location: views/dashboard-patient.php");
        break;

        default:
            header("Location: views/dashboard.php");
    }
    exit;
    
} else {
    echo "<script>alert('".$resultado['message']."'); window.location.href='index.html';</script>";
    exit;
}

$database->closeConnection();
?>
