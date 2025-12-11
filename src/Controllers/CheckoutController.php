<?php

namespace App\Controllers;

use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Config\Database;

class CheckoutController
{
    private CartRepository $cartRepo;
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->cartRepo = new CartRepository($pdo);
        $this->orderRepo = new OrderRepository($pdo);
    }

    private function ensureAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?error=login_required');
            exit;
        }
    }

    private function calculateTotals(array $cartItems): array
    {
        $totalCash = 0;
        $totalInstallments = 0;

        foreach ($cartItems as $item) {
            $totalCash += $item['price'] * $item['quantity'];
            $totalInstallments += $item['price_installments'] * $item['quantity'];
        }

        return [$totalCash, $totalInstallments];
    }

    public function index(): void
    {
        $this->ensureAuth();

        $cartId = $this->cartRepo->getOrCreateCart(session_id(), $_SESSION['user_id']);
        $cartItems = $this->cartRepo->getCartItems($cartId);

        if (empty($cartItems)) {
            header('Location: /carrinho');
            return;
        }

        [$totalCash, $totalInstallments] = $this->calculateTotals($cartItems);

        require __DIR__ . '/../../views/checkout/index.php';
    }

    public function process(): void
    {
        $this->ensureAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /checkout');
            return;
        }

        $cartId = $this->cartRepo->getOrCreateCart(session_id(), $_SESSION['user_id']);
        $cartItems = $this->cartRepo->getCartItems($cartId);

        if (empty($cartItems)) {
            header('Location: /carrinho');
            return;
        }

        [$totalCash, $totalInstallments] = $this->calculateTotals($cartItems);

        try {
            $orderId = $this->orderRepo->createOrder(
                $_SESSION['user_id'],
                $cartItems,
                $totalCash,
                $totalInstallments
            );

            $this->cartRepo->clearCart($cartId);

            header("Location: /checkout/success?order_id=$orderId");
        } catch (\Exception $e) {
            // Logger::error("Erro ao criar pedido: " . $e->getMessage());
            header('Location: /checkout?error=order_failed');
        }
    }

    public function success(): void
    {
        $this->ensureAuth();
        $orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);


        require __DIR__ . '/../../views/checkout/success.php';
    }
}
