<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'a') {
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
    <title>Dashboard - Administrador</title>
    <style>
        .dashboard-items {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
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
            color: #dc3545;
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
                                    <p class="profile-title"><?php echo substr($username, 0, 13) ?>..</p>
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
                        <a href="dashboard-admin.php" class="non-style-link-menu non-style-link-menu-active">
                            <div><p class="menu-text">Inicio</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="#" onclick="alert('Próximamente')" class="non-style-link-menu">
                            <div><p class="menu-text">Médicos</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="#" onclick="alert('Próximamente')" class="non-style-link-menu">
                            <div><p class="menu-text">Sesiones</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="#" onclick="alert('Próximamente')" class="non-style-link-menu">
                            <div><p class="menu-text">Todas las Citas</p></div>
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
                            <h1 style="margin: 0;">¡Bienvenido Admin <?php echo $username; ?>! 🔧</h1>
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
                            <div class="link-card" onclick="window.location.href='gestion_medicos.php'">
    <h3>👨‍⚕️ Gestión de Médicos</h3>
    <p>Agregar, editar y gestionar médicos</p>
</div>
                            
                            <div class="link-card" onclick="alert('Módulo en desarrollo')">
                                <h3>👥 Gestión de Pacientes</h3>
                                <p>Ver y administrar pacientes registrados</p>
                            </div>
                            
                            <div class="link-card" onclick="alert('Módulo en desarrollo')">
                                <h3>📅 Gestión de Sesiones</h3>
                                <p>Crear y administrar horarios médicos</p>
                            </div>
                            
                            <div class="link-card" onclick="alert('Módulo en desarrollo')">
                                <h3>📋 Todas las Citas</h3>
                                <p>Ver y gestionar todas las citas del sistema</p>
                            </div>
                            
                            <div class="link-card" onclick="alert('Módulo en desarrollo')">
                                <h3>📊 Reportes</h3>
                                <p>Estadísticas generales del sistema</p>
                            </div>
                            
                            <div class="link-card" onclick="alert('Módulo en desarrollo')">
                                <h3>⚙️ Configuración</h3>
                                <p>Ajustes del sistema y tarifas</p>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <!-- Información Adicional -->
                <tr>
                    <td colspan="4" style="padding: 20px;">
                        <center>
                            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; max-width: 800px;">
                                <h3 style="color: #333; margin-bottom: 15px;">ℹ️ Panel de Administrador</h3>
                                <p style="color: #666; line-height: 1.8;">
                                    <strong>Nota:</strong> Este es un panel temporal mientras se desarrollan los módulos de administración.<br><br>
                                    <strong>Funcionalidades próximamente:</strong><br>
                                    - CRUD de médicos<br>
                                    - CRUD de pacientes<br>
                                    - Gestión completa de sesiones<br>
                                    - Reportes y estadísticas<br>
                                    - Configuración del sistema
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