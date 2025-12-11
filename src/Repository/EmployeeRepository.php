<?php

namespace App\Repository;

use PDO;
use App\Domain\User;

class EmployeeRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        $sql = "SELECT u.id, u.name, u.email, r.name as role_name, e.registration_number
                FROM users u
                JOIN employees e ON u.id = e.user_id
                JOIN roles r ON e.role_id = r.id
                ORDER BY u.name";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRoles(): array
    {
        return $this->pdo->query("SELECT * FROM roles ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEmployee(string $name, string $email, string $password, int $roleId): bool
    {
        try {
            $this->pdo->beginTransaction();

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmtUser = $this->pdo->prepare("INSERT INTO users (name, email, password_hash, active, created_at) VALUES (?, ?, ?, 1, NOW())");
            $stmtUser->execute([$name, $email, $hash]);
            $userId = $this->pdo->lastInsertId();

            $regNum = 'EMP-' . str_pad($userId, 4, '0', STR_PAD_LEFT);
            $stmtEmp = $this->pdo->prepare("INSERT INTO employees (user_id, role_id, registration_number, hire_date) VALUES (?, ?, ?, NOW())");
            $stmtEmp->execute([$userId, $roleId, $regNum]);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
