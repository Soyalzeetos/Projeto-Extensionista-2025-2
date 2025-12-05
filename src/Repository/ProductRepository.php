<?php

namespace App\Repository;

use PDO;
use App\Domain\Product;

class ProductRepository
{

    public function __construct(private PDO $pdo) {}

    /**
     * @return Product[]
     */
    public function findFeatured(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE destaque = 1 LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $this->hydrateList($stmt->fetchAll());
    }

    /**
     * @return Product[]
     */
    public function findAllRegular(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM produtos WHERE destaque = 0");
        return $this->hydrateList($stmt->fetchAll());
    }

    private function hydrateList(array $rows): array
    {
        return array_map(fn($row) => Product::fromArray($row), $rows);
    }

    public function findById(int $id): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch();
        return $data ? Product::fromArray($data) : null;
    }
}
