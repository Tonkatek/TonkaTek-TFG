<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../config/database.php';

class AdminController extends Controller {
    
    private function logDebug($message, $data = null) {
        error_log("[AdminController] $message" . ($data ? ": " . json_encode($data) : ""));
    }
    
    public function index() {
        $this->logDebug("index() llamado");
        $this->logDebug("Sesión actual", $_SESSION);
        
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
        
        $this->logDebug("Cargando vista admin/index con " . count($productos) . " productos");
        $this->view('admin/index', $data);
    }
    
    public function mostrarCrear() {
        $this->logDebug("mostrarCrear() llamado");
        $this->requireAuth();
        $this->requireAdmin();
        
        $categoriaModel = new Categoria();
        $categorias = $categoriaModel->obtenerTodas();
        
        $data = [
            'categorias' => $categorias
        ];
        
        $this->view('admin/form', $data);
    }
    
    public function redirigirEditar() {
        $this->logDebug("redirigirEditar() llamado");
        
        $this->requireAuth();
        $this->requireAdmin();
        
        // Obtener ID de query string si existe
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        
        if ($id) {
            // Redirigir a la nueva ruta MVC
            $this->logDebug("Redirigiendo de /editar?id=$id a /admin/editar/$id");
            $this->redirect("/admin/editar/$id");
        } else {
            // Si no hay ID, redirigir al panel de admin
            $this->logDebug("No hay ID, redirigiendo a /admin");
            $this->redirect('/admin');
        }
    }
    
    public function mostrarEditar($id) {
        $this->logDebug("mostrarEditar($id) llamado");
        $this->requireAuth();
        $this->requireAdmin();
        
        $productoModel = new Producto();
        $categoriaModel = new Categoria();
        
        $producto = $productoModel->obtenerPorId($id);
        $categorias = $categoriaModel->obtenerTodas();
        
        if (!$producto) {
            $this->logDebug("ERROR: Producto $id no encontrado");
            showAlert('Producto no encontrado', 'error');
            $this->redirect('/admin');
            return;
        }
        
        $this->logDebug("Producto encontrado: " . $producto['nombre']);
        
        $data = [
            'producto' => $producto,
            'categorias' => $categorias
        ];
        
        $this->view('admin/form', $data);
    }
    
