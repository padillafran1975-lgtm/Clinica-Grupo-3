<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
    header("location: ../login.php");
    exit();
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Sesion.php';

// Obtener datos del usuario
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

$sesionModel = new Sesion($db);

// Manejar búsqueda
$busqueda = null;
$textoBusqueda = "";
$tipoBusqueda = "Todas las";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search'])) {
    $busqueda = $_POST['search'];
    $textoBusqueda = $busqueda;
    $tipoBusqueda = "Resultados de búsqueda:";
}

// Obtener sesiones disponibles
$sesiones = $sesionModel->obtenerSesionesDisponibles($busqueda);
$totalSesiones = $sesiones->num_rows;

// Obtener listas para autocompletado
$listaDoctores = $sesionModel->obtenerListaDoctores();
$listaTitulos = $sesionModel->obtenerTitulosSesiones();

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
    

    <title>Sesiones Disponibles</title>
    <style>
        .popup { animation: transitionIn-Y-bottom 0.5s; }
        .sub-table { animation: transitionIn-Y-bottom 0.5s; }
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
                    <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active">
                            <div><p class="menu-text">Sesiones</p></div>
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
                <!-- ENCABEZADO CON BÚSQUEDA -->
                <tr>
                    <td width="13%">
                        <a href="dashboard-patient.php">
                            <button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                                <font class="tn-in-text">Regresar</font>
                            </button>
                        </a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search">
                            <input type="search" 
                                   name="search" 
                                   class="input-text header-searchbar" 
                                   placeholder="Buscar Doctor, Especialidad o Fecha (YYYY-MM-DD)" 
                                   list="opciones-busqueda"
                                   value="<?php echo $textoBusqueda; ?>">
                            
                            <datalist id="opciones-busqueda">
                                <?php
                                while ($doc = $listaDoctores->fetch_assoc()) {
                                    echo '<option value="'.$doc["docname"].'">';
                                }
                                
                                while ($titulo = $listaTitulos->fetch_assoc()) {
                                    echo '<option value="'.$titulo["title"].'">';
                                }
                                ?>
                            </datalist>
                            
                            <input type="submit" 
                                   value="Buscar" 
                                   class="login-btn btn-primary btn" 
                                   style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        </form>
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
                            <img src="../assets/img/calendar.svg" width="100%">
                        </button>
                    </td>
                </tr>
                
                <!-- TÍTULO CON CONTADOR -->
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">
                            <?php echo $tipoBusqueda; ?> Sesiones (<?php echo $totalSesiones; ?>)
                        </p>
                        <?php if ($textoBusqueda): ?>
                        <p class="heading-main12" style="margin-left: 45px;font-size:22px;color:rgb(49, 49, 49)">
                            "<?php echo $textoBusqueda; ?>"
                        </p>
                        <?php endif; ?>
                    </td>
                </tr>
                
                <!-- LISTADO DE SESIONES -->
                <tr>
                    <td colspan="4">
                        <center>
                            <div class="abc scroll">
                                <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                                    <tbody>
                                        <?php
                                        // Si no hay sesiones
                                        if ($totalSesiones == 0) {
                                            echo '
                                            <tr>
                                                <td colspan="4">
                                                    <br><br><br><br>
                                                    <center>
                                                        <img src="../assets/img/notfound.svg" width="25%">
                                                        <br>
                                                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">
                                                            No se encontraron sesiones disponibles
                                                        </p>
                                                        <a class="non-style-link" href="schedule.php">
                                                            <button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">
                                                                &nbsp; Ver Todas las Sesiones &nbsp;
                                                            </button>
                                                        </a>
                                                    </center>
                                                    <br><br><br><br>
                                                </td>
                                            </tr>';
                                        } else {
                                            // Mostrar sesiones en formato de tarjetas (3 por fila)
                                            $contador = 0;
                                            echo "<tr>";
                                            
                                            while ($row = $sesiones->fetch_assoc()) {
                                                $id_sesion = $row["id_sesion"];
                                                $titulo = $row["titulo"];
                                                $medico_nombre = $row["medico_nombre"];
                                                $fecha_sesion = $row["fecha_sesion"];
                                                $hora_inicio = $row["hora_inicio"];
                                                $cupo_maximo = $row["cupo_maximo"];
                                                $citas_reservadas = $row["citas_reservadas"];
                                                
                                                // Calcular cupos disponibles
                                                $cupos_disponibles = $cupo_maximo - $citas_reservadas;
                                                $sesion_llena = $cupos_disponibles <= 0;
                                                
                                                echo '
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items search-items">
                                                        <div style="width:100%">
                                                            <div class="h1-search">
                                                                '.substr($titulo, 0, 21).'
                                                            </div><br>
                                                            <div class="h3-search">
                                                                Dr. '.substr($medico_nombre, 0, 30).'
                                                            </div>
                                                            <div class="h4-search">
                                                                '.date('d/m/Y', strtotime($fecha_sesion)).'<br>
                                                                Inicia: <b>'.date('h:i A', strtotime($hora_inicio)).'</b>
                                                            </div>
                                                            <div class="h4-search" style="margin-top:10px; color: '.($sesion_llena ? 'red' : 'green').';">
                                                                <strong>Cupos: '.$cupos_disponibles.' de '.$cupo_maximo.'</strong>
                                                            </div>
                                                            <br>';
                                                
                                                if ($sesion_llena) {
                                                    echo '
                                                    <button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%;opacity:0.5;cursor:not-allowed;" disabled>
                                                        <font class="tn-in-text">CUPO LLENO</font>
                                                    </button>';
                                                } else {
                                                    echo '
                                                    <a href="booking.php?id='.$id_sesion.'">
                                                        <button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%">
                                                            <font class="tn-in-text">Reservar Ahora</font>
                                                        </button>
                                                    </a>';
                                                }
                                                
                                                echo '
                                                        </div>
                                                    </div>
                                                </td>';
                                                
                                                $contador++;
                                                
                                                // Cerrar fila cada 3 sesiones
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
</body>
</html>