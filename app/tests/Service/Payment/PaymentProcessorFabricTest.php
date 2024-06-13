<?php

declare(strict_types=1);

namespace App\Tests\Service\Payment;

use App\Service\Payment\PaymentProcessorFabric;
use App\Service\Payment\PaypalPaymentProcessor;
use App\Service\Payment\StripePaymentProcessor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaymentProcessorFabricTest extends KernelTestCase
{
    /**
     * @return mixed[]
     */
    public static function getPaymentProcessorDataProvider(): array
    {
        return [
            [
                'processor' => PaymentProcessorFabric::PAYPAL,
                'expectedProcessorClass' => PaypalPaymentProcessor::class,
            ],
            [
                'processor' => PaymentProcessorFabric::STRIPE,
                'expectedProcessorClass' => StripePaymentProcessor::class,
            ]
        ];
    }

    /**
     * @dataProvider getPaymentProcessorDataProvider
     */
    public function testGetPaymentProcessor(string $processor, string $expectedProcessorClass): void
    {
        $paymentProcessor = (new PaymentProcessorFabric())->getPaymentProcessor($processor);

        self::assertSame($expectedProcessorClass, $paymentProcessor::class);
    }
}
