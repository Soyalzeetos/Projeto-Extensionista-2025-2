<?php

namespace App\Controllers;

use App\Repository\ProductRepository;

class HomeController
{
    public function __construct(private ProductRepository $repository) {}

    public function index(): void
    {
        $destaques = $this->repository->findFeatured();
        $produtos = $this->repository->findAllRegular();

        require __DIR__ . '/../../views/Home.php';
    }
}
