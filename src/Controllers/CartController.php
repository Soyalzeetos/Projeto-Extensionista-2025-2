<?php

namespace App\Controllers;

class CartController
{
    public function __construct()
    {
        //TODO: Implementar lógica de inicialização, buscar no localstorage? Online?
    }

    public function index(): void
    {
        require __DIR__ . '/../../views/ShoppingCart.php';
    }
}
