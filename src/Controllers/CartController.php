<?php

namespace App\Controllers;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;

class CartController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
    ) {}

    public function index(): void
    {
        require __DIR__ . '/../../views/cart/index.php';
    }

    public function add(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /');
            return;
        }

        $product = $this->productRepository->findById($id);

        if (!$product) {
            header('Location: /?error=product_not_found');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->priceCash,
                'image' => $product->imageUrl,
                'quantity' => 1
            ];
        }

        header('Location: /');
    }
}
