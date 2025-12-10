<?php

namespace App\Core;

use App\Config\Database;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Controllers\AuthController;
use App\Core\Logger;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $uri, string $method): void
    {
        $startTime = microtime(true);

        $requestId = $this->generateRequestId();

        $path = parse_url($uri, PHP_URL_PATH);

        try {
            if (isset($this->routes[$method][$path])) {
                [$controllerClass, $action] = $this->routes[$method][$path];

                $pdo = Database::getConnection();

                if ($controllerClass === AuthController::class) {
                    $userRepo = new UserRepository($pdo);
                    $controller = new AuthController($userRepo);
                } else {
                    $productRepo = new ProductRepository($pdo);
                    $categoryRepo = new CategoryRepository($pdo);
                    $controller = new $controllerClass($productRepo, $categoryRepo);
                }

                $controller->$action();
            } else {
                http_response_code(404);
                echo "Página não encontrada.";
            }
        } catch (\Throwable $t) {
            http_response_code(500);
            Logger::error('Exception caught', [
                'request_id' => $requestId,
                'error' => $t->getMessage(),
                'file' => $t->getFile(),
                'line' => $t->getLine()
            ]);
            echo "Ocorreu um erro interno.";
        } finally {
            $endTime = microtime(true);
            $latencyMs = ($endTime - $startTime) * 1000;

            Logger::info('Request completed', [
                'http_method'   => $method,
                'http_path'     => $path,
                'http_status'   => http_response_code() ?: 200,
                'latency_ms'    => number_format($latencyMs, 6, '.', ''),
                'remote_ip'     => $_SERVER['REMOTE_ADDR'] ?? '-',
                'request_id'    => $requestId,
                'route_pattern' => $path,
                'user_agent'    => $_SERVER['HTTP_USER_AGENT'] ?? '-'
            ]);
        }
    }

    private function generateRequestId(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
