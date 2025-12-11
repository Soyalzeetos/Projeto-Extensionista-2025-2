<?php

namespace App\Repository;

use PDO;
use App\Domain\Product;

class CartRepository
{
    public function __construct(private PDO $pdo) {}

    public function getOrCreateCart(string $sessionToken, ?int $userId = null): int
    {
        $sql = "SELECT id FROM carts WHERE session_token = :token";
        $params = [':token' => $sessionToken];

        if ($userId) {
            $sql .= " OR user_id = :uid";
            $params[':uid'] = $userId;
        }

        $stmt = $this->pdo->prepare($sql . " ORDER BY id DESC LIMIT 1");
        $stmt->execute($params);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            if ($userId && !empty($cart['user_id']) === false) {
                $this->pdo->prepare("UPDATE carts SET user_id = ? WHERE id = ?")
                    ->execute([$userId, $cart['id']]);
            }
            return (int)$cart['id'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO carts (session_token, user_id) VALUES (?, ?)");
        $stmt->execute([$sessionToken, $userId]);
        return (int)$this->pdo->lastInsertId();
    }

    public function addItem(int $cartId, int $productId, int $quantity = 1): void
    {
        $stmt = $this->pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cartId, $productId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            $newQty = $item['quantity'] + $quantity;
            $this->pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?")->execute([$newQty, $item['id']]);
        } else {
            $this->pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)")
                ->execute([$cartId, $productId, $quantity]);
        }
    }



    public function removeItem(int $cartId, int $productId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
        return $stmt->execute([$cartId, $productId]);
    }

    public function updateQuantity(int $cartId, int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->removeItem($cartId, $productId);
        }
        $stmt = $this->pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $cartId, $productId]);
    }

    public function getCartItems(int $cartId): array
    {
        $sql = "SELECT p.id, p.name, p.price_cash as price, p.price_installments, p.image_data, p.image_mime, ci.quantity
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.cart_id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cartId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['image'] = (!empty($row['image_data']))
                ? "data:{$row['image_mime']};base64,{$row['image_data']}"
                : '/assets/img/ui/sem-imagem.webp';
        }
        return $rows;
    }
}
