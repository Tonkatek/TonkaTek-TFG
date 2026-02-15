<?php
class Controller {
    
    protected function view($view, $data = []) {
        // Extraer datos para que estén disponibles como variables
        extract($data);
        
        // Incluir el layout con el contenido de la vista
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View not found: $view");
        }
        
        // Capturar el contenido de la vista
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        
        // Cargar el layout si existe
        $layoutFile = __DIR__ . '/../views/layouts/main.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function isLoggedIn() {
        return isset($_SESSION['usuario_id']);
    }

    protected function isAdmin() {
        return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
    }

    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }

    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }
    }
}
?>
