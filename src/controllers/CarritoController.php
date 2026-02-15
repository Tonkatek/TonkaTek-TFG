<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Carrito.php';
require_once __DIR__ . '/../models/Pedido.php';

class CarritoController extends Controller {
    
    public function index() {
        $carritoModel = new Carrito();
        
        $items = $carritoModel->obtenerItems();
        $total = $carritoModel->calcularTotal();
        $carrito_count = $carritoModel->contarItems();
        
        $data = [
            'items' => $items,
            'total' => $total,
            'carrito_count' => $carrito_count
        ];
        
        $this->view('carrito/index', $data);
    }
    
    public function agregar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['producto_id'])) {
            $this->json(['success' => false, 'message' => 'Producto no especificado'], 400);
            return;
        }
        
        $carritoModel = new Carrito();
        $cantidad = isset($data['cantidad']) ? (int)$data['cantidad'] : 1;
        
        if ($carritoModel->agregar($data['producto_id'], $cantidad)) {
            $this->json([
                'success' => true,
                'total_items' => $carritoModel->contarItems()
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Error al agregar al carrito'], 500);
        }
    }
    
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false], 405);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['carrito_id']) || !isset($data['cantidad'])) {
            $this->json(['success' => false], 400);
            return;
        }
        
        $carritoModel = new Carrito();
        
        if ($carritoModel->actualizarCantidad($data['carrito_id'], $data['cantidad'])) {
            $this->json([
                'success' => true,
                'total' => $carritoModel->calcularTotal()
            ]);
        } else {
            $this->json(['success' => false], 500);
        }
    }
    
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false], 405);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['carrito_id'])) {
            $this->json(['success' => false], 400);
            return;
        }
        
        $carritoModel = new Carrito();
        
        if ($carritoModel->eliminar($data['carrito_id'])) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false], 500);
        }
    }
    
    public function vaciar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false], 405);
            return;
        }
        
        $carritoModel = new Carrito();
        
        if ($carritoModel->vaciar()) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false], 500);
        }
    }
    
    public function obtener() {
        $carritoModel = new Carrito();
        
        $this->json([
            'success' => true,
            'items' => $carritoModel->obtenerItems(),
            'total' => $carritoModel->calcularTotal(),
            'total_items' => $carritoModel->contarItems()
        ]);
    }
    
    public function realizarPedido() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar que haya dirección de envío
        if (!isset($data['direccion_envio']) || empty(trim($data['direccion_envio']))) {
            $this->json(['success' => false, 'message' => 'La dirección de envío es obligatoria'], 400);
            return;
        }
        
        $carritoModel = new Carrito();
        $pedidoModel = new Pedido();
        
        // Obtener items del carrito
        $items = $carritoModel->obtenerItems();
        
        if (empty($items)) {
            $this->json(['success' => false, 'message' => 'El carrito está vacío'], 400);
            return;
        }
        
        // Calcular total
        $total = $carritoModel->calcularTotal();
        
        // Crear el pedido
        $pedido_id = $pedidoModel->crear(
            $_SESSION['usuario_id'],
            $total,
            trim($data['direccion_envio']),
            $items
        );
        
        if ($pedido_id) {
            // Vaciar el carrito después de crear el pedido
            $carritoModel->vaciar();
            
            $this->json([
                'success' => true,
                'message' => 'Pedido realizado con éxito',
                'pedido_id' => $pedido_id
            ]);
        } else {
            $this->json([
                'success' => false,
                'message' => 'Error al procesar el pedido. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }
}
?>
