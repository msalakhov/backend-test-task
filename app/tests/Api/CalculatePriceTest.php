<?php

namespace Tests\Api;

use App\Entity\Discount\DiscountType;
use App\Tests\Factory\Discount\DiscountFactory;
use App\Tests\Factory\Product\ProductFactory;
use App\Tests\Factory\Tax\TaxFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class CalculatePriceTest extends WebTestCase
{
    use ResetDatabase;
    use Factories;

    public function testCalculatePriceWithFixedDiscount(): void
    {
        $client = static::createClient();
        $discountAmount = 10;
        $couponCode = 'X10';
        $taxNumber = 'DE123456789';
        $taxRate = '19';
        $price = 100;
        /**
         * ($price - $discountAmount) * $taxRate/100 + ($price - $discountAmount)
         * (100 - 10) * 0.19 + (100 - 10) = 107.1
         */
        $expectedPrice = '107.1';

        ProductFactory::new(['price' => $price])->create();
        DiscountFactory::new([
            'amount' => $discountAmount,
            'code' => $couponCode,
            'discountType' => DiscountType::FIXED,
        ])->create();
        TaxFactory::new([
            'countryCode' => 'DE',
            'rate' => $taxRate,
        ])->create();

        $productId = ProductFactory::first()->_get('id');

        $headers = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ];

        $data = [
            'product' => $productId,
            'taxNumber' => $taxNumber,
            'couponCode' => $couponCode,
        ];

        $client->request('POST', '/calculate-price', [], [], $headers, json_encode($data));

        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSame($expectedPrice, $response->getContent());
    }

    public function testCalculatePriceWithPercentDiscount(): void
    {
        $client = static::createClient();
        $discountAmount = 10;
        $couponCode = 'X10';
        $taxNumber = 'DE123456789';
        $taxRate = '19';
        $price = 100;
        /**
         * ($price - $price * $discountAmount) * $taxRate/100 + ($price - $price * $discountAmount)
         * (100 - 100*0.1) * 0.19 + (100 - 100*0.1) = 107.1
         */
        $expectedPrice = '107.1';

        ProductFactory::new(['price' => $price])->create();
        DiscountFactory::new([
            'amount' => $discountAmount,
            'code' => $couponCode,
            'discountType' => DiscountType::PERCENT,
        ])->create();
        TaxFactory::new([
            'countryCode' => 'DE',
            'rate' => $taxRate,
        ])->create();

        $productId = ProductFactory::first()->_get('id');

        $headers = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ];

        $data = [
            'product' => $productId,
            'taxNumber' => $taxNumber,
            'couponCode' => $couponCode,
        ];

        $client->request('POST', '/calculate-price', [], [], $headers, json_encode($data));

        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSame($expectedPrice, $response->getContent());
    }

    public function testCalculatePriceWithWrongCouponCode(): void
    {
        $client = static::createClient();
        $discountAmount = 10;
        $couponCode = 'X10';
        $wrongCouponCode = 'Z10';
        $taxNumber = 'DE123456789';
        $taxRate = '19';
        $price = 100;

        ProductFactory::new(['price' => $price])->create();
        DiscountFactory::new([
            'amount' => $discountAmount,
            'code' => $couponCode,
            'discountType' => DiscountType::PERCENT,
        ])->create();
        TaxFactory::new([
            'countryCode' => 'DE',
            'rate' => $taxRate,
        ])->create();

        $productId = ProductFactory::first()->_get('id');

        $headers = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ];

        $data = [
            'product' => $productId,
            'taxNumber' => $taxNumber,
            'couponCode' => $wrongCouponCode,
        ];

        $client->request('POST', '/calculate-price', [], [], $headers, json_encode($data));

        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(400);
    }
}
