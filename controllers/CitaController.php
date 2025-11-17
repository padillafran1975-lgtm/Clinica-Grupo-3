<?php


require_once '../config/conexion.php';
require_once '../models/Cita.php';

class CitaController {
    private $db;
    private $citaModel;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->citaModel = new Cita($this->db);
    }
    

    public function mostrarDetallesBooking($id_sesion) {

        $this->citaModel->marcarCitasPerdidas();
        $sesion = $this->citaModel->obtenerDetallesSesion($id_sesion);
        
        if (!$sesion) {
            return ['error' => 'Sesión no encontrada'];
        }
        
        return [
            'sesion' => $sesion,
            'today' => date('Y-m-d'),
            'id_cita' => $this->citaModel->calcularNumeroSiguienteCita($id_sesion),
            'disponible' => $this->citaModel->verificarDisponibilidad($id_sesion)
        ];
    }
    

    public function procesarBooking($id_paciente, $id_cita, $id_sesion, $creado_en) {

    // Obtener el médico correcto de la sesión
    $sesion = $this->citaModel->obtenerDetallesSesion($id_sesion);
    $id_medico = $sesion['id_medico'];

    // Crear la cita  
    $resultado = $this->citaModel->crear($id_paciente, $id_medico, $id_sesion, $creado_en);

    if ($resultado['success']) {
        header("Location: ../views/booking-complete.php?id_cita=" . $resultado['id_cita']);
        exit();
    }

    return $resultado;
}

    
    /**
     * Obtener citas del paciente
     */
    public function obtenerCitasPaciente($id_paciente) {
        $this->citaModel->marcarCitasPerdidas();
        return $this->citaModel->obtenerCitasPorPaciente($id_paciente);
    }
    
    /**
     * Cancelar cita
     */
    public function cancelarCita($id_cita, $id_paciente, $motivo) {
        return $this->citaModel->cancelar($id_cita, $id_paciente, $motivo);
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    session_start();

    // Validar usuario logueado
    if (!isset($_SESSION['user']) || $_SESSION['usertype'] != 'p') {
        header("Location: ../login.php");
        exit();
    }

    if (!isset($_SESSION['id_paciente'])) {
        die("ERROR: No se encontró id_paciente en la sesión.");
    }

    $id_paciente = $_SESSION['id_paciente'];


    $controller = new CitaController();

    // Registramos cita
    if (isset($_POST['booknow'])) {
        
        $id_cita   = $_POST['id_cita'];
        $id_sesion = $_POST['id_sesion'];
        $creado_en = $_POST['date'];

        $resultado = $controller->procesarBooking(
            $id_paciente,
            $id_cita,
            $id_sesion,
            $creado_en
        );

        if (!$resultado['success']) {
            $_SESSION['error'] = $resultado['message'];
            header("Location: ../views/booking.php?id=" . $id_sesion);
            exit();
        }
    }

    // CANCELAR CITA
    if (isset($_POST['cancelar'])) {
        $id_cita = $_POST['id_cita'];
        $motivo  = $_POST['motivo'];

        $resultado = $controller->cancelarCita($id_cita, $id_paciente, $motivo);

        $_SESSION['message'] = $resultado['message'];
        header("Location: ../views/appointment.php");
        exit();
    }
}
?>
