<?php

declare(strict_types=1);

namespace App\Service\Tax;

use App\Entity\Tax\TaxRepository;

class TaxService
{
    public function __construct(private TaxRepository $taxRepository)
    {
    }

    private function getCountryCodeFromTaxNumber(string $taxNumber): string
    {
        return substr($taxNumber, 0, 2);
    }

    /**
     * @param numeric-string $price
     * @return numeric-string
     */
    public function calculateTaxAmount(string $price, string $taxNumber): string
    {
        $tax = $this->taxRepository->getRateByCountryCode(
            $this->getCountryCodeFromTaxNumber($taxNumber)
        );

        return bcmul($price, bcdiv($tax, '100', 6), 8);
    }
}
