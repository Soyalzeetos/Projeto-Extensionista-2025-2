<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\AuthController;

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $router = new Router();
    $router->get('/', [HomeController::class, 'index']);
    $router->post('/register', [AuthController::class, 'register']);
    $router->get('/verify-email', [AuthController::class, 'verifyEmail']);
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/logout', [AuthController::class, 'logout']);
    $router->post('/forgot-password', [AuthController::class, 'forgotPassword']);
    $router->get('/reset-password', [AuthController::class, 'showResetForm']);
    $router->post('/reset-password', [AuthController::class, 'resetPassword']);
    $router->get('/produto', [ProductController::class, 'show']);
    $router->get('/carrinho', [CartController::class, 'index']);
    $router->get('/carrinho/adicionar', [CartController::class, 'add']);

    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    $router->dispatch($uri, $method);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "Erro interno.";
}
