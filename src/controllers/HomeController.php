<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Carrito.php';

class HomeController extends Controller {
    
    public function index() {
        $productoModel = new Producto();
        $categoriaModel = new Categoria();
        $carritoModel = new Carrito();
        
        $data = [
            'productos_destacados' => $productoModel->obtenerDestacados(8),
            'categorias' => $categoriaModel->obtenerTodas(),
            'carrito_count' => $carritoModel->contarItems()
        ];
        
        $this->view('home/index', $data);
    }
}
?>
