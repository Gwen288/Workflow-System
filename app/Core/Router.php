<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function get($route, $action) {
        $this->addRoute('GET', $route, $action);
    }

    public function post($route, $action) {
        $this->addRoute('POST', $route, $action);
    }

    private function addRoute($method, $route, $action) {
        // Convert route parameters (e.g. /request/{id}) to regex
        $routeRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
        $routeRegex = '#^' . $routeRegex . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'regex' => $routeRegex,
            'action' => $action
        ];
    }

    public function dispatch($uri, $method) {
        // Automatically handle subdirectories (e.g., from XAMPP htdocs)
        $basePath = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
        $uri = urldecode($uri);
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Remove trailing slash and query string
        $uri = strtok($uri, '?');
        $uri = rtrim($uri, '/');
        if (empty($uri)) {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['regex'], $uri, $matches)) {
                array_shift($matches); // Remove the full match

                if (is_callable($route['action'])) {
                    return call_user_func_array($route['action'], $matches);
                }

                if (is_array($route['action'])) {
                    $controller = new $route['action'][0]();
                    $method = $route['action'][1];
                    return call_user_func_array([$controller, $method], $matches);
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
