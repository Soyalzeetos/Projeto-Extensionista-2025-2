<?php

namespace App\Repository;

use PDO;
use App\Domain\Category;

class CategoryRepository
{
    public function __construct(private PDO $pdo) {}

    /**
     * @return Category[]
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => Category::fromArray($row), $rows);
    }
}
