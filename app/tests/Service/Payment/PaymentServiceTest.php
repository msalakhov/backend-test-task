<?php

declare(strict_types=1);

namespace App\Tests\Service\Payment;

use App\Service\Payment\PaymentProcessorFabric;
use App\Service\Payment\PaymentService;
use App\Service\Payment\PaypalPaymentProcessor;
use App\Service\Payment\StripePaymentProcessor;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaymentServiceTest extends KernelTestCase
{
    /** @var PaymentProcessorFabric | MockObject $paymentProcessorFabric */
    private MockObject $paymentProcessorFabric;

    /** @var StripePaymentProcessor | MockObject $stripePaymentProcessor */
    private MockObject $stripePaymentProcessor;

    /** @var PaypalPaymentProcessor | MockObject $paypalPaymentProcessor */
    private MockObject $paypalPaymentProcessor;

    protected function setUp(): void
    {
        $this->paymentProcessorFabric = self::createMock(PaymentProcessorFabric::class);
        $this->stripePaymentProcessor = self::createMock(StripePaymentProcessor::class);
        $this->paypalPaymentProcessor = self::createMock(PaypalPaymentProcessor::class);

        parent::setUp();
    }

    /**
     * @return mixed[]
     */
    public static function payStripePaymentProcessorDataProvider(): array
    {
        return [
            [
                'amount' => '250',
                'expectedResult' => true,
            ],
            [
                'amount' => '50',
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @param numeric-string $amount
     * @dataProvider payStripePaymentProcessorDataProvider
     */
    public function testPayStripePaymentProcessor(string $amount, bool $expectedResult): void
    {
        $processor = PaymentProcessorFabric::STRIPE;

        $this
            ->paymentProcessorFabric
            ->expects(self::once())
            ->method('getPaymentProcessor')
            ->with($processor)
            ->willReturnReference($this->stripePaymentProcessor);

        $this
            ->stripePaymentProcessor
            ->expects(self::once())
            ->method('pay')
            ->with($amount)
            ->willReturn($expectedResult);

        $payResult = (new PaymentService($this->paymentProcessorFabric))->pay($amount, $processor);

        self::assertSame($expectedResult, $payResult);
    }

    public function testPayPaypalPaymentProcessorThrowsError(): void
    {
        $amount = '1001';
        $processor = PaymentProcessorFabric::PAYPAL;

        $this
            ->paymentProcessorFabric
            ->expects(self::once())
            ->method('getPaymentProcessor')
            ->with($processor)
            ->willReturnReference($this->paypalPaymentProcessor);

        $this
            ->paypalPaymentProcessor
            ->expects(self::once())
            ->method('pay')
            ->with($amount)
            ->willThrowException(new Exception());

        self::expectException(Exception::class);

        (new PaymentService($this->paymentProcessorFabric))->pay($amount, $processor);
    }
}
