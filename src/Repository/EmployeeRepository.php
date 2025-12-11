<?php

namespace App\Repository;

use PDO;

class EmployeeRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        $sql = "SELECT u.id, u.name, u.email, u.active, r.name as role_name, e.role_id, e.registration_number
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

    public function updateEmployee(int $userId, string $name, string $email, int $roleId, ?string $password = null): bool
    {
        try {
            $this->pdo->beginTransaction();

            $sqlUser = "UPDATE users SET name = :name, email = :email WHERE id = :id";
            $paramsUser = [':name' => $name, ':email' => $email, ':id' => $userId];

            if ($password) {
                $sqlUser = "UPDATE users SET name = :name, email = :email, password_hash = :hash WHERE id = :id";
                $paramsUser[':hash'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $this->pdo->prepare($sqlUser)->execute($paramsUser);

            $sqlEmp = "UPDATE employees SET role_id = :role_id WHERE user_id = :user_id";
            $this->pdo->prepare($sqlEmp)->execute([':role_id' => $roleId, ':user_id' => $userId]);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function toggleStatus(int $userId): bool
    {
        $sql = "UPDATE users SET active = NOT active WHERE id = :id";
        return $this->pdo->prepare($sql)->execute([':id' => $userId]);
    }

    public function delete(int $userId): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        return $this->pdo->prepare($sql)->execute([':id' => $userId]);
    }
}
