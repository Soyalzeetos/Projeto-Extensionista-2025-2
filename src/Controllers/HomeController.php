<?php

namespace App\Controllers;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;

class HomeController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
    ) {}

    public function index(): void
    {
        $categoryId = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);

        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = $this->categoryRepository->findById($categoryId);
        }

        $featured = $this->productRepository->findFeatured();

        $products = $this->productRepository->findAllRegular($categoryId);

        $categories = $this->categoryRepository->findAll();

        require __DIR__ . '/../../views/home/index.php';
    }
}
