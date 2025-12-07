<?php

namespace App\Repository;

use PDO;
use App\Domain\User;

class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function findByEmail(string $email): ?User
    {
        $sql = "
            SELECT
                u.*,
                r.slug as role_slug
            FROM users u
            LEFT JOIN employees e ON u.id = e.user_id
            LEFT JOIN roles r ON e.role_id = r.id
            WHERE u.email = :email
            AND u.active = 1
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        $permissions = [];
        if (!empty($userData['role_slug'])) {
            $sqlPerms = "
                SELECT p.slug
                FROM permissions p
                INNER JOIN role_permissions rp ON p.id = rp.permission_id
                INNER JOIN roles r ON rp.role_id = r.id
                WHERE r.slug = :role_slug
            ";

            $stmtPerms = $this->pdo->prepare($sqlPerms);
            $stmtPerms->bindValue(':role_slug', $userData['role_slug']);
            $stmtPerms->execute();

            $permissions = $stmtPerms->fetchAll(PDO::FETCH_COLUMN);
        }

        $userData['permissions'] = $permissions;

        return User::fromArray($userData);
    }
}
