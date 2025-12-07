<?php

namespace App\Domain;

class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $passwordHash,
        public readonly ?string $role = null,
        public readonly array $permissions = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'],
            email: $data['email'],
            passwordHash: $data['password_hash'],
            role: $data['role_slug'] ?? null,
            permissions: $data['permissions'] ?? []
        );
    }
}
