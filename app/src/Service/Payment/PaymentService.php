<?php

namespace App\Service\Payment;

final class PaymentService
{
    public function __construct(private PaymentProcessorFabric $paymentProcessorFabric)
    {
    }

    /** @param numeric-string $amount */
    public function pay(string $amount, string $paymentProcessor): bool
    {
        return $this
            ->paymentProcessorFabric
            ->getPaymentProcessor($paymentProcessor)
            ->pay($amount);
    }
}
