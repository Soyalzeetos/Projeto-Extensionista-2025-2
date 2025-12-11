<?php

namespace App\Controllers;

use App\Repository\CartRepository;
use App\Config\Database;

class CartController
{
    private CartRepository $cartRepo;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->cartRepo = new CartRepository($pdo);
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cartId = $this->cartRepo->getOrCreateCart(session_id(), $_SESSION['user_id'] ?? null);
        $cartItems = $this->cartRepo->getCartItems($cartId);

        $totalCash = 0;
        $totalInstallments = 0;
        foreach ($cartItems as $item) {
            $totalCash += $item['price'] * $item['quantity'];
            $totalInstallments += $item['price_installments'] * $item['quantity'];
        }

        require __DIR__ . '/../../views/cart/index.php';
    }

    public function add(): void
    {
        $this->handleCartAction('add');
    }

    public function update(): void
    {
        $this->handleCartAction('update');
    }

    public function remove(): void
    {
        $this->handleCartAction('remove');
    }

    private function handleCartAction(string $action): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $productId = $input['id'] ?? $input['product_id'] ?? null;
        $qty = (int)($input['qty'] ?? 1);

        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            return;
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        try {
            $cartId = $this->cartRepo->getOrCreateCart(session_id(), $_SESSION['user_id'] ?? null);

            if ($action === 'add') {
                $this->cartRepo->addItem($cartId, $productId, $qty);
            } elseif ($action === 'update') {
                $this->cartRepo->updateQuantity($cartId, $productId, $qty);
            } elseif ($action === 'remove') {
                $this->cartRepo->removeItem($cartId, $productId);
            }

            $items = $this->cartRepo->getCartItems($cartId);

            $cartItems = $items;
            ob_start();
            require __DIR__ . '/../../views/partials/cart_dropdown_content.php';
            $html = ob_get_clean();

            $totalQty = 0;
            $totalCash = 0;
            $totalInstallments = 0;
            $itemTotalCash = '';
            $itemTotalInstallments = '';

            foreach ($items as $i) {
                $qtyItem = $i['quantity'];
                $subCash = $i['price'] * $qtyItem;
                $subInst = $i['price_installments'] * $qtyItem;

                $totalQty += $qtyItem;
                $totalCash += $subCash;
                $totalInstallments += $subInst;

                if ($i['id'] == $productId) {
                    $itemTotalCash = 'R$ ' . number_format($subCash, 2, ',', '.');
                    $itemTotalInstallments = 'ou R$ ' . number_format($subInst, 2, ',', '.');
                }
            }

            echo json_encode([
                'success' => true,
                'cartHtml' => $html,
                'totalQty' => $totalQty,
                'summaryTotalCash' => 'R$ ' . number_format($totalCash, 2, ',', '.'),
                'summaryTotalInstallments' => 'R$ ' . number_format($totalInstallments, 2, ',', '.'),
                'itemTotalCash' => $itemTotalCash,
                'itemTotalInstallments' => $itemTotalInstallments
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
