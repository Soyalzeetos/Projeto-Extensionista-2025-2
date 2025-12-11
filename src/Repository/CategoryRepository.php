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

    public function create(string $name, string $description): bool
    {
        $sql = "INSERT INTO categories (name, description) VALUES (:name, :desc)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':desc' => $description
        ]);
    }

    public function update(int $id, string $name, string $description): bool
    {
        $sql = "UPDATE categories SET name = :name, description = :desc WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':desc' => $description
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