    public function crear() {
        $this->logDebug("crear() llamado");
        $this->logDebug("REQUEST_METHOD", $_SERVER['REQUEST_METHOD']);
        $this->logDebug("REQUEST_URI", $_SERVER['REQUEST_URI']);
        $this->logDebug("Sesión actual", $_SESSION);
        
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->logDebug("Es POST, procesando datos");
            $this->logDebug("POST data", $_POST);
            
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
            
            $this->logDebug("Datos a insertar", $datos);
            
            if ($productoModel->crear($datos)) {
                $this->logDebug("✅ Producto creado con éxito");
                showAlert('Producto creado exitosamente', 'success');
            } else {
                $this->logDebug("❌ Fallo al crear producto");
                showAlert('Error al crear el producto', 'error');
            }
            
            $this->logDebug("Redirigiendo a /admin");
            $this->redirect('/admin');
        } else {
            $this->logDebug("❌ ERROR: No es POST, método: " . $_SERVER['REQUEST_METHOD']);
        }
    }
    
    public function editar($id) {
        $this->logDebug("editar($id) llamado");
        $this->logDebug("REQUEST_METHOD", $_SERVER['REQUEST_METHOD']);
        $this->logDebug("REQUEST_URI", $_SERVER['REQUEST_URI']);
        $this->logDebug("Sesión actual", $_SESSION);
        
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->logDebug("Es POST, procesando edición");
            $this->logDebug("POST data", $_POST);
            
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
            
            $this->logDebug("Datos a actualizar para ID $id", $datos);
            
            if ($productoModel->actualizar($id, $datos)) {
                $this->logDebug("✅ Producto $id actualizado con éxito");
                showAlert('Producto actualizado exitosamente', 'success');
            } else {
                $this->logDebug("❌ Fallo al actualizar producto $id");
                showAlert('Error al actualizar el producto', 'error');
            }
            
            $this->logDebug("Redirigiendo a /admin");
            $this->redirect('/admin');
        } else {
            $this->logDebug("❌ ERROR: No es POST en editar, método: " . $_SERVER['REQUEST_METHOD']);
            // En lugar de fallar silenciosamente, mostrar el formulario de edición
            $this->mostrarEditar($id);
        }
    }
    
    public function eliminar($id) {
        $this->logDebug("eliminar($id) llamado");
        $this->logDebug("REQUEST_METHOD", $_SERVER['REQUEST_METHOD']);
        $this->logDebug("REQUEST_URI", $_SERVER['REQUEST_URI']);
        $this->logDebug("Sesión actual", $_SESSION);
        $this->logDebug("POST data", $_POST);
        $this->logDebug("GET data", $_GET);
        $this->logDebug("Cookies", $_COOKIE);
        
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->logDebug("✅ Es POST, eliminando producto $id");
            $productoModel = new Producto();
            
            if ($productoModel->eliminar($id)) {
                $this->logDebug("✅ Producto $id eliminado con éxito");
                showAlert('Producto eliminado correctamente', 'success');
            } else {
                $this->logDebug("❌ Fallo al eliminar producto $id");
                showAlert('Error al eliminar el producto', 'error');
            }
            
            $this->logDebug("Redirigiendo a /admin");
            $this->redirect('/admin');
        } else {
            $this->logDebug("❌ ERROR: No es POST en eliminar, método: " . $_SERVER['REQUEST_METHOD']);
            $this->logDebug("Headers enviados", headers_list());
            
            // Mostrar error al usuario
            showAlert('Error: La petición debe ser POST', 'error');
            $this->redirect('/admin');
        }
    }
    
    /**
     * Listar todos los pedidos
     */
    public function pedidos() {
        $this->logDebug("pedidos() llamado");
        $this->requireAuth();
        $this->requireAdmin();
        
        require_once __DIR__ . '/../models/Pedido.php';
        $pedidoModel = new Pedido();
        
        $pedidos = $pedidoModel->obtenerTodos(100, 0);
        
        $data = [
            'pedidos' => $pedidos
        ];
        
        $this->view('admin/pedidos', $data);
    }
    
    /**
     * Ver detalles de un pedido
     */
    public function verPedido($id) {
        $this->logDebug("verPedido($id) llamado");
        $this->requireAuth();
        $this->requireAdmin();
        
        require_once __DIR__ . '/../models/Pedido.php';
        $pedidoModel = new Pedido();
        
        $pedido = $pedidoModel->obtenerPorId($id);
        $detalles = $pedidoModel->obtenerDetalles($id);
        
        if (!$pedido) {
            showAlert('Pedido no encontrado', 'error');
            $this->redirect('/admin/pedidos');
            return;
        }
        
        $data = [
            'pedido' => $pedido,
            'detalles' => $detalles
        ];
        
        $this->view('admin/ver-pedido', $data);
    }
    
    /**
     * Cambiar estado de un pedido
     */
    public function cambiarEstado($id) {
        $this->logDebug("cambiarEstado($id) llamado");
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../models/Pedido.php';
            $pedidoModel = new Pedido();
            
            $nuevo_estado = $_POST['estado'] ?? '';
            
            if ($pedidoModel->actualizarEstado($id, $nuevo_estado)) {
                $this->logDebug("✅ Estado del pedido $id actualizado a $nuevo_estado");
                showAlert("Estado actualizado a '$nuevo_estado' exitosamente", 'success');
            } else {
                $this->logDebug("❌ Error al actualizar estado del pedido $id");
                showAlert('Error al actualizar el estado del pedido', 'error');
            }
            
            // Redirigir de vuelta a donde vino (referer) o a la vista del pedido
            $referer = $_SERVER['HTTP_REFERER'] ?? '';
            if (strpos($referer, '/pedido/') !== false && strpos($referer, '/admin/pedido/') === false) {
                // Vino de la vista del usuario, volver allí
                $this->redirect('/pedido/' . $id);
            } else {
                // Vino de la vista de admin o lugar desconocido
                $this->redirect('/admin/pedido/' . $id);
            }
        } else {
            $this->redirect('/admin/pedidos');
        }
    }
}
?>