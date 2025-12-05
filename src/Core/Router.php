<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            [$controllerClass, $action] = $this->routes[$method][$path];

            $pdo = \App\Config\Database::getConnection();
            $repository = new \App\Repository\ProductRepository($pdo);

            $controller = new $controllerClass($repository);
            $controller->$action();
        } else {
            http_response_code(404);
            echo "Página não encontrada.";
        }
    }
}
