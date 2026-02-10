<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Carrito.php';
require_once __DIR__ . '/../models/Pedido.php';

class PerfilController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $usuarioModel = new Usuario();
        $carritoModel = new Carrito();
        
        $datos = $usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        
        $data = [
            'usuario' => $datos,
            'carrito_count' => $carritoModel->contarItems()
        ];
        
        $this->view('perfil/index', $data);
    }
    
    public function pedidos() {
        $this->requireAuth();
        
        $carritoModel = new Carrito();
        $pedidoModel = new Pedido();
        
        // Obtener todos los pedidos del usuario
        $pedidos = $pedidoModel->obtenerPorUsuario($_SESSION['usuario_id']);
        
        $data = [
            'pedidos' => $pedidos,
            'carrito_count' => $carritoModel->contarItems()
        ];
        
        $this->view('perfil/pedidos', $data);
    }
    
    public function verPedido($pedido_id) {
        $this->requireAuth();
        
        $carritoModel = new Carrito();
        $pedidoModel = new Pedido();
        
        // Obtener información del pedido
        $pedido = $pedidoModel->obtenerPorId($pedido_id, $_SESSION['usuario_id']);
        
        if (!$pedido) {
            showAlert('Pedido no encontrado', 'error');
            $this->redirect('/pedidos');
            return;
        }
        
        // Obtener detalles del pedido
        $detalles = $pedidoModel->obtenerDetalles($pedido_id, $_SESSION['usuario_id']);
        
        $data = [
            'pedido' => $pedido,
            'detalles' => $detalles,
            'carrito_count' => $carritoModel->contarItems()
        ];
        
        $this->view('perfil/ver-pedido', $data);
    }
}
?>
