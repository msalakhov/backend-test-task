<?php

declare(strict_types=1);

namespace App\Service\Discount;

use App\Entity\Discount\DiscountRepository;
use App\Entity\Discount\DiscountType;
use Exception;

use function bcmul;

class DiscountService
{
    public function __construct(private DiscountRepository $discountRepository)
    {
    }

    /**
     * @param numeric-string $price
     * @return numeric-string
     */
    public function calculateDiscount(string $price, string $couponCode): string
    {
        $discount = $this->discountRepository->getByCouponCode($couponCode);
        $discountType = $discount->getDiscountType();
        $discountAmount = $discount->getAmount();

        if ($discountType === DiscountType::PERCENT) {
            return bcmul($price, bcdiv($discountAmount, '100', 3), 2);
        }

        if ($discountType === DiscountType::FIXED) {
            return $discountAmount;
        }

        throw new Exception('Unknown discount type');
    }
}
