<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
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
    <title>Dashboard - Paciente</title>
    <style>
        .dashboard-items {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #667eea;
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
                        <a href="dashboard-patient.php" class="non-style-link-menu non-style-link-menu-active">
                            <div><p class="menu-text">Inicio</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu">
                            <div><p class="menu-text">Agendar Cita</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu">
                            <div><p class="menu-text">Mis Citas</p></div>
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
                            <h1 style="margin: 0;">¡Bienvenido, <?php echo $username; ?>! 👋</h1>
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
                            <a href="schedule.php" class="link-card">
                                <h3>📅 Agendar Cita</h3>
                                <p>Busca sesiones disponibles y agenda tu cita con tu médico preferido</p>
                            </a>
                            
                            <a href="appointment.php" class="link-card">
                                <h3>📋 Mis Citas</h3>
                                <p>Consulta tu historial de citas y próximas consultas programadas</p>
                            </a>
                            
                            <a href="#" class="link-card" onclick="alert('Próximamente')">
                                <h3>👨‍⚕️ Médicos</h3>
                                <p>Conoce a nuestro equipo médico y sus especialidades</p>
                            </a>
                            
                            <a href="#" class="link-card" onclick="alert('Próximamente')">
                                <h3>⚙️ Configuración</h3>
                                <p>Actualiza tu información personal y preferencias</p>
                            </a>
                        </div>
                    </td>
                </tr>
                
                <!-- Información Adicional -->
                <tr>
                    <td colspan="4" style="padding: 20px;">
                        <center>
                            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; max-width: 800px;">
                                <h3 style="color: #333; margin-bottom: 15px;">📍 Información de la Clínica</h3>
                                <p style="color: #666; line-height: 1.8;">
                                    <strong>Horario de atención:</strong> Lunes a Viernes, 8:00 AM - 6:00 PM<br>
                                    <strong>Costo de consulta:</strong> L. 700.00<br>
                                    <strong>Teléfono:</strong> +504 xxxx-xxxx<br>
                                    <strong>Dirección:</strong> Tegucigalpa, Honduras
                                </p>
                                <p style="color: #999; font-size: 12px; margin-top: 15px;">
                                    💡 Recuerda llegar 15 minutos antes de tu cita
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