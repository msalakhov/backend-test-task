<?php

namespace App\Tests\Factory\Tax;

use App\Entity\Tax\Tax;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Tax>
 */
final class TaxFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Tax::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'countryCode' => self::faker()->unique()->randomElement(['DE', 'IT', 'GR', 'FR']),
            'mask' => self::faker()->text(),
            'rate' => (string) self::faker()->randomFloat(2, 10, 30),
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
