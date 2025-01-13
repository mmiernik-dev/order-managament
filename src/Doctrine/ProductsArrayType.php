<?php

declare(strict_types=1);

namespace App\Doctrine;

use App\Entity\ValueObject\Product;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ProductsArrayType extends Type
{
    public const NAME = 'product_array';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('json');
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $data = json_decode($value, true);

        return array_map(fn ($item) => Product::fromArray($item), $data);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $data = array_map(fn (Product $settings) => $settings->toArray(), $value);

        return json_encode($data);
    }

    public function getName()
    {
        return self::NAME;
    }
}