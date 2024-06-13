<?php

namespace App\Dto;

use App\Service\Payment\PaymentProcessorFabric;
use Symfony\Component\Validator\Constraints\Choice;

final class PurchaseDto extends BaseDto
{
    #[Choice(choices: PaymentProcessorFabric::LIST)]
    public string $paymentProcessor;
}
