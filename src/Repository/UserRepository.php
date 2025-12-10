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

    public function create(User $user, string $token): bool
    {
        $sql = "INSERT INTO users (name, email, phone, password_hash, verification_token, active)
                VALUES (:name, :email, :phone, :password_hash, :token, 1)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':name' => $user->name,
            ':email' => $user->email,
            ':phone' => $user->phone,
            ':password_hash' => $user->passwordHash,
            ':token' => $token,
        ]);
    }

    public function verifyEmail(string $token): bool
    {
        $sql = "SELECT id FROM users WHERE verification_token = :token LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':token' => $token]);

        if (!$stmt->fetch()) {
            return false;
        }

        $updateSql = "UPDATE users
                      SET email_verified_at = NOW(), verification_token = NULL
                      WHERE verification_token = :token";

        $updateStmt = $this->pdo->prepare($updateSql);
        return $updateStmt->execute([':token' => $token]);
    }

    public function storePasswordResetToken(string $email, string $token, string $expiresAt): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expiresAt)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':email' => $email,
            ':token' => $token,
            ':expiresAt' => $expiresAt
        ]);
    }

    public function findResetToken(string $token): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM password_resets WHERE token = :token LIMIT 1");
        $stmt->execute([':token' => $token]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function updatePassword(string $email, string $passwordHash): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = :hash WHERE email = :email");
        return $stmt->execute([
            ':hash' => $passwordHash,
            ':email' => $email
        ]);
    }

    public function deleteResetToken(string $token): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE token = :token");
        $stmt->execute([':token' => $token]);
    }
}
