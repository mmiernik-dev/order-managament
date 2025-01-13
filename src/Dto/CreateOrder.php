<?php
declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOrder implements DtoInterface
{
    #[Assert\NotBlank]
    public string $username;

    #[Assert\NotBlank]
    public array $products;
}