<?php

declare(strict_types=1);

namespace App\Handler;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\CalculatePriceDto;
use App\Service\Price\PriceCalculatorService;

final class CalculatePriceHandler implements ProcessorInterface
{
    public function __construct(
        private PriceCalculatorService $calculatorService
    ) {
    }

    /**
     * @param CalculatePriceDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        return $this->calculatorService->calculatePrice(
            $data->product,
            $data->taxNumber,
            $data->couponCode
        );
    }
}
