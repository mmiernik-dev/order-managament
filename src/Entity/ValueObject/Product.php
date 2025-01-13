<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

class Product
{
    private int $id;
    private float $price;
    private string $title;

    public function __construct(int $id, string $title, float $price)
    {
        $this->id = $id;
        $this->price = $price;
        $this->title = $title;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['title'],
            $data['price']
        );
    }
}