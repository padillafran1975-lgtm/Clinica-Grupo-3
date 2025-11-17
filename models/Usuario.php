<?php
require_once __DIR__ . '/../config/conexion.php';

class Usuario {
    private $conn;
    private $table = 'usuarios';
    
    public $id_usuario;
    public $nombre_usuario;
    public $correo;
    public $contrasena_hash;
    public $id_rol;
    public $activo;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Verificamos credenciales
     */
    public function login($correo, $password) {

    $query = "SELECT u.*, r.nombre AS nombre_rol
              FROM usuarios u
              INNER JOIN roles r ON u.id_rol = r.id_rol
              WHERE u.correo = ? AND u.activo = 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Usuario no encontrado'];
    }

    $user = $result->fetch_assoc();


    if ($password === $user['contrasena_hash']) {

        return [
            'success' => true,
            'user' => [
                'id'         => $user['id_usuario'],
                'nombre'     => $user['nombre'],
                'correo'     => $user['correo'],
                'rol_id'     => $user['id_rol'],
                'rol_nombre' => $user['nombre_rol']
            ]
        ];
    }

    return ['success' => false, 'message' => 'Contraseña incorrecta'];
}

    
    public function obtenerPorCorreo($correo) {
        $query = "SELECT u.*, r.nombre AS nombre_rol
          FROM {$this->table} u
          INNER JOIN roles r ON u.id_rol = r.id_rol
          WHERE u.correo = ? AND u.activo = 1";

        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    

    public function obtenerTipoUsuario($id_rol) {
        $roles = [
            1 => 'a', // Admin
            2 => 'd', // Medico
            3 => 'p', // Paciente
            4 => 'e'  
        ];
        
        return $roles[$id_rol] ?? null;
    }
    

    public function obtenerIdEspecifico($id_usuario, $tipo) {
    switch($tipo) {
        case 'p': 
            $query = "SELECT id_paciente FROM pacientes WHERE id_usuario = ?";
            break;

        case 'd': 
            $query = "SELECT id_medico FROM medicos WHERE id_usuario = ?";
            break;

        default:
            return null;
    }

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row ? array_values($row)[0] : null;
}

    

    public function registrar($nombre, $correo, $password, $id_rol) {
        $query = "SELECT id_usuario FROM {$this->table} WHERE correo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'El correo ya está registrado'];
        }
        

        $query = "INSERT INTO {$this->table} (nombre_usuario, correo, contrasena_hash, id_rol, activo) 
                  VALUES (?, ?, ?, ?, 1)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $nombre, $correo, $password, $id_rol);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Usuario registrado exitosamente', 'id' => $this->conn->insert_id];
        }
        
        return ['success' => false, 'message' => 'Error al registrar usuario'];
    }
}
?>