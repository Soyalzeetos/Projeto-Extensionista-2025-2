<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\CartController; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $router = new Router();
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/produto', [ProductController::class, 'show']);
    $router->get('/carrinho', [CartController::class, 'index']);

    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    $router->dispatch($uri, $method);

} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo "<h1>Ops!</h1>";
    echo "<p>Ocorreu um erro interno no servidor.</p>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
