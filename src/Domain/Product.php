<?php

namespace App\Domain;

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly string $imageUrl,
        public readonly bool $isFeatured
    ) {}

    public function getFormattedPrice(): string
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public static function fromArray(array $data): self
    {
        $imgSrc = 'assets/img/ui/sem-imagem.webp';

        if (!empty($data['image_data']) && !empty($data['image_mime'])) {
            $base64 = base64_encode($data['image_data']);
            $mime = $data['image_mime'];
            $imgSrc = "data:$mime;base64,$base64";
        }

        return new self(
            id: (int)$data['id'],
            name: $data['name'],
            description: $data['description'],
            price: (float)$data['price'],
            imageUrl: $imgSrc,
            isFeatured: (bool)($data['is_featured'] ?? false)
        );
    }
}
