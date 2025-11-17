<?php
class Sesion {
    private $conn;
    private $table = 'sesiones';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    

public function obtenerSesionesDisponibles($busqueda = null) {
    $today = date('Y-m-d');
    
    $query = "SELECT s.*, 
                     s.id_sesion,
                     s.cupo_maximo,
                     m.especialidad, 
                     u.nombre as medico_nombre, 
                     u.correo as medico_correo,
                     (SELECT COUNT(*) FROM citas c WHERE c.id_sesion = s.id_sesion AND c.estado != 'cancelada') as citas_reservadas
              FROM {$this->table} s 
              INNER JOIN medicos m ON s.id_medico = m.id_medico 
              INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
              WHERE s.fecha_sesion >= ? AND s.estado = 'activa'";
    
    if ($busqueda) {
        $query .= " AND (u.nombre LIKE ? 
                    OR s.titulo LIKE ? 
                    OR s.fecha_sesion LIKE ?
                    OR m.especialidad LIKE ?)";
    }
    
    $query .= " ORDER BY s.fecha_sesion ASC, s.hora_inicio ASC";
    
    $stmt = $this->conn->prepare($query);
    
    if ($busqueda) {
        $searchTerm = "%{$busqueda}%";
        $stmt->bind_param("sssss", $today, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    } else {
        $stmt->bind_param("s", $today);
    }
    
    $stmt->execute();
    return $stmt->get_result();
}
    
    public function obtenerSesionesPorMedico($id_medico, $desde = null) {
        $fecha = $desde ?? date('Y-m-d');
        
        $query = "SELECT s.*, u.nombre as nombre_medico, u.correo as docemail,
                  (SELECT COUNT(*) FROM citas c WHERE c.id_sesion = s.id_sesion AND c.estado != 'cancelada') as citas_reservadas
                  FROM {$this->table} s 
                  INNER JOIN medicos m ON s.id_medico = m.id_medico
                  INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                  WHERE s.id_medico = ? AND s.fecha_sesion >= ?
                  ORDER BY s.fecha_sesion ASC, s.hora_inicio ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $id_medico, $fecha);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    

    public function obtenerDetallesSesion($id_sesion) {
        $query = "SELECT s.*, m.especialidad, u.nombre as nombre_medico, u.correo as docemail, m.id_medico,
                  (SELECT COUNT(*) FROM citas c WHERE c.id_sesion = s.id_sesion AND c.estado != 'cancelada') as citas_reservadas
                  FROM {$this->table} s 
                  INNER JOIN medicos m ON s.id_medico = m.id_medico
                  INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                  WHERE s.id_sesion = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    

    public function crear($id_medico, $titulo, $fecha_sesion, $hora_inicio, $duracion_minutos, $cupo_maximo, $descripcion = null) {
        // que no exista conflicto de horario
        if (!$this->verificarDisponibilidadMedico($id_medico, $fecha_sesion, $hora_inicio)) {
            return ['success' => false, 'message' => 'El médico ya tiene una sesión en ese horario'];
        }
        
        $query = "INSERT INTO {$this->table} (id_medico, titulo, fecha_sesion, hora_inicio, duracion_minutos, cupo_maximo, descripcion, estado) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, 'activa')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssiis", $id_medico, $titulo, $fecha_sesion, $hora_inicio, $duracion_minutos, $cupo_maximo, $descripcion);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Sesión creada exitosamente', 'id_sesion' => $this->conn->insert_id];
        }
        
        return ['success' => false, 'message' => 'Error al crear la sesión'];
    }
    

    private function verificarDisponibilidadMedico($id_medico, $fecha, $hora) {
        $query = "SELECT COUNT(*) as total 
                  FROM {$this->table} 
                  WHERE id_medico = ? 
                  AND fecha_sesion = ? 
                  AND hora_inicio = ?
                  AND estado = 'activa'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $id_medico, $fecha, $hora);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'] == 0;
    }
    

    public function obtenerListaDoctores() {
        $query = "SELECT DISTINCT m.id_medico, u.nombre as nombre_medico, m.especialidad 
                  FROM medicos m
                  INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                  WHERE u.activo = 1
                  ORDER BY u.nombre ASC";
        
        $result = $this->conn->query($query);
        return $result;
    }
    

    public function obtenerTitulosSesiones() {
        $query = "SELECT DISTINCT titulo FROM {$this->table} ORDER BY titulo ASC";
        $result = $this->conn->query($query);
        
        return $result;
    }
    

    public function eliminar($id_sesion) {
        $query = "SELECT COUNT(*) as total FROM citas WHERE id_sesion = ? AND estado != 'cancelada'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['total'] > 0) {
            return ['success' => false, 'message' => 'No se puede eliminar: hay citas agendadas'];
        }
        
        $query = "UPDATE {$this->table} SET estado = 'cancelada' WHERE id_sesion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_sesion);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Sesión cancelada exitosamente'];
        }
        
        return ['success' => false, 'message' => 'Error al cancelar la sesión'];
    }
    

    public function actualizar($id_sesion, $datos) {
        $campos = [];
        $valores = [];
        $tipos = "";
        
        if (isset($datos['titulo'])) {
            $campos[] = "titulo = ?";
            $valores[] = $datos['titulo'];
            $tipos .= "s";
        }
        
        if (isset($datos['fecha_sesion'])) {
            $campos[] = "fecha_sesion = ?";
            $valores[] = $datos['fecha_sesion'];
            $tipos .= "s";
        }
        
        if (isset($datos['hora_inicio'])) {
            $campos[] = "hora_inicio = ?";
            $valores[] = $datos['hora_inicio'];
            $tipos .= "s";
        }
        
        if (isset($datos['cupo_maximo'])) {
            $campos[] = "cupo_maximo = ?";
            $valores[] = $datos['cupo_maximo'];
            $tipos .= "i";
        }
        
        if (isset($datos['descripcion'])) {
            $campos[] = "descripcion = ?";
            $valores[] = $datos['descripcion'];
            $tipos .= "s";
        }
        
        if (empty($campos)) {
            return ['success' => false, 'message' => 'No hay datos para actualizar'];
        }
        
        $query = "UPDATE {$this->table} SET " . implode(", ", $campos) . " WHERE id_sesion = ?";
        $valores[] = $id_sesion;
        $tipos .= "i";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($tipos, ...$valores);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Sesión actualizada exitosamente'];
        }
        
        return ['success' => false, 'message' => 'Error al actualizar la sesión'];
    }
}
?>