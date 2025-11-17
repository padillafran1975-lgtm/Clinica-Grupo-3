<?php
class Cita {
    private $conn;
    private $table = 'citas';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
 
    public function obtenerDetallesSesion($id_sesion) {
        $query = "SELECT s.*, 
                         m.especialidad, 
                         u.nombre as nombre_medico, 
                         u.correo as correo_medico 
                  FROM sesiones s 
                  INNER JOIN medicos m ON s.id_medico = m.id_medico 
                  INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                  WHERE s.id_sesion = ? 
                  ORDER BY s.fecha_sesion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    

    public function calcularNumeroSiguienteCita($id_sesion) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE id_sesion = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'] + 1;
    }
    

    public function verificarDisponibilidad($id_sesion) {
        $query = "SELECT s.cupo_maximo, 
                  (SELECT COUNT(*) FROM {$this->table} WHERE id_sesion = ? AND estado != 'cancelada') as citas_reservadas
                  FROM sesiones s WHERE s.id_sesion = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_sesion, $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if (!$row) return false;
        
        return $row['citas_reservadas'] < $row['cupo_maximo'];
    }
    

    private function pacienteYaTieneCitaEnSesion($id_paciente, $id_sesion) {
        $query = "SELECT COUNT(*) as total 
                  FROM {$this->table} 
                  WHERE id_paciente = ? 
                  AND id_sesion = ? 
                  AND estado NOT IN ('cancelada', 'perdida')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_paciente, $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'] > 0;
    }
    
    /**
     * Crear una nueva cita
     */
    public function crear($id_paciente, $id_medico, $id_sesion, $fecha, $duracion_min = 60, $costo = 700) {
        if ($this->pacienteYaTieneCitaEnSesion($id_paciente, $id_sesion)) {
            return ['success' => false, 'message' => 'Ya tienes una cita agendada en esta sesión'];
        }
        
        if (!$this->verificarDisponibilidad($id_sesion)) {
            return ['success' => false, 'message' => 'No hay cupos disponibles'];
        }
        
        // COMENTAR TEMPORALMENTEEE
        /*
        if (!$this->pacientePuedeAgendar($id_paciente)) {
            return ['success' => false, 'message' => 'Tiene citas pendientes sin facturar'];
        }
        */
        
        $query = "INSERT INTO {$this->table} (id_paciente, id_medico, id_sesion, fecha, duracion_min, estado, costo, probono) 
                  VALUES (?, ?, ?, ?, ?, 'pendiente', ?, 0)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiiidi", $id_paciente, $id_medico, $id_sesion, $fecha, $duracion_min, $costo);
        
        if ($stmt->execute()) {
            $id_cita = $this->conn->insert_id;
            return ['success' => true, 'message' => 'Cita agendada exitosamente', 'id_cita' => $id_cita];
        }
        
        return ['success' => false, 'message' => 'Error al agendar la cita'];
    }
    

    private function pacientePuedeAgendar($id_paciente) {
        $query = "SELECT COUNT(*) as pendientes 
                  FROM {$this->table} c
                  LEFT JOIN facturas f ON c.id_cita = f.id_cita
                  WHERE c.id_paciente = ? 
                  AND c.estado NOT IN ('cancelada', 'perdida')
                  AND c.probono = 0
                  AND f.id_factura IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_paciente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['pendientes'] == 0;
    }
    

    public function obtenerCitasPorPaciente($id_paciente, $fecha = null) {
        $query = "SELECT c.*, 
                         s.titulo, s.fecha_sesion, s.hora_inicio, 
                         u.nombre as nombre_medico,
                         f.id_factura,
                         f.metodo_pago
                  FROM {$this->table} c
                  INNER JOIN sesiones s ON c.id_sesion = s.id_sesion
                  INNER JOIN medicos m ON c.id_medico = m.id_medico
                  INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                  LEFT JOIN facturas f ON c.id_cita = f.id_cita
                  WHERE c.id_paciente = ? 
                  AND c.estado != 'cancelada'";
        
        if ($fecha) {
            $query .= " AND s.fecha_sesion = ?";
        }
        
        $query .= " ORDER BY c.fecha DESC, s.hora_inicio DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($fecha) {
            $stmt->bind_param("is", $id_paciente, $fecha);
        } else {
            $stmt->bind_param("i", $id_paciente);
        }
        
        $stmt->execute();
        return $stmt->get_result();
    }
    

    public function obtenerCitasPorMedico($id_medico, $fecha = null) {
        $query = "SELECT c.*, 
                         s.titulo, s.fecha_sesion, s.hora_inicio,
                         p.nombre as pnombre, 
                         p.telefono as ptelefono,
                         f.id_factura
                  FROM {$this->table} c
                  INNER JOIN sesiones s ON c.id_sesion = s.id_sesion
                  INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                  LEFT JOIN facturas f ON c.id_cita = f.id_cita
                  WHERE c.id_medico = ?";
        
        if ($fecha) {
            $query .= " AND s.fecha_sesion = ?";
        }
        
        $query .= " ORDER BY s.fecha_sesion DESC, s.hora_inicio DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($fecha) {
            $stmt->bind_param("is", $id_medico, $fecha);
        } else {
            $stmt->bind_param("i", $id_medico);
        }
        
        $stmt->execute();
        return $stmt->get_result();
    }
    
    /**
     * Cancelar una cita
     */
    public function cancelar($id_cita) {
        $query = "SELECT * FROM {$this->table} WHERE id_cita = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_cita);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            return ['success' => false, 'message' => 'Cita no encontrada'];
        }
        
        $query = "UPDATE {$this->table} SET estado = 'cancelada' WHERE id_cita = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_cita);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Cita cancelada exitosamente'];
        }
        
        return ['success' => false, 'message' => 'Error al cancelar la cita'];
    }
    

    public function marcarCitasPerdidas() {
        $hoy = date('Y-m-d');
        
        $query = "UPDATE {$this->table} c
                  INNER JOIN sesiones s ON c.id_sesion = s.id_sesion
                  SET c.estado = 'perdida'
                  WHERE s.fecha_sesion < ? 
                  AND c.estado = 'pendiente'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $hoy);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener todas las citas (admin)
     */
    public function obtenerTodasLasCitas($filtros = []) {
        $query = "SELECT c.*, 
                         s.titulo, s.fecha_sesion, s.hora_inicio,
                         p.nombre as paciente_nombre,
                         u.nombre as nombre_medico,
                         f.id_factura
                  FROM {$this->table} c
                  INNER JOIN sesiones s ON c.id_sesion = s.id_sesion
                  INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
                  INNER JOIN medicos m ON c.id_medico = m.id_medico
                  INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                  LEFT JOIN facturas f ON c.id_cita = f.id_cita
                  WHERE 1=1";
        
        $params = [];
        $types = "";
        
        if (!empty($filtros['fecha'])) {
            $query .= " AND s.fecha_sesion = ?";
            $params[] = $filtros['fecha'];
            $types .= "s";
        }
        
        if (!empty($filtros['estado'])) {
            $query .= " AND c.estado = ?";
            $params[] = $filtros['estado'];
            $types .= "s";
        }
        
        $query .= " ORDER BY s.fecha_sesion DESC, s.hora_inicio DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>