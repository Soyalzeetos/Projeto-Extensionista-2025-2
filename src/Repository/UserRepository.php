<?php

namespace App\Repository;

use PDO;
use App\Domain\User;

class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $data = $stmt->fetch();
        return $data ? User::fromArray($data) : null;
    }
}
