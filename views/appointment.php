<?php
session_start();

// Verificar sesión
if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'e') {
    header("location: ../login.php");
    exit();
}

require_once '../config/conexion.php';
require_once '../models/Cita.php';

$database = new Database();
$db = $database->getConnection();

$useremail = $_SESSION["user"];

$queryUsuario = "SELECT id_usuario, nombre 
                 FROM usuarios 
                 WHERE correo = ? AND activo = 1";

$stmt = $db->prepare($queryUsuario);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$id_usuario = $usuario['id_usuario'];
$username  = $usuario['nombre'];

// Obtener id_paciente asociado al usuario
$queryPaciente = "SELECT id_paciente 
                  FROM pacientes 
                  WHERE id_usuario = ?";

$stmt = $db->prepare($queryPaciente);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();

$userid = $paciente['id_paciente'];

// Crear modelo de citas
$citaModel = new Cita($db);

// Marcar citas perdidas automáticamente
$citaModel->marcarCitasPerdidas();

// Filtro de fecha 
$fechaFiltro = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['sheduledate'])) {
    $fechaFiltro = $_POST['sheduledate'];
}

// Obtener las citas del paciente
$citas = $citaModel->obtenerCitasPorPaciente($userid, $fechaFiltro);
$totalCitas = $citas->num_rows;

// Fecha de hoy
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
    <title>Mis Citas</title>
    <style>
        .popup { animation: transitionIn-Y-bottom 0.5s; }
        .sub-table { animation: transitionIn-Y-bottom 0.5s; }
    </style>
</head>
<body>
    <div class="container">
        <!-- MENU LATERAL -->
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
                            <div><p class="menu-text">Sesiones</p></div>
                        </a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active">
                            <div><p class="menu-text">Mis Citas</p></div>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CONTENIDO PRINCIPAL -->
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <!-- ENCABEZADO -->
                <tr>
                    <td width="13%">
                        <a href="dashboard-patient.php">
                            <button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                <font class="tn-in-text">Regresar</font>
                            </button>
                        </a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Historial de Mis Citas</p>
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
                
                <!-- CONTADOR DE CITAS -->
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgba(109, 130, 23, 1)">
                            Mis Citas (<?php echo $totalCitas; ?>)
                        </p>
                    </td>
                </tr>
                
                <!-- FILTRO POR FECHA -->
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;">
                        <center>
                            <table class="filter-container" border="0">
                                <tr>
                                    <td width="10%"></td>
                                    <td width="5%" style="text-align: center;">Fecha:</td>
                                    <td width="30%">
                                        <form action="" method="post">
                                            <input type="date" name="sheduledate" class="input-text filter-container-items" style="margin: 0;width: 95%;" value="<?php echo $fechaFiltro; ?>">
                                    </td>
                                    <td width="12%">
                                        <input type="submit" name="filter" value="Filtrar" class="btn-primary-soft btn button-icon btn-filter" style="padding: 15px; margin:0;width:100%">
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </td>
                </tr>
                
                <!-- LISTADO DE CITAS -->
                <tr>
                    <td colspan="4">
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <tbody>
                                        <?php
                                        // Si no hay citas
                                        if ($totalCitas == 0) {
                                            echo '
                                            <tr>
                                                <td colspan="7">
                                                    <br><br><br><br>
                                                    <center>
                                                        <img src="../img/notfound.svg" width="25%">
                                                        <br>
                                                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">
                                                            No se encontraron citas
                                                        </p>
                                                        <a class="non-style-link" href="schedule.php">
                                                            <button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">
                                                                &nbsp; Agendar Nueva Cita &nbsp;
                                                            </button>
                                                        </a>
                                                    </center>
                                                    <br><br><br><br>
                                                </td>
                                            </tr>';
                                        } else {
                                            // Mostrar citas en formato de tarjetas (3 por fila)
                                            $contador = 0;
                                            echo "<tr>";
                                            
                                            while ($row = $citas->fetch_assoc()) {
                                                $id_cita = $row["id_cita"];
                                                $id_sesion = $row["id_sesion"];
                                                $titulo = $row["titulo"];
                                                $nombre_medico = $row["nombre_medico"];
                                                $fecha_sesion = $row["fecha_sesion"];
                                                $hora_inicio = $row["hora_inicio"];
                                                $id_cita = $row["id_cita"];
                                                $creado_en = $row["creado_en"];
                                                
                                                echo '
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items search-items">
                                                        <div style="width:100%;">
                                                            <div class="h3-search">
                                                                Fecha de Reserva: '.date('d/m/Y', strtotime($creado_en)).'<br>
                                                                Referencia: OC-000-'.$id_cita.'
                                                            </div>
                                                            <div class="h1-search">
                                                                '.substr($titulo, 0, 21).'
                                                            </div>
                                                            <div class="h3-search">
                                                                Número de Cita: <div class="h1-search">#'.$id_cita.'</div>
                                                            </div>
                                                            <div class="h3-search">
                                                                Dr. '.substr($nombre_medico, 0, 30).'
                                                            </div>
                                                            <div class="h4-search">
                                                                Fecha: '.date('d/m/Y', strtotime($fecha_sesion)).'<br>
                                                                Hora: <b>'.date('h:i A', strtotime($hora_inicio)).'</b>
                                                            </div>
                                                            <br>
                                                            <a href="?action=drop&id='.$id_cita.'&title='.$titulo.'&doc='.$nombre_medico.'">
                                                                <button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%">
                                                                    <font class="tn-in-text">Cancelar Cita</font>
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>';
                                                
                                                $contador++;
                                                
                                                // Cerrar fila cada 3 citas
                                                if ($contador % 3 == 0) {
                                                    echo "</tr><tr>";
                                                }
                                            }
                                            
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- POPUPS -->
    <?php
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        
        // Popup de confirmación de cancelación
        if ($action == 'drop' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
            $nombre_medico = isset($_GET['doc']) ? $_GET['doc'] : '';
            
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                    <center>
                        <h2>¿Estás seguro?</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            ¿Deseas cancelar esta cita?<br><br>
                            Sesión: <b>'.substr($titulo, 0, 40).'</b><br>
                            Doctor: <b>Dr. '.substr($nombre_medico, 0, 40).'</b><br><br>
                        </div>
                        <div style="display: flex;justify-content: center;">
                            <a href="delete-appointment.php?id='.$id.'" class="non-style-link">
                                <button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                    <font class="tn-in-text">&nbsp;Sí, Cancelar&nbsp;</font>
                                </button>
                            </a>
                            <a href="appointment.php" class="non-style-link">
                                <button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                    <font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font>
                                </button>
                            </a>
                        </div>
                    </center>
                </div>
            </div>';
        }
        
        // Popup de éxito después de agendar....
        if ($action == 'booking-added' && isset($_GET['id'])) {
            $id_cita = $_GET['id'];
            
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                    <center>
                        <br><br>
                        <h2>Cita Agendada Exitosamente</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            Tu número de cita es: <b>#'.$id_cita.'</b><br><br>
                        </div>
                        <div style="display: flex;justify-content: center;">
                            <a href="appointment.php" class="non-style-link">
                                <button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                    <font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font>
                                </button>
                            </a>
                        </div>
                    </center>
                </div>
            </div>';
        }
    }
    ?>
</body>
</html>
