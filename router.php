<?php

class Router {
    private $routes = [];

    public function get($path, $file) {
        $this->routes['GET'][$this->normalizePath($path)] = $file;
    }

    public function post($path, $file) {
        $this->routes['POST'][$this->normalizePath($path)] = $file;
    }

    public function dispatch() {
        // Capture the request method and URI
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        
        // Remove the base path from the URI
        $basePath = trim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($basePath && strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
        
        // Normalize route to match the registered paths
        $route = $this->normalizePath($requestUri);
        
        // Check if the route exists
        if (isset($this->routes[$requestMethod][$route])) {
            include $this->routes[$requestMethod][$route];
        } else {
            include 'views/not_found.php';
        }
    }

    private function normalizePath($path) {
        // Remove leading and trailing slashes
        return trim($path, '/');
    }
}
?>
