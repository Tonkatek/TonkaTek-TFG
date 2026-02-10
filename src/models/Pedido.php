<?php
require_once __DIR__ . '/../config/database.php';

class Pedido {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Crear un nuevo pedido
     */
    public function crear($usuario_id, $total, $direccion_envio, $items) {
        try {
            $this->conn->beginTransaction();
            
            // Crear el pedido principal
            $sql = "INSERT INTO pedidos (usuario_id, total, direccion_envio, estado) 
                    VALUES (:usuario_id, :total, :direccion_envio, 'pendiente')";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':direccion_envio', $direccion_envio, PDO::PARAM_STR);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al crear el pedido");
            }
            
            $pedido_id = $this->conn->lastInsertId();
            
            // Insertar los detalles del pedido
            $sql_detalle = "INSERT INTO pedidos_detalle 
                           (pedido_id, producto_id, cantidad, precio_unitario, subtotal) 
                           VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";
            
            $stmt_detalle = $this->conn->prepare($sql_detalle);
            
            foreach ($items as $item) {
                $stmt_detalle->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
                $stmt_detalle->bindParam(':producto_id', $item['producto_id'], PDO::PARAM_INT);
                $stmt_detalle->bindParam(':cantidad', $item['cantidad'], PDO::PARAM_INT);
                $stmt_detalle->bindParam(':precio_unitario', $item['precio'], PDO::PARAM_STR);
                $stmt_detalle->bindParam(':subtotal', $item['subtotal'], PDO::PARAM_STR);
                
                if (!$stmt_detalle->execute()) {
                    throw new Exception("Error al insertar detalles del pedido");
                }
                
                // Actualizar stock del producto
                $sql_stock = "UPDATE productos SET stock = stock - :cantidad WHERE id = :producto_id";
                $stmt_stock = $this->conn->prepare($sql_stock);
                $stmt_stock->bindParam(':cantidad', $item['cantidad'], PDO::PARAM_INT);
                $stmt_stock->bindParam(':producto_id', $item['producto_id'], PDO::PARAM_INT);
                $stmt_stock->execute();
            }
            
            $this->conn->commit();
            return $pedido_id;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear pedido: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener pedidos de un usuario
     */
    public function obtenerPorUsuario($usuario_id) {
        $sql = "SELECT p.*, 
                       (SELECT COUNT(*) FROM pedidos_detalle WHERE pedido_id = p.id) as total_items
                FROM pedidos p
                WHERE p.usuario_id = :usuario_id
                ORDER BY p.fecha_pedido DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener detalles de un pedido específico
     */
    public function obtenerDetalles($pedido_id, $usuario_id = null) {
        $sql = "SELECT pd.*, p.nombre, p.imagen, p.marca, p.modelo, c.nombre as categoria_nombre
                FROM pedidos_detalle pd
                INNER JOIN productos p ON pd.producto_id = p.id
                LEFT JOIN categorias c ON p.categoria_id = c.id
                INNER JOIN pedidos ped ON pd.pedido_id = ped.id
                WHERE pd.pedido_id = :pedido_id";
        
        // Si se especifica usuario_id, verificar que el pedido pertenezca a ese usuario
        if ($usuario_id !== null) {
            $sql .= " AND ped.usuario_id = :usuario_id";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
        
        if ($usuario_id !== null) {
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener información completa de un pedido
     */
    public function obtenerPorId($pedido_id, $usuario_id = null) {
        $sql = "SELECT * FROM pedidos WHERE id = :pedido_id";
        
        if ($usuario_id !== null) {
            $sql .= " AND usuario_id = :usuario_id";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
        
        if ($usuario_id !== null) {
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Actualizar estado de un pedido
     */
    public function actualizarEstado($pedido_id, $nuevo_estado) {
        $estados_permitidos = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'];
        
        if (!in_array($nuevo_estado, $estados_permitidos)) {
            return false;
        }
        
        $sql = "UPDATE pedidos SET estado = :estado WHERE id = :pedido_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':estado', $nuevo_estado, PDO::PARAM_STR);
        $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener todos los pedidos (para admin)
     */
    public function obtenerTodos($limit = 50, $offset = 0) {
        $sql = "SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email,
                       (SELECT COUNT(*) FROM pedidos_detalle WHERE pedido_id = p.id) as total_items
                FROM pedidos p
                INNER JOIN usuarios u ON p.usuario_id = u.id
                ORDER BY p.fecha_pedido DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
