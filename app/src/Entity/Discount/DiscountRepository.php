<?php

namespace App\Entity\Discount;

use Doctrine\ORM\EntityNotFoundException;

interface DiscountRepository
{
    /**
     * @throws EntityNotFoundException
     */
    public function getByCouponCode(string $couponCode): Discount;
}
