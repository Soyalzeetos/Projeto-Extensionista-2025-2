<?php

namespace App\Controllers;

use App\Repository\OrderRepository;
use App\Config\Database;

class OrderController
{
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->orderRepo = new OrderRepository($pdo);
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /?error=login_required');
            return;
        }

        $userId = $_SESSION['user_id'];
        $orders = $this->orderRepo->findByUserId($userId);

        require __DIR__ . '/../../views/client/orders.php';
    }
}
