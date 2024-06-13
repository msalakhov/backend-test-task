<?php

namespace App\Handler;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\PurchaseDto;
use App\Service\Payment\PaymentService;
use App\Service\Price\PriceCalculatorService;

final class PurchaseHandler implements ProcessorInterface
{
    public function __construct(
        private PriceCalculatorService $calculatorService,
        private PaymentService $paymentService
    ) {
    }

    /**
     * @param PurchaseDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $amount = $this->calculatorService->calculatePrice(
            $data->product,
            $data->taxNumber,
            $data->couponCode
        );

        return $this->paymentService->pay((string) $amount, $data->paymentProcessor);
    }
}
