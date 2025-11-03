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

$conn = mysqli_connect("localhost", "root", "", "init_db");
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}


$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {

    if ($password === $row['contrasena_hash']) {
        $_SESSION['username'] = $username;
        header("Location: bienvenida.php");
        exit;
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.html';</script>";
        exit;
    }
} else {
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.html';</script>";
    exit;
}

mysqli_close($conn);
?>
