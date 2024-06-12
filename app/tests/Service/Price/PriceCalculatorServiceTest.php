<?php

declare(strict_types=1);

namespace app\tests\Service\Price;

use App\Entity\Discount\Discount;
use App\Entity\Discount\DiscountType;
use App\Entity\Product\Product;
use App\Entity\Product\ProductRepository;
use App\Entity\Tax\Tax;
use App\Service\Discount\DiscountService;
use App\Service\Price\PriceCalculatorService;
use App\Service\Tax\TaxService;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PriceCalculatorServiceTest extends KernelTestCase
{
    /** @var DiscountService | MockObject $discountService */
    private MockObject $discountService;

    /** @var ProductRepository | MockObject $productRepository */
    private MockObject $productRepository;

    /** @var TaxService | MockObject $taxService */
    private MockObject $taxService;

    protected function setUp(): void
    {
        $this->discountService = self::createMock(DiscountService::class);
        $this->productRepository = self::createMock(ProductRepository::class);
        $this->taxService = self::createMock(TaxService::class);

        parent::setUp();
    }

    /**
     * @return mixed[]
     */
    public static function calculatePriceDataProvider(): array
    {
        return [
            [
                'price' => '135',
                'discount' => [
                    'amount' => '10',
                    'type' => DiscountType::PERCENT,
                ],
                'tax' => [
                    'countryCode' => 'IT',
                    'amount' => '22',
                ],
                'taxNumber' => 'IT12345678900',
                'discountAmount' => '13.50',
                'priceWithDiscount' => '121.50',
                'taxAmount' => '26.73',
                'priceWithDiscountAndTax' => '148.23',
            ],
            [
                'price' => '456',
                'discount' => [
                    'amount' => '15.2',
                    'type' => DiscountType::FIXED,
                ],
                'tax' => [
                    'countryCode' => 'DE',
                    'amount' => '19',
                ],
                'taxNumber' => 'DE123456789',
                'discountAmount' => '15.2',
                'priceWithDiscount' => '440.80',
                'taxAmount' => '83.752',
                'priceWithDiscountAndTax' => '524.55',
            ],
            [
                'price' => '789',
                'discount' => null,
                'tax' => [
                    'countryCode' => 'FR',
                    'amount' => '20',
                ],
                'taxNumber' => 'FRHB123456789',
                'discountAmount' => '0',
                'priceWithDiscount' => '789.00',
                'taxAmount' => '157.80',
                'priceWithDiscountAndTax' => '946.80',
            ],
        ];
    }

    /**
     * @param numeric-string $price
     * @param array<string, string> | null $discount
     * @param array<string, string> $tax
     * @dataProvider calculatePriceDataProvider
     */
    public function testCalculatePrice(
        string $price,
        ?array $discount,
        array $tax,
        string $taxNumber,
        string $discountAmount,
        string $priceWithDiscount,
        string $taxAmount,
        string $priceWithDiscountAndTax
    ): void {
        $product = (new Product())
            ->setId(1)
            ->setPrice($price);

        if ($discount !== null) {
            /** @var numeric-string $discountAmountToSet */
            $discountAmountToSet = $discount['amount'];
            $discount = (new Discount())
                ->setId(1)
                ->setCode('C13')
                ->setDiscountType($discount['type'])
                ->setAmount($discountAmountToSet);
        }

        (new Tax())
            ->setId(1)
            ->setCountryCode($tax['countryCode'])
            ->setRate($tax['amount']);

        $this
            ->productRepository
            ->expects(self::once())
            ->method('getById')
            ->with($product->getId())
            ->willReturn($product);

        if ($discount === null) {
            $this
                ->discountService
                ->expects(self::never())
                ->method('calculateDiscount');
        } else {
            $this
                ->discountService
                ->expects(self::once())
                ->method('calculateDiscount')
                ->with($product->getPrice(), $discount->getCode())
                ->willReturn($discountAmount);
        }

        $this
            ->taxService
            ->expects(self::once())
            ->method('calculateTaxAmount')
            ->with($priceWithDiscount, $taxNumber)
            ->willReturn($taxAmount);

        $totalAmount = (new PriceCalculatorService($this->discountService, $this->productRepository, $this->taxService))
            ->calculatePrice((int) $product->getId(), $taxNumber, $discount?->getCode());

        self::assertSame($priceWithDiscountAndTax, $totalAmount);
    }

    public function testCalculatePriceThrowsEntityNotFound(): void
    {
        $taxNumber = 'DE123456789';
        $couponCode = null;
        $product = (new Product())
            ->setId(1)
            ->setPrice('123');

        $this
            ->productRepository
            ->expects(self::once())
            ->method('getById')
            ->with($product->getId())
            ->willThrowException(new EntityNotFoundException());

        $this
            ->discountService
            ->expects(self::never())
            ->method('calculateDiscount');

        $this
            ->taxService
            ->expects(self::never())
            ->method('calculateTaxAmount');

        self::expectException(EntityNotFoundException::class);

        (new PriceCalculatorService($this->discountService, $this->productRepository, $this->taxService))
            ->calculatePrice((int) $product->getId(), $taxNumber, $couponCode);
    }
}
