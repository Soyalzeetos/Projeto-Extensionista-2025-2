<?php

namespace App\Repository;

use PDO;

class PromotionRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        return $this->pdo->query("SELECT * FROM promotions ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name, float $discount, string $start, string $end): bool
    {
        $sql = "INSERT INTO promotions (name, discount_percentage, start_date, end_date, active) VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $discount, $start, $end]);
    }

    public function addProductToPromotion(int $promotionId, int $productId): bool
    {
        $sql = "INSERT INTO product_promotions (promotion_id, product_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$promotionId, $productId]);
    }
}
