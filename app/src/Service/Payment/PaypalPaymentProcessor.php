<?php

declare(strict_types=1);

namespace App\Service\Payment;

/**
 * @final
 */
class PaypalPaymentProcessor implements PaymentProcessor
{
    public function __construct(private \Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor $paymentProcessor)
    {
    }

    /**
     * @inheritDoc
     */
    public function pay(string $amount): bool
    {
        $this
            ->paymentProcessor
            ->pay(
                (int) bcmul($amount, '100')
            );

        return true;
    }
}
