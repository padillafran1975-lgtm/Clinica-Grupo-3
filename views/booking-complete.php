<?php
session_start();

// Verificar sesión
if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
    header("location: ../login.php");
    exit();
}

// Obtener datos del usuario
require_once '../config/conexion.php';
$database = new Database();
$db = $database->getConnection();

$useremail = $_SESSION["user"];
$query = "SELECT id_paciente, nombre FROM pacientes WHERE correo = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$userid = $user['id_paciente'];
$username = $user['nombre'];

// Obtener numero de cita 
$id_cita = isset($_GET['id_cita']) ? $_GET['id_cita'] : null;

if (!$id_cita) {
    header("Location: schedule.php");
    exit();
}

// Obtener detalles de la cita recien creada
$query = "SELECT 
            c.*, 
            s.titulo, 
            s.fecha_sesion, 
            s.hora_inicio,
            u.nombre AS nombre_medico,
            u.correo AS correo_medico
          FROM citas c
          INNER JOIN sesiones s ON c.id_sesion = s.id_sesion
          INNER JOIN medicos m ON s.id_medico = m.id_medico
          INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
          WHERE c.id_cita = ?
          AND c.id_paciente = ?
          LIMIT 1";



$stmt = $db->prepare($query);
$stmt->bind_param("ii", $id_cita, $id_paciente);
$stmt->execute();
$result = $stmt->get_result();
$cita = $result->fetch_assoc();

if (!$cita) {
    header("Location: appointment.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/animations.css">  
<link rel="stylesheet" href="../assets/css/main.css">  
<link rel="stylesheet" href="../assets/css/admin.css">
    <title>Cita Confirmada</title>
    <style>
        .popup { animation: transitionIn-Y-bottom 0.5s; }
        .success-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            margin: 50px auto;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .cita-details {
            background: rgba(255,255,255,0.1);
            padding: 25px;
            border-radius: 10px;
            margin: 20px 0;
            backdrop-filter: blur(10px);
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            opacity: 0.9;
        }
        .detail-value {
            font-weight: 700;
        }
        .id_cita-display {
            font-size: 60px;
            font-weight: 800;
            color: #ffd700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin: 20px 0;
        }
        .btn-container {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn-custom {
            padding: 12px 30px;
            border: 2px solid white;
            background: rgba(255,255,255,0.2);
            color: white;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-custom:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
        }
        .print-btn {
            background: rgba(255,215,0,0.3);
            border-color: #ffd700;
        }
        .print-btn:hover {
            background: #ffd700;
            color: #333;
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
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
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
                    <td class="menu-btn menu-icon-home">
                        <a href="dashboard-patient.php" class="non-style-link-menu">
                            <div><p class="menu-text">Inicio</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu">
                            <div><p class="menu-text">Doctores</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu">
                            <div><p class="menu-text">Sesiones Programadas</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment menu-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active">
                            <div><p class="menu-text">Mis Citas</p></div>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CONTENIDO PRINCIPAL -->
        <div class="dash-body">
            <div class="success-container">
                <div class="success-icon">✓</div>
                <h1 style="font-size: 32px; margin-bottom: 10px;">¡Cita Reservada Exitosamente!</h1>
                <p style="font-size: 18px; opacity: 0.9;">Tu cita ha sido agendada correctamente</p>
                
                <div class="id_cita-display">
                    #<?php echo $id_cita; ?>
                </div>
                <p style="font-size: 14px; opacity: 0.8;">Número de Cita</p>
                
                <div class="cita-details">
                    <div class="detail-row">
                        <span class="detail-label">Doctor:</span>
                        <span class="detail-value"><?php echo $cita['nombre_medico']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Especialidad:</span>
                        <span class="detail-value"><?php echo $cita['titulo']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Fecha:</span>
                        <span class="detail-value"><?php echo date('d/m/Y', strtotime($cita['fecha_sesion'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Hora:</span>
                        <span class="detail-value"><?php echo date('h:i A', strtotime($cita['hora_inicio'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Fecha de Reserva:</span>
                        <span class="detail-value"><?php echo date('d/m/Y', strtotime($cita['creado_en'])); ?></span>
                    </div>
                </div>
                
                <div style="background: rgba(255,215,0,0.2); padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <p style="margin: 0; font-size: 14px;">
                        <strong>Importante:</strong> Por favor llega 15 minutos antes de tu cita.
                        <br>Recuerda traer tu identificación y tu número de cita.
                    </p>
                </div>
                
                <div class="btn-container">
                    <a href="appointment.php" class="btn-custom">
                        Ver Mis Citas
                    </a>
                    <a href="schedule.php" class="btn-custom">
                        Agendar Otra
                    </a>
                    <button onclick="window.print()" class="btn-custom print-btn">
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-scroll al contenido
        window.addEventListener('load', function() {
            document.querySelector('.success-container').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        });
    </script>
</body>
</html>