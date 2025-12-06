<?php

namespace App\Controllers;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;

class ProductController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
    ) {}

    public function show(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /');
            return;
        }

        $product = $this->productRepository->findById($id);

        $categories = $this->categoryRepository->findAll();

        if (!$product) {
            echo "Product not found.";
            return;
        }

        require __DIR__ . '/../../views/products/datails.php';
    }
}
