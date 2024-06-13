<?php

namespace App\Tests\Factory\Product;

use App\Entity\Product\Product;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Product>
 */
final class ProductFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Product::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->text(),
            'price' => (string) self::faker()->randomFloat(2, 10, 1_000),
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
