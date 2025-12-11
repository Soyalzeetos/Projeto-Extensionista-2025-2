<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\CheckoutController;
use App\Controllers\OrderController;

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
    $router->get('/carrinho', [CartController::class, 'index']);
    $router->post('/carrinho/adicionar', [CartController::class, 'add']);
    $router->post('/carrinho/atualizar', [CartController::class, 'update']);
    $router->post('/carrinho/remover', [CartController::class, 'remove']);
    $router->get('/checkout', [CheckoutController::class, 'index']);
    $router->post('/checkout/process', [CheckoutController::class, 'process']);
    $router->get('/checkout/success', [CheckoutController::class, 'success']);
    $router->get('/admin', [AdminController::class, 'dashboard']);
    $router->get('/admin/employees', [AdminController::class, 'listEmployees']);
    $router->post('/admin/employees/store', [AdminController::class, 'storeEmployee']);
    $router->post('/admin/employees/update', [AdminController::class, 'updateEmployee']);
    $router->post('/admin/employees/toggle', [AdminController::class, 'toggleEmployeeStatus']);
    $router->post('/admin/employees/delete', [AdminController::class, 'deleteEmployee']);
    $router->get('/admin/categories', [AdminController::class, 'listCategories']);
    $router->post('/admin/categories/store', [AdminController::class, 'storeCategory']);
    $router->post('/admin/categories/update', [AdminController::class, 'updateCategory']);
    $router->post('/admin/categories/delete', [AdminController::class, 'deleteCategory']);
    $router->get('/admin/products', [AdminController::class, 'listProducts']);
    $router->post('/admin/products/create', [AdminController::class, 'storeProduct']);
    $router->post('/admin/products/update', [AdminController::class, 'updateProduct']);
    $router->post('/admin/products/delete', [AdminController::class, 'deleteProduct']);
    $router->post('/admin/products/toggle', [AdminController::class, 'toggleProductStatus']);
    $router->get('/admin/promotions', [AdminController::class, 'listPromotions']);
    $router->post('/admin/promotions/create', [AdminController::class, 'storePromotion']);
    $router->post('/admin/promotions/update', [AdminController::class, 'updatePromotion']);
    $router->post('/admin/promotions/delete', [AdminController::class, 'deletePromotion']);
    $router->post('/admin/promotions/toggle', [AdminController::class, 'togglePromotionStatus']);
    $router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
    $router->get('/admin/orders', [\App\Controllers\AdminController::class, 'listOrders']);
    $router->post('/admin/orders/update-status', [\App\Controllers\AdminController::class, 'updateOrderStatus']);
    $router->get('/my-orders', [OrderController::class, 'index']);

    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    $router->dispatch($uri, $method);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "Erro interno.";
}
