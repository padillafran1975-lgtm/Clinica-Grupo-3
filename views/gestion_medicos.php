<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'a') {
    header("location: ../index.html");
    exit();
}

$username = $_SESSION['username'];
$useremail = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/main.css">
    <title>Gestión de Médicos</title>
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .btn {
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        .btn-add {
            background: #28a745;
            color: white;
        }
        .btn-add:hover {
            background: #218838;
        }
        .btn-modify {
            background: #ffc107;
            color: black;
        }
        .btn-modify:hover {
            background: #e0a800;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .btn-back {
            background: #6c757d;
            color: white;
            margin-top: 20px;
        }
        .btn-back:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestión de Médicos</h1>
            <p>Selecciona una opción:</p>
        </div>

        <div class="btn-container">
            <a href="agregar_medico.php" class="btn btn-add">
                ➕ Agregar Médico
            </a>
            
            <a href="eliminar_medico.php" class="btn btn-modify">
                ✏️ Modificar Médico
            </a>
            
            <a href="eliminar_medico.php" class="btn btn-delete">
                🗑️ Eliminar Médico
            </a>
            
            <a href="dashboard-admin.php" class="btn btn-back">
                ← Volver al Dashboard
            </a>
        </div>
    </div>
</body>
</html>