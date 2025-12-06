<?php

namespace App\Domain;

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly float $priceCash,
        public readonly float $priceInstallments,
        public readonly string $imageUrl,
        public readonly bool $isFeatured,
        public readonly float $discountPercentage = 0.0
    ) {}

    public function getFormattedCashPrice(): string
    {
        $finalPrice = $this->priceCash * (1 - ($this->discountPercentage / 100));
        return 'R$ ' . number_format($finalPrice, 2, ',', '.');
    }

    public function getFormattedInstallmentPrice(): string
    {
        $finalPrice = $this->priceInstallments * (1 - ($this->discountPercentage / 100));
        return 'R$ ' . number_format($finalPrice, 2, ',', '.');
    }

    public function getDiscountLabel(): ?string
    {
        if ($this->discountPercentage > 0) {
            return '-' . number_format($this->discountPercentage, 0) . '%';
        }
        return null;
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
            description: $data['description'] ?? '',
            priceCash: (float)$data['price_cash'],
            priceInstallments: (float)$data['price_installments'],
            imageUrl: $imgSrc,
            isFeatured: (bool)($data['is_featured'] ?? false),
            discountPercentage: (float)($data['discount_percentage'] ?? 0)
        );
    }
}
