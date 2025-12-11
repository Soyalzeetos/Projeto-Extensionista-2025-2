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
    $router->get('/admin', [App\Controllers\AdminController::class, 'dashboard']);
    $router->get('/admin/employees', [\App\Controllers\AdminController::class, 'listEmployees']);
    $router->post('/admin/employees/store', [\App\Controllers\AdminController::class, 'storeEmployee']);
    $router->post('/admin/employees/update', [\App\Controllers\AdminController::class, 'updateEmployee']);
    $router->post('/admin/employees/toggle', [\App\Controllers\AdminController::class, 'toggleEmployeeStatus']);
    $router->post('/admin/employees/delete', [\App\Controllers\AdminController::class, 'deleteEmployee']);
    $router->get('/admin/categories', [App\Controllers\AdminController::class, 'listCategories']);
    $router->post('/admin/categories/store', [App\Controllers\AdminController::class, 'storeCategory']);
    $router->post('/admin/categories/update', [App\Controllers\AdminController::class, 'updateCategory']);
    $router->post('/admin/categories/delete', [App\Controllers\AdminController::class, 'deleteCategory']);
    $router->get('/admin/products', [App\Controllers\AdminController::class, 'listProducts']);
    $router->post('/admin/products/create', [App\Controllers\AdminController::class, 'storeProduct']);
    $router->post('/admin/products/update', [App\Controllers\AdminController::class, 'updateProduct']);
    $router->post('/admin/products/delete', [App\Controllers\AdminController::class, 'deleteProduct']);
    $router->get('/admin/promotions', [App\Controllers\AdminController::class, 'listPromotions']);
    $router->post('/admin/promotions/create', [App\Controllers\AdminController::class, 'storePromotion']);
    $router->post('/admin/promotions/update', [App\Controllers\AdminController::class, 'updatePromotion']);
    $router->post('/admin/promotions/delete', [App\Controllers\AdminController::class, 'deletePromotion']);
    $router->post('/admin/promotions/toggle', [App\Controllers\AdminController::class, 'togglePromotionStatus']);
    $router->get('/admin/dashboard', [App\Controllers\AdminController::class, 'dashboard']);

    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    $router->dispatch($uri, $method);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "Erro interno.";
}
