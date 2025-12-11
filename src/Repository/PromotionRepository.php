<?php

namespace App\Repository;

use PDO;

class PromotionRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        $sql = "SELECT
                    p.*,
                    COUNT(pp.product_id) as product_count,
                    GROUP_CONCAT(pp.product_id) as product_ids
                FROM promotions p
                LEFT JOIN product_promotions pp ON p.id = pp.promotion_id
                GROUP BY p.id
                ORDER BY p.created_at DESC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM promotions WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $promotion = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($promotion) {
            $stmtProds = $this->pdo->prepare("SELECT product_id FROM product_promotions WHERE promotion_id = :id");
            $stmtProds->execute([':id' => $id]);
            $promotion['products'] = $stmtProds->fetchAll(PDO::FETCH_COLUMN);
        }

        return $promotion ?: null;
    }

    public function create(string $name, float $discount, string $start, string $end, array $productIds = []): bool
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("INSERT INTO promotions (name, discount_percentage, start_date, end_date, active) VALUES (?, ?, ?, ?, 1)");
            $stmt->execute([$name, $discount, $start, $end]);
            $this->syncProducts($this->pdo->lastInsertId(), $productIds);
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function update(int $id, string $name, float $discount, string $start, string $end, array $productIds): bool
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("UPDATE promotions SET name = ?, discount_percentage = ?, start_date = ?, end_date = ? WHERE id = ?");
            $stmt->execute([$name, $discount, $start, $end, $id]);
            $this->syncProducts($id, $productIds);
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function toggleStatus(int $id): bool
    {
        return $this->pdo->prepare("UPDATE promotions SET active = NOT active WHERE id = :id")->execute([':id' => $id]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare("DELETE FROM promotions WHERE id = :id")->execute([':id' => $id]);
    }

    private function syncProducts(int $promoId, array $productIds): void
    {
        $this->pdo->prepare("DELETE FROM product_promotions WHERE promotion_id = ?")->execute([$promoId]);
        if (!empty($productIds)) {
            $stmt = $this->pdo->prepare("INSERT INTO product_promotions (promotion_id, product_id) VALUES (?, ?)");
            foreach ($productIds as $prodId) {
                $stmt->execute([$promoId, $prodId]);
            }
        }
    }
}
