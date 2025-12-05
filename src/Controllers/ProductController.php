<?php

namespace App\Controllers;

use App\Repository\ProductRepository;

class ProductController
{
    public function __construct(private ProductRepository $repository) {}

    public function show(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /');
            return;
        }

        $produto = $this->repository->findById($id);

        if (!$produto) {
            echo "Produto não encontrado.";
            return;
        }

        require __DIR__ . '/../../views/produto-detalhe.php';
    }
}
