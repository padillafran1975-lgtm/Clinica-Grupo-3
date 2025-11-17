<?php
class Database {
    private $host = "localhost";
    private $db_name = "init_db";
    private $username = "root";
    private $password = "";
    private $conn;
    

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        
        return $this->conn;
    }
    
    /**
     * Cerrar conexión
     */
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>