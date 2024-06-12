<?php

declare(strict_types=1);

namespace App\Service\Price;

use App\Entity\Product\ProductRepository;
use App\Service\Discount\DiscountService;
use App\Service\Tax\TaxService;

use function bcadd;

class PriceCalculatorService
{
    public function __construct(
        private DiscountService $discountService,
        private ProductRepository $productRepository,
        private TaxService $taxService
    ) {
    }

    /**
     * @return numeric-string
     */
    public function calculatePrice(int $product, string $taxNumber, ?string $couponCode): string
    {
        $price = $this->productRepository->getById($product)->getPrice();

        $discountAmount = '0';
        if ($couponCode !== null) {
            $discountAmount = $this->discountService->calculateDiscount($price, $couponCode);
        }

        $priceWithDiscount = bcsub($price, $discountAmount, 2);

        $taxAmount = $this->taxService->calculateTaxAmount($priceWithDiscount, $taxNumber);

        return bcadd($priceWithDiscount, $taxAmount, 2);
    }
}
