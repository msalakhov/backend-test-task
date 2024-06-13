<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;

class BaseDto
{
    #[Positive]
    public int $product;
    #[Regex('/^[A-Z]{2,4}\d{9,11}$/')]
    public string $taxNumber;
    #[Regex('/^[A-Z]\d{2}$/')]
    public ?string $couponCode = null;
}
