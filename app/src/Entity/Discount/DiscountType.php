<?php

namespace App\Entity\Discount;

class DiscountType
{
    public const FIXED = 'fixed';
    public const PERCENT = 'percent';
    public const LIST = [
        self::FIXED,
        self::PERCENT,
    ];
}
