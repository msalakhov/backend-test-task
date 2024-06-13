<?php

namespace App\Service\Payment;

use Exception;

class PaymentProcessorFabric
{
    public const PAYPAL = 'paypal';
    public const STRIPE = 'stripe';
    public const LIST = [
        self::PAYPAL,
        self::STRIPE,
    ];

    public function getPaymentProcessor(string $processor): PaymentProcessor
    {
        switch ($processor) {
            case self::PAYPAL:
                return new PaypalPaymentProcessor(new \Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor());
            case self::STRIPE:
                return new StripePaymentProcessor(new \Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor());
            default:
                throw new Exception('Unsupported payment processor: ' . $processor);
        }
    }
}
