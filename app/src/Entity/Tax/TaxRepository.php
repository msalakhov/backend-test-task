<?php

declare(strict_types=1);

namespace App\Entity\Tax;

use Doctrine\ORM\EntityNotFoundException;

interface TaxRepository
{
    /**
     * @return numeric-string
     * @throws EntityNotFoundException
     */
    public function getRateByCountryCode(string $countryCode): string;
}
