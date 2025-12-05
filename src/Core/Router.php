<?php

namespace App\Core;

use App\Config\Database;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;

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

            $pdo = Database::getConnection();
            $productRepo = new ProductRepository($pdo);
            $categoryRepo = new CategoryRepository($pdo);
            $controller = new $controllerClass($productRepo, $categoryRepo);
            $controller->$action();
        } else {
            http_response_code(404);
            echo "Page not found.";
        }
    }
}
