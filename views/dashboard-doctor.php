<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'd') {
    header("location: ../index.html");
    exit();
}

$username = $_SESSION['username'];
$useremail = $_SESSION['user'];

$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/animations.css">  
    <link rel="stylesheet" href="../assets/css/main.css">  
    <link rel="stylesheet" href="../assets/css/admin.css">
    <title>Dashboard - Doctor</title>
    <style>
        .dashboard-items {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .link-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .link-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .link-card h3 {
            color: #28a745;
            margin-bottom: 10px;
        }
        .link-card p {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- MENÚ LATERAL -->
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../assets/img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title">Dr. <?php echo substr($username, 0, 13) ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php">
                                        <input type="button" value="Cerrar Sesión" class="logout-btn btn-primary-soft btn">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home menu-active menu-icon-home-active">
                        <a href="dashboard-doctor.php" class="non-style-link-menu non-style-link-menu-active">
                            <div><p class="menu-text">Inicio</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="#" onclick="alert('Próximamente')" class="non-style-link-menu">
                            <div><p class="menu-text">Mis Sesiones</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="#" onclick="alert('Próximamente')" class="non-style-link-menu">
                            <div><p class="menu-text">Mis Pacientes</p></div>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CONTENIDO PRINCIPAL -->
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;">
                <!-- Banner de Bienvenida -->
                <tr>
                    <td colspan="4">
                        <div class="welcome-banner">
                            <h1 style="margin: 0;">¡Bienvenido Dr. <?php echo $username; ?>! 👨‍⚕️</h1>
                            <p style="margin: 10px 0 0 0; opacity: 0.9;">
                                Fecha de hoy: <?php echo date('d/m/Y', strtotime($today)); ?>
                            </p>
                        </div>
                    </td>
                </tr>
                
                <!-- Enlaces Rápidos -->
                <tr>
                    <td colspan="4">
                        <div class="quick-links">
                            <div class="link-card" onclick="alert('Módulo en desarrollo')">
                                <h3>📅 Mis Sesiones</h3>
                                <p>Ver y gestionar mis horarios de atención</p>
                            </div>
                            
                            <div class="link-card" onclick="alert('Módulo en desarrollo')">
                                <h3>👥 Mis Pacientes</h3>
                                <p>Consultar pacientes asignados y su historial</p>
                            </div>
                            
                            <div class="link-card" onclick="alert('Módulo en desarrollo')">
                                <h3>📋 Citas del Día</h3>
                                <p>Ver citas programadas para hoy</p>
                            </div>
                            
                            <div class="link-card" onclick="alert('Módulo en desarrollo')">
                                <h3>📊 Estadísticas</h3>
                                <p>Ver reportes y estadísticas de atención</p>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <!-- Información Adicional -->
                <tr>
                    <td colspan="4" style="padding: 20px;">
                        <center>
                            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; max-width: 800px;">
                                <h3 style="color: #333; margin-bottom: 15px;">ℹ️ Panel de Doctor</h3>
                                <p style="color: #666; line-height: 1.8;">
                                    <strong>Nota:</strong> Este es un panel temporal mientras se desarrollan los módulos específicos para doctores.<br><br>
                                    <strong>Funcionalidades próximamente:</strong><br>
                                    - Gestión de sesiones/horarios<br>
                                    - Expedientes de pacientes<br>
                                    - Registro de consultas<br>
                                    - Prescripciones médicas
                                </p>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>