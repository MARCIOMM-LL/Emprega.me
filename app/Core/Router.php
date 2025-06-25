<?php
namespace App\Core;

class Router {
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get($uri, $controllerAction): void
    {
        $this->routes['GET'][$uri] = $controllerAction;
    }

    public function post($uri, $controllerAction): void
    {
        $this->routes['POST'][$uri] = $controllerAction;
    }

    public function dispatch(): mixed
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            [$controllerName, $methodName] = explode('@', $this->routes[$method][$uri]);
            $controllerClass = "App\\Controllers\\$controllerName";

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $methodName)) {
                    return $controller->$methodName();
                } else {
                    echo "❌ Erro: Método '$methodName' não encontrado em $controllerClass.";
                }
            } else {
                echo "❌ Erro: Controlador '$controllerClass' não encontrado.";
            }
        } else {
            http_response_code(404);
            echo "404 - Página não encontrada.";
        }
        return null;
    }
}
