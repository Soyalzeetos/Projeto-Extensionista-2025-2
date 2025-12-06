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
        $featured = $this->productRepository->findFeatured();
        $products = $this->productRepository->findAllRegular();
        $categories = $this->categoryRepository->findAll();

        require __DIR__ . '/../../views/home/index.php';
    }
}
