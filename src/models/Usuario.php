<?php
require_once __DIR__ . '/../config/database.php';
class Usuario {
    private $conn;
    private $table_name = 'usuarios';
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function registrar($nombre, $email, $password, $telefono = null, $direccion = null) {
        try {
            $query = 'INSERT INTO ' . $this->table_name . ' (nombre, email, password, telefono, direccion) VALUES (:nombre, :email, :password, :telefono, :direccion)';
            $stmt = $this->conn->prepare($query);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccion', $direccion);
            return $stmt->execute();
        } catch(PDOException $e) { return false; }
    }
    public function login($email, $password) {
        try {
            $query = 'SELECT id, nombre, email, password, rol FROM ' . $this->table_name . ' WHERE email = :email LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password, $row["password"])) {
                    return $row;
                }
            }
            return false;
        } catch(PDOException $e) { return false; }
    }
    public function obtenerPorId($id) {
        $query = 'SELECT id, nombre, email, telefono, direccion, rol, fecha_registro FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function emailExiste($email) {
        $query = 'SELECT id FROM ' . $this->table_name . ' WHERE email = :email LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>