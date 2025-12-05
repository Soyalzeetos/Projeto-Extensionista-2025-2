<?php

namespace App\Domain;

class Category
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'],
            description: $data['description'] ?? null
        );
    }
}
