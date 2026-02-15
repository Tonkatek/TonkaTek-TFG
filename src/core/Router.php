<?php
class Router {
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }

    public function get($uri, $handler) {
        $this->addRoute('GET', $uri, $handler);
    }

    public function post($uri, $handler) {
        $this->addRoute('POST', $uri, $handler);
    }

    private function addRoute($method, $uri, $handler) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $this->basePath . $uri,
            'handler' => $handler
        ];
    }

    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Remover el basePath si existe
        if ($this->basePath && strpos($requestUri, $this->basePath) === 0) {
            $requestUri = substr($requestUri, strlen($this->basePath));
        }

        foreach ($this->routes as $route) {
            $pattern = $this->buildPattern($route['uri']);
            
            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remover el primer elemento (match completo)
                return $this->callHandler($route['handler'], $matches);
            }
        }

        // 404 - No se encontró la ruta
        http_response_code(404);
        echo "404 - Página no encontrada";
    }

    private function buildPattern($uri) {
        // Convertir {parámetros} en grupos de captura regex
        // Permitimos letras, números, guiones y guiones bajos en los parámetros
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $uri);
        
        // Escapar caracteres especiales del regex que puedan estar en la URI
        // pero NO las barras ni los paréntesis que acabamos de agregar
        $pattern = str_replace('.', '\.', $pattern);
        
        return '#^' . $pattern . '$#';
    }

    private function callHandler($handler, $params = []) {
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        if (is_string($handler)) {
            list($controller, $method) = explode('@', $handler);
            
            $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';
            
            if (!file_exists($controllerFile)) {
                throw new Exception("Controller file not found: $controllerFile");
            }

            require_once $controllerFile;

            if (!class_exists($controller)) {
                throw new Exception("Controller class not found: $controller");
            }

            $controllerInstance = new $controller();

            if (!method_exists($controllerInstance, $method)) {
                throw new Exception("Method $method not found in controller $controller");
            }

            return call_user_func_array([$controllerInstance, $method], $params);
        }
    }
}
?>
