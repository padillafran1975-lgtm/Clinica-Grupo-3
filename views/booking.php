<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
    header("location: ../login.php");
    exit();
}

require_once '../controllers/CitaController.php';

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

// Obtener detalles de la sesión
if (isset($_GET['id'])) {
    $controller = new CitaController();
    $datos = $controller->mostrarDetallesBooking($_GET['id']);
    
    if (isset($datos['error'])) {
        $_SESSION['error'] = $datos['error'];
        header("Location: schedule.php");
        exit();
    }
    
    $sesion = $datos['sesion'];
    $id_cita = $datos['id_cita'];
    $disponible = $datos['disponible'];
    $today = $datos['today'];
} else {
    header("Location: schedule.php");
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
    <title>Reservar Cita</title>
    <style>
        .popup { animation: transitionIn-Y-bottom 0.5s; }
        .sub-table { animation: transitionIn-Y-bottom 0.5s; }
        .error-message { 
            background: #ff4444; 
            color: white; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 20px;
            text-align: center;
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
                    <td class="menu-btn menu-icon-session menu-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active">
                            <div><p class="menu-text">Sesiones Programadas</p></div>
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
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <tr>
                    <td width="13%">
                        <a href="schedule.php">
                            <button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                <font class="tn-in-text">Regresar</font>
                            </button>
                        </a>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Fecha de Hoy
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php echo $today; ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;">
                            <img src="../img/calendar.svg" width="100%">
                        </button>
                    </td>
                </tr>
                
                <?php if (isset($_SESSION['error'])): ?>
                <tr>
                    <td colspan="3">
                        <div class="error-message">
                            <?php 
                            echo $_SESSION['error']; 
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                
                <tr>
                    <td colspan="3">
                        <center>
                            <div class="abc scroll">
                                <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                                    <tbody>
                                        <?php if ($disponible): ?>
                                        <form action="/CITASSSS/controllers/CitaController.php" method="post">
                                            <input type="hidden" name="id_sesion" value="<?php echo $sesion['id_sesion']; ?>">
                                            <input type="hidden" name="id_cita" value="<?php echo $id_cita; ?>">
                                            <input type="hidden" name="date" value="<?php echo $today; ?>">
                                            
                                            <tr>
                                                <td style="width: 50%;" rowspan="2">
                                                    <div class="dashboard-items search-items">
                                                        <div style="width:100%">
                                                            <div class="h1-search" style="font-size:25px;">
                                                                Detalles de la Sesión
                                                            </div><br><br>
                                                            <div class="h3-search" style="font-size:18px;line-height:30px">
                                                                Nombre del Doctor: &nbsp;&nbsp;<b><?php echo $sesion['nombre_medico']; ?></b><br>
                                                                Email del Doctor: &nbsp;&nbsp;<b><?php echo $sesion['docemail']; ?></b>
                                                            </div><br>
                                                            <div class="h3-search" style="font-size:18px;">
                                                                Título de Sesión: <?php echo $sesion['titulo']; ?><br>
                                                                Fecha Programada: <?php echo $sesion['fecha_sesion']; ?><br>
                                                                Hora de Inicio: <?php echo $sesion['hora_inicio']; ?><br>
                                                                Costo de Consulta: <b>L. 700.00</b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items search-items">
                                                        <div style="width:100%;padding-top: 15px;padding-bottom: 15px;">
                                                            <div class="h1-search" style="font-size:20px;line-height: 35px;margin-left:8px;text-align:center;">
                                                                Tu Número de Cita
                                                            </div>
                                                            <center>
                                                                <div class="dashboard-icons" style="margin-left: 0px;width:90%;font-size:70px;font-weight:800;text-align:center;color:var(--btnnictext);background-color: var(--btnice)">
                                                                    <?php echo $id_cita; ?>
                                                                </div>
                                                            </center>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="submit" name="booknow" class="login-btn btn-primary btn btn-book" style="margin-left:10px;padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;width:95%;text-align: center;" value="Reservar Ahora">
                                                </td>
                                            </tr>
                                        </form>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="2">
                                                <div class="error-message">
                                                    No hay cupos disponibles para esta sesión
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>