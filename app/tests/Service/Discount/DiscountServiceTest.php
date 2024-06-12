<?php

namespace app\tests\Service\Discount;

use App\Entity\Discount\Discount;
use App\Entity\Discount\DiscountRepository;
use App\Entity\Discount\DiscountType;
use App\Service\Discount\DiscountService;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DiscountServiceTest extends KernelTestCase
{
    private const COUPON_CODE = 'B34';

    /** @var DiscountRepository | MockObject $discountRepository */
    private MockObject $discountRepository;

    protected function setUp(): void
    {
        $this->discountRepository = self::createMock(DiscountRepository::class);

        parent::setUp();
    }

    /**
     * @return mixed[]
     */
    public static function calculateDiscountDataProvider(): array
    {
        return [
            [
                'price' => '100',
                'discountAmount' => '10',
                'discountType' => DiscountType::FIXED,
                'expectedCalculatedDiscountAmount' => 10,
            ],
            [
                'price' => '250',
                'discountAmount' => '15',
                'discountType' => DiscountType::PERCENT,
                'expectedCalculatedDiscountAmount' => 37.5,
            ],
        ];
    }

    /**
     * @param numeric-string $discountAmount
     * @param numeric-string $price
     * @dataProvider calculateDiscountDataProvider
     */
    public function testCalculateDiscount(string $price, string $discountAmount, string $discountType, float $expectedCalculatedDiscountAmount): void
    {
        $discount = (new Discount())
            ->setCode(self::COUPON_CODE)
            ->setDiscountType($discountType)
            ->setAmount($discountAmount);

        $this
            ->discountRepository
            ->expects(self::once())
            ->method('getByCouponCode')
            ->with(self::COUPON_CODE)
            ->willReturn($discount);

        $calculatedDiscountAmount = $this->getService()->calculateDiscount($price, self::COUPON_CODE);
        self::assertEquals($expectedCalculatedDiscountAmount, $calculatedDiscountAmount);
    }

    public function testCalculateDiscountThrowsError(): void
    {
        $price = '250';
        $discountAmount = '15';
        $wrongCouponCode = 'H21';

        (new Discount())
            ->setCode(self::COUPON_CODE)
            ->setDiscountType(DiscountType::PERCENT)
            ->setAmount($discountAmount);

        $this
            ->discountRepository
            ->expects(self::once())
            ->method('getByCouponCode')
            ->with($wrongCouponCode)
            ->willThrowException(new EntityNotFoundException());

        self::expectException(EntityNotFoundException::class);

        $this->getService()->calculateDiscount($price, $wrongCouponCode);
    }

    private function getService(): DiscountService
    {
        return new DiscountService($this->discountRepository);
    }
}
