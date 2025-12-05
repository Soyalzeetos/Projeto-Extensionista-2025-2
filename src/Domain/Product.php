<?php
namespace App\Domain;

class Product {
    public function __construct(
        public readonly int $id,
        public readonly string $nome,
        public readonly string $descricao,
        public readonly float $preco,
        public readonly string $imagemUrl,
        public readonly bool $isDestaque
    ) {}

    public function getFormattedPrice(): string {
        return 'R$ ' . number_format($this->preco, 2, ',', '.');
    }

    public static function fromArray(array $data): self {
        return new self(
            id: (int)$data['id'],
            nome: $data['nome'],
            descricao: $data['descricao'],
            preco: (float)$data['preco'],
            imagemUrl: $data['imagem_url'],
            isDestaque: (bool)$data['destaque']
        );
    }
}
