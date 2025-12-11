<?php

namespace App\Repository;

use PDO;
use Exception;

class OrderRepository
{
    public function __construct(private PDO $pdo) {}

    public function createOrder(int $userId, array $cartItems, float $totalCash, float $totalInstallments): int
    {
        try {
            $this->pdo->beginTransaction();

            $sqlOrder = "INSERT INTO orders (user_id, total_amount, total_amount_installments, status, payment_method, created_at)
                         VALUES (:user_id, :total_cash, :total_installments, 'pending', 'manual_contact', NOW())";

            $stmt = $this->pdo->prepare($sqlOrder);
            $stmt->execute([
                ':user_id' => $userId,
                ':total_cash' => $totalCash,
                ':total_installments' => $totalInstallments
            ]);

            $orderId = (int)$this->pdo->lastInsertId();

            $sqlItem = "INSERT INTO order_items
                        (order_id, product_id, quantity, unit_price, unit_price_installments, subtotal, subtotal_installments)
                        VALUES
                        (:order_id, :product_id, :qty, :price_cash, :price_inst, :sub_cash, :sub_inst)";

            $stmtItem = $this->pdo->prepare($sqlItem);

            foreach ($cartItems as $item) {
                $priceCash = (float)$item['price'];
                $priceInst = (float)$item['price_installments'];

                $subCash = $priceCash * $item['quantity'];
                $subInst = $priceInst * $item['quantity'];

                $stmtItem->execute([
                    ':order_id'   => $orderId,
                    ':product_id' => $item['id'],
                    ':qty'        => $item['quantity'],
                    ':price_cash' => $priceCash,
                    ':price_inst' => $priceInst,
                    ':sub_cash'   => $subCash,
                    ':sub_inst'   => $subInst
                ]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    public function findAll(): array
    {
        $sql = "SELECT
                o.*,
                u.name as client_name,
                u.email as client_email,
                u.phone as client_phone
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC";

        $stmt = $this->pdo->query($sql);
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $order['items'] = $this->findItemsByOrderId($order['id']);
        }

        return $orders;
    }

    public function findItemsByOrderId(int $orderId): array
    {
        $sql = "SELECT
                    oi.*,
                    p.name as product_name,
                    p.image_data,
                    p.image_mime
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as &$item) {
            if (!empty($item['image_data'])) {
                $item['image'] = "data:{$item['image_mime']};base64,{$item['image_data']}";
            } else {
                $item['image'] = '/assets/img/ui/sem-imagem.webp';
            }
        }

        return $items;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function findByUserId(int $userId): array
    {
        $sql = "SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $order['items'] = $this->findItemsByOrderId($order['id']);
        }

        return $orders;
    }
}
