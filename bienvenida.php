<?php
session_start();


if (!isset($_SESSION["username"])) {
    header("Location: index.html");
    exit();
}

$username = $_SESSION["username"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido - PumaCare Plus</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Arial', sans-serif;
      background-color: #f8f9fa;
    }
    .welcome-box {
      text-align: center;
      background-color: #ffffff;
      padding: 2rem 3rem;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-logout {
      margin-top: 1.5rem;
    }
  </style>
</head>
<body>

  <div class="welcome-box">
    <h1 class="text-primary">Bienvenido, <?php echo htmlspecialchars($username); ?>!</h1>
    <p class="mt-3">Has iniciado sesión correctamente en PumaCare Plus.</p>
    <a href="logout.php" class="btn btn-danger btn-logout">Cerrar sesión</a>
  </div>

</body>
</html>
