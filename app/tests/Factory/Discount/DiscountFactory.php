<?php

namespace App\Tests\Factory\Discount;

use App\Entity\Discount\Discount;
use App\Entity\Discount\DiscountType;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Discount>
 */
final class DiscountFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Discount::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'amount' => (string) self::faker()->randomFloat(2, 10, 90),
            'code' => sprintf(
                '%s%d',
                self::faker()->toUpper(self::faker()->lexify('?')),
                self::faker()->numerify('##')
            ),
            'discountType' => self::faker()->randomElement(DiscountType::LIST),
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
