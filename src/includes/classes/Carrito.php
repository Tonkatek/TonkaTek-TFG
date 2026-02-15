<?php
require_once __DIR__ . '/../../config/database.php';

class Carrito {
    private $conn;
    private $table_name = "carrito";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    private function getIdentifier() {
        if (isset($_SESSION['usuario_id'])) {
            return ['tipo' => 'usuario', 'valor' => $_SESSION['usuario_id']];
        } else {
            if (!isset($_SESSION['session_id'])) {
                $_SESSION['session_id'] = session_id();
            }
            return ['tipo' => 'session', 'valor' => $_SESSION['session_id']];
        }
    }

    public function agregar($producto_id, $cantidad = 1) {
        $identifier = $this->getIdentifier();
        $query = "SELECT id, cantidad FROM " . $this->table_name . " WHERE producto_id = :producto_id AND ";
        if ($identifier['tipo'] === 'usuario') {
            $query .= "usuario_id = :usuario_id";
        } else {
            $query .= "session_id = :session_id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":producto_id", $producto_id);
        if ($identifier['tipo'] === 'usuario') {
            $stmt->bindParam(":usuario_id", $identifier['valor']);
        } else {
            $stmt->bindParam(":session_id", $identifier['valor']);
        }
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $nueva_cantidad = $row['cantidad'] + $cantidad;
            $update_query = "UPDATE " . $this->table_name . " SET cantidad = :cantidad WHERE id = :id";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindParam(":cantidad", $nueva_cantidad);
            $update_stmt->bindParam(":id", $row['id']);
            return $update_stmt->execute();
        } else {
            $insert_query = "INSERT INTO " . $this->table_name . " (usuario_id, session_id, producto_id, cantidad) VALUES (:usuario_id, :session_id, :producto_id, :cantidad)";
            $insert_stmt = $this->conn->prepare($insert_query);
            if ($identifier['tipo'] === 'usuario') {
                $insert_stmt->bindParam(":usuario_id", $identifier['valor']);
                $session_null = null;
                $insert_stmt->bindParam(":session_id", $session_null);
            } else {
                $usuario_null = null;
                $insert_stmt->bindParam(":usuario_id", $usuario_null);
                $insert_stmt->bindParam(":session_id", $identifier['valor']);
            }
            $insert_stmt->bindParam(":producto_id", $producto_id);
            $insert_stmt->bindParam(":cantidad", $cantidad);
            return $insert_stmt->execute();
        }
    }

    public function obtenerItems() {
        $identifier = $this->getIdentifier();
        $query = "SELECT c.*, p.nombre, p.precio, p.imagen, p.stock, (c.cantidad * p.precio) as subtotal FROM " . $this->table_name . " c INNER JOIN productos p ON c.producto_id = p.id WHERE ";
        if ($identifier['tipo'] === 'usuario') {
            $query .= "c.usuario_id = :usuario_id";
        } else {
            $query .= "c.session_id = :session_id";
        }
        $stmt = $this->conn->prepare($query);
        if ($identifier['tipo'] === 'usuario') {
            $stmt->bindParam(":usuario_id", $identifier['valor']);
        } else {
            $stmt->bindParam(":session_id", $identifier['valor']);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarCantidad($carrito_id, $cantidad) {
        if ($cantidad <= 0) {
            return $this->eliminar($carrito_id);
        }
        $query = "UPDATE " . $this->table_name . " SET cantidad = :cantidad WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":id", $carrito_id);
        return $stmt->execute();
    }

    public function eliminar($carrito_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $carrito_id);
        return $stmt->execute();
    }

    public function vaciar() {
        $identifier = $this->getIdentifier();
        $query = "DELETE FROM " . $this->table_name . " WHERE ";
        if ($identifier['tipo'] === 'usuario') {
            $query .= "usuario_id = :usuario_id";
        } else {
            $query .= "session_id = :session_id";
        }
        $stmt = $this->conn->prepare($query);
        if ($identifier['tipo'] === 'usuario') {
            $stmt->bindParam(":usuario_id", $identifier['valor']);
        } else {
            $stmt->bindParam(":session_id", $identifier['valor']);
        }
        return $stmt->execute();
    }

    public function contarItems() {
        $identifier = $this->getIdentifier();
        $query = "SELECT SUM(cantidad) as total FROM " . $this->table_name . " WHERE ";
        if ($identifier['tipo'] === 'usuario') {
            $query .= "usuario_id = :usuario_id";
        } else {
            $query .= "session_id = :session_id";
        }
        $stmt = $this->conn->prepare($query);
        if ($identifier['tipo'] === 'usuario') {
            $stmt->bindParam(":usuario_id", $identifier['valor']);
        } else {
            $stmt->bindParam(":session_id", $identifier['valor']);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function calcularTotal() {
        $items = $this->obtenerItems();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
}
?>
