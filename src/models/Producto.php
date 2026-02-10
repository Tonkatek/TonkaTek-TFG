<?php
require_once __DIR__ . '/../config/database.php';

class Producto {
    private $conn;
    private $table_name = "productos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerTodos($page = 1, $limit = 12, $categoria_id = null, $busqueda = null) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE 1=1";
        
        if ($categoria_id) {
            $query .= " AND p.categoria_id = :categoria_id";
        }
        
        if ($busqueda) {
            $query .= " AND (p.nombre LIKE :busqueda OR p.descripcion LIKE :busqueda OR p.marca LIKE :busqueda)";
        }
        
        $query .= " ORDER BY p.id DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        if ($categoria_id) {
            $stmt->bindParam(":categoria_id", $categoria_id, PDO::PARAM_INT);
        }
        
        if ($busqueda) {
            $busqueda_param = "%{$busqueda}%";
            $stmt->bindParam(":busqueda", $busqueda_param);
        }
        
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre, c.slug as categoria_slug
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDestacados($limit = 8) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.destacado = 1
                  ORDER BY RAND()
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarTotal($categoria_id = null, $busqueda = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE 1=1";
        
        if ($categoria_id) {
            $query .= " AND categoria_id = :categoria_id";
        }
        
        if ($busqueda) {
            $query .= " AND (nombre LIKE :busqueda OR descripcion LIKE :busqueda OR marca LIKE :busqueda)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($categoria_id) {
            $stmt->bindParam(":categoria_id", $categoria_id, PDO::PARAM_INT);
        }
        
        if ($busqueda) {
            $busqueda_param = "%{$busqueda}%";
            $stmt->bindParam(":busqueda", $busqueda_param);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    public function crear($datos) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, descripcion, precio, stock, categoria_id, marca, modelo, imagen, especificaciones, destacado) 
                  VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :marca, :modelo, :imagen, :especificaciones, :destacado)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $datos['nombre']);
        $stmt->bindParam(":descripcion", $datos['descripcion']);
        $stmt->bindParam(":precio", $datos['precio']);
        $stmt->bindParam(":stock", $datos['stock']);
        $stmt->bindParam(":categoria_id", $datos['categoria_id']);
        $stmt->bindParam(":marca", $datos['marca']);
        $stmt->bindParam(":modelo", $datos['modelo']);
        $stmt->bindParam(":imagen", $datos['imagen']);
        $stmt->bindParam(":especificaciones", $datos['especificaciones']);
        $stmt->bindParam(":destacado", $datos['destacado']);
        
        return $stmt->execute();
    }

    public function actualizar($id, $datos) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                      stock = :stock, categoria_id = :categoria_id, marca = :marca, 
                      modelo = :modelo, imagen = :imagen, especificaciones = :especificaciones, 
                      destacado = :destacado
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre", $datos['nombre']);
        $stmt->bindParam(":descripcion", $datos['descripcion']);
        $stmt->bindParam(":precio", $datos['precio']);
        $stmt->bindParam(":stock", $datos['stock']);
        $stmt->bindParam(":categoria_id", $datos['categoria_id']);
        $stmt->bindParam(":marca", $datos['marca']);
        $stmt->bindParam(":modelo", $datos['modelo']);
        $stmt->bindParam(":imagen", $datos['imagen']);
        $stmt->bindParam(":especificaciones", $datos['especificaciones']);
        $stmt->bindParam(":destacado", $datos['destacado']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>
