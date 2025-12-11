<?php

namespace App\Repository;

use PDO;
use App\Domain\Product;

class ProductRepository
{
    public function __construct(private PDO $pdo) {}

    public function findFeatured(int $limit = 5): array
    {
        $sql = "
            SELECT
                p.id, p.name, p.description, p.price_cash, p.price_installments, p.image_data, p.image_mime,
                1 as is_featured,
                prom.discount_percentage
            FROM products p
            INNER JOIN product_promotions pp ON p.id = pp.product_id
            INNER JOIN promotions prom ON pp.promotion_id = prom.id
            WHERE prom.active = 1
            AND NOW() BETWEEN prom.start_date AND prom.end_date
            ORDER BY prom.end_date ASC
            LIMIT :limit
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $this->hydrateList($stmt->fetchAll());
    }

    public function findAllRegular(?int $categoryId = null): array
    {
        $sql = "
            SELECT
                p.id, p.name, p.description, p.price_cash, p.price_installments, p.image_data, p.image_mime,
                0 as is_featured,
                0 as discount_percentage
            FROM products p
            WHERE p.id NOT IN (
                SELECT pp.product_id
                FROM product_promotions pp
                INNER JOIN promotions prom ON pp.promotion_id = prom.id
                WHERE prom.active = 1
                AND NOW() BETWEEN prom.start_date AND prom.end_date
            )
        ";

        if ($categoryId) {
            $sql .= " AND p.category_id = :cat_id";
        }

        $sql .= " ORDER BY p.name ASC";

        $stmt = $this->pdo->prepare($sql);

        if ($categoryId) {
            $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $this->hydrateList($stmt->fetchAll());
    }

    public function findById(int $id): ?Product
    {
        $sql = "
            SELECT
                p.*,
                COALESCE(
                    (SELECT prom.discount_percentage
                     FROM product_promotions pp
                     JOIN promotions prom ON pp.promotion_id = prom.id
                     WHERE pp.product_id = p.id
                     AND prom.active = 1
                     AND NOW() BETWEEN prom.start_date AND prom.end_date
                     LIMIT 1
                    ), 0) as discount_percentage,

                (SELECT COUNT(*) FROM product_promotions pp
                 JOIN promotions prom ON pp.promotion_id = prom.id
                 WHERE pp.product_id = p.id AND prom.active = 1
                 AND NOW() BETWEEN prom.start_date AND prom.end_date
                ) > 0 as is_featured
            FROM products p
            WHERE p.id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch();
        return $data ? Product::fromArray($data) : null;
    }

    public function findAllToCart(): array
    {
        $sql = "
            SELECT
                p.id, p.name, p.price_installments, p.image_data, p.image_mime,
                0 as is_featured,
                0 as discount_percentage
            FROM products p
            WHERE p.id NOT IN (
                SELECT pp.product_id
                FROM product_promotions pp
                INNER JOIN promotions prom ON pp.promotion_id = prom.id
                WHERE prom.active = 1
                AND NOW() BETWEEN prom.start_date AND prom.end_date
            )
            ORDER BY p.name ASC
        ";

        $stmt = $this->pdo->query($sql);
        return $this->hydrateList($stmt->fetchAll());
    }

    private function hydrateList(array $rows): array
    {
        return array_map(fn($row) => Product::fromArray($row), $rows);
    }

    public function findAllForAdmin(): array
    {
        $sql = "SELECT
                    p.*,
                    0 as is_featured,
                    0 as discount_percentage
                FROM products p
                ORDER BY p.name ASC";

        $stmt = $this->pdo->query($sql);
        return $this->hydrateList($stmt->fetchAll());
    }

    public function create(Product $product, int $categoryId, ?string $imageData, ?string $imageMime): bool
    {
        $sql = "INSERT INTO products (name, description, price_cash, price_installments, category_id, stock_quantity, active, image_data, image_mime)
            VALUES (:name, :desc, :cash, :installments, :cat_id, :stock, 1, :img_data, :img_mime)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $product->name,
            ':desc' => $product->description,
            ':cash' => $product->priceCash,
            ':installments' => $product->priceInstallments,
            ':cat_id' => $categoryId,
            ':stock' => $product->stockQuantity,
            ':img_data' => $imageData,
            ':img_mime' => $imageMime
        ]);
    }

    public function update(int $id, string $name, string $description, float $priceCash, float $priceInst, int $categoryId, int $stock, ?string $imageData, ?string $imageMime): bool
    {
        $sql = "UPDATE products SET
                name = :name,
                description = :desc,
                price_cash = :cash,
                price_installments = :inst,
                category_id = :cat_id,
                stock_quantity = :stock";

        $params = [
            ':id' => $id,
            ':name' => $name,
            ':desc' => $description,
            ':cash' => $priceCash,
            ':inst' => $priceInst,
            ':cat_id' => $categoryId,
            ':stock' => $stock
        ];

        if ($imageData && $imageMime) {
            $sql .= ", image_data = :img_data, image_mime = :img_mime";
            $params[':img_data'] = $imageData;
            $params[':img_mime'] = $imageMime;
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function toggleStatus(int $id): bool
    {
        $sql = "UPDATE products SET active = NOT active WHERE id = :id";
        return $this->pdo->prepare($sql)->execute([':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function findByCategory(int $categoryId): array
    {
        $sql = "
            SELECT
                p.*,
                -- Verifica se é destaque/promoção para manter a consistência visual
                (SELECT COUNT(*) FROM product_promotions pp
                 JOIN promotions prom ON pp.promotion_id = prom.id
                 WHERE pp.product_id = p.id AND prom.active = 1
                 AND NOW() BETWEEN prom.start_date AND prom.end_date
                ) > 0 as is_featured,

                -- Busca a porcentagem de desconto se houver
                COALESCE(
                    (SELECT prom.discount_percentage
                     FROM product_promotions pp
                     JOIN promotions prom ON pp.promotion_id = prom.id
                     WHERE pp.product_id = p.id
                     AND prom.active = 1
                     AND NOW() BETWEEN prom.start_date AND prom.end_date
                     LIMIT 1
                    ), 0) as discount_percentage
            FROM products p
            WHERE p.category_id = :cat_id
            AND p.active = 1
            ORDER BY p.name ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cat_id' => $categoryId]);

        return $this->hydrateList($stmt->fetchAll());
    }
}
