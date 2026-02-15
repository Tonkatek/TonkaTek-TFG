<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Carrito.php';

class ProductoController extends Controller {
    
    public function index() {
        $productoModel = new Producto();
        $categoriaModel = new Categoria();
        $carritoModel = new Carrito();
        
        // Obtener parámetros de búsqueda y filtros
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
        $busqueda = isset($_GET['busqueda']) ? sanitizeInput($_GET['busqueda']) : null;
        
        // Obtener productos
        $productos = $productoModel->obtenerTodos($page, ITEMS_PER_PAGE, $categoria_id, $busqueda);
        $total_productos = $productoModel->contarTotal($categoria_id, $busqueda);
        $total_paginas = ceil($total_productos / ITEMS_PER_PAGE);
        
        // Obtener categorías
        $categorias = $categoriaModel->obtenerTodas();
        
        // Nombre de categoría actual
        $categoria_actual = 'Todos los Productos';
        if ($categoria_id) {
            $cat = $categoriaModel->obtenerPorId($categoria_id);
            if ($cat) {
                $categoria_actual = $cat['nombre'];
            }
        }
        
        $data = [
            'productos' => $productos,
            'categorias' => $categorias,
            'categoria_actual' => $categoria_actual,
            'categoria_id' => $categoria_id,
            'busqueda' => $busqueda,
            'page' => $page,
            'total_paginas' => $total_paginas,
            'total_productos' => $total_productos,
            'carrito_count' => $carritoModel->contarItems()
        ];
        
        $this->view('productos/index', $data);
    }
    
    public function show($id) {
        $productoModel = new Producto();
        $carritoModel = new Carrito();
        
        $producto = $productoModel->obtenerPorId($id);
        
        if (!$producto) {
            $this->redirect('/productos');
            return;
        }
        
        $data = [
            'producto' => $producto,
            'carrito_count' => $carritoModel->contarItems()
        ];
        
        $this->view('productos/show', $data);
    }
}
?>
