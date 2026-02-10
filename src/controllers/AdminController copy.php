<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../config/database.php';

class AdminController extends Controller {
    
    public function index() {
        error_log("DEBUG: AdminController->index() llamado");
        $this->requireAuth();
        $this->requireAdmin();
        
        $productoModel = new Producto();
        $categoriaModel = new Categoria();
        
        $productos = $productoModel->obtenerTodos(1, 100);
        $categorias = $categoriaModel->obtenerTodas();
        
        // Estadísticas
        $database = new Database();
        $conn = $database->getConnection();
        
        $stats = [];
        $stats['total_productos'] = $conn->query("SELECT COUNT(*) FROM productos")->fetchColumn();
        $stats['total_usuarios'] = $conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        $stats['total_pedidos'] = $conn->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
        
        $data = [
            'productos' => $productos,
            'categorias' => $categorias,
            'stats' => $stats
        ];
        
        $this->view('admin/index', $data);
    }
    
    public function mostrarCrear() {
        error_log("DEBUG: AdminController->mostrarCrear() llamado");
        $this->requireAuth();
        $this->requireAdmin();
        
        $categoriaModel = new Categoria();
        $categorias = $categoriaModel->obtenerTodas();
        
        $data = [
            'categorias' => $categorias
        ];
        
        $this->view('admin/form', $data);
    }
    
    public function mostrarEditar($id) {
        error_log("DEBUG: AdminController->mostrarEditar($id) llamado");
        $this->requireAuth();
        $this->requireAdmin();
        
        $productoModel = new Producto();
        $categoriaModel = new Categoria();
        
        $producto = $productoModel->obtenerPorId($id);
        $categorias = $categoriaModel->obtenerTodas();
        
        if (!$producto) {
            error_log("ERROR: Producto $id no encontrado");
            showAlert('Producto no encontrado', 'error');
            $this->redirect('/admin');
            return;
        }
        
        error_log("DEBUG: Producto encontrado: " . $producto['nombre']);
        
        $data = [
            'producto' => $producto,
            'categorias' => $categorias
        ];
        
        $this->view('admin/form', $data);
    }
    
    public function crear() {
        error_log("DEBUG: AdminController->crear() llamado");
        error_log("DEBUG: REQUEST_METHOD = " . $_SERVER['REQUEST_METHOD']);
        
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("DEBUG: Es POST, procesando datos");
            $productoModel = new Producto();
            
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'stock' => $_POST['stock'] ?? 0,
                'categoria_id' => $_POST['categoria_id'] ?? null,
                'marca' => $_POST['marca'] ?? '',
                'modelo' => $_POST['modelo'] ?? '',
                'imagen' => $_POST['imagen'] ?? '',
                'especificaciones' => $_POST['especificaciones'] ?? '{}',
                'destacado' => isset($_POST['destacado']) ? 1 : 0
            ];
            
            error_log("DEBUG: Datos a insertar: " . json_encode($datos));
            
            if ($productoModel->crear($datos)) {
                error_log("DEBUG: Producto creado con éxito");
                showAlert('Producto creado exitosamente', 'success');
            } else {
                error_log("ERROR: Fallo al crear producto");
                showAlert('Error al crear el producto', 'error');
            }
            
            error_log("DEBUG: Redirigiendo a /admin");
            $this->redirect('/admin');
        } else {
            error_log("ERROR: No es POST, método: " . $_SERVER['REQUEST_METHOD']);
        }
    }
    
    public function editar($id) {
        error_log("DEBUG: AdminController->editar($id) llamado");
        error_log("DEBUG: REQUEST_METHOD = " . $_SERVER['REQUEST_METHOD']);
        
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("DEBUG: Es POST, procesando edición");
            $productoModel = new Producto();
            
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'stock' => $_POST['stock'] ?? 0,
                'categoria_id' => $_POST['categoria_id'] ?? null,
                'marca' => $_POST['marca'] ?? '',
                'modelo' => $_POST['modelo'] ?? '',
                'imagen' => $_POST['imagen'] ?? '',
                'especificaciones' => $_POST['especificaciones'] ?? '{}',
                'destacado' => isset($_POST['destacado']) ? 1 : 0
            ];
            
            error_log("DEBUG: Datos a actualizar para ID $id: " . json_encode($datos));
            
            if ($productoModel->actualizar($id, $datos)) {
                error_log("DEBUG: Producto $id actualizado con éxito");
                showAlert('Producto actualizado exitosamente', 'success');
            } else {
                error_log("ERROR: Fallo al actualizar producto $id");
                showAlert('Error al actualizar el producto', 'error');
            }
            
            error_log("DEBUG: Redirigiendo a /admin");
            $this->redirect('/admin');
        } else {
            error_log("ERROR: No es POST en editar, método: " . $_SERVER['REQUEST_METHOD']);
        }
    }
    
    public function eliminar($id) {
        error_log("DEBUG: AdminController->eliminar($id) llamado");
        error_log("DEBUG: REQUEST_METHOD = " . $_SERVER['REQUEST_METHOD']);
        
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("DEBUG: Es POST, eliminando producto $id");
            $productoModel = new Producto();
            
            if ($productoModel->eliminar($id)) {
                error_log("DEBUG: Producto $id eliminado con éxito");
                showAlert('Producto eliminado correctamente', 'success');
            } else {
                error_log("ERROR: Fallo al eliminar producto $id");
                showAlert('Error al eliminar el producto', 'error');
            }
            
            error_log("DEBUG: Redirigiendo a /admin");
            $this->redirect('/admin');
        } else {
            error_log("ERROR: No es POST en eliminar, método: " . $_SERVER['REQUEST_METHOD']);
        }
    }
}
?>
