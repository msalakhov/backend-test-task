<?php

namespace App\Service\Payment;

interface PaymentProcessor
{
    /** @param numeric-string $amount */
    public function pay(string $amount): bool;
}
