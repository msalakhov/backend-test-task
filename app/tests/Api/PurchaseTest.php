<?php

namespace App\Tests\Api;

use ApiPlatform\Validator\Exception\ValidationException;
use App\Entity\Discount\DiscountType;
use App\Service\Payment\PaymentProcessorFabric;
use App\Tests\Factory\Discount\DiscountFactory;
use App\Tests\Factory\Product\ProductFactory;
use App\Tests\Factory\Tax\TaxFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class PurchaseTest extends WebTestCase
{
    use ResetDatabase;
    use Factories;

    /**
     * @return mixed[]
     */
    public static function purchaseDataProvider(): array
    {
        return [
            [
                'paymentProcessor' => PaymentProcessorFabric::PAYPAL,
                'discountType' => DiscountType::FIXED,
            ],
            [
                'paymentProcessor' => PaymentProcessorFabric::STRIPE,
                'discountType' => DiscountType::PERCENT,
            ],
        ];
    }

    /**
     * @dataProvider purchaseDataProvider
     */
    public function testPurchasePaypal(string $paymentProcessor, string $discountType): void
    {
        $client = static::createClient();
        $discountAmount = 10;
        $couponCode = 'X10';
        $taxNumber = 'DE123456789';
        $taxRate = '19';
        $price = 100;

        ProductFactory::new(['price' => $price])->create();
        DiscountFactory::new([
            'amount' => $discountAmount,
            'code' => $couponCode,
            'discountType' => $discountType,
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
            'paymentProcessor' => $paymentProcessor,
            'product' => $productId,
            'taxNumber' => $taxNumber,
            'couponCode' => $couponCode,
        ];

        $client->request('POST', '/purchase', [], [], $headers, json_encode($data));

        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals(true, $response->getContent());
    }

    public function testPurchaseNonExistedPayment(): void
    {
        $client = static::createClient();
        $discountAmount = 10;
        $couponCode = 'X10';
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
            'paymentProcessor' => 'foo',
            'product' => $productId,
            'taxNumber' => $taxNumber,
            'couponCode' => $couponCode,
        ];

        $client->request('POST', '/purchase', [], [], $headers, json_encode($data));

        $this->assertResponseStatusCodeSame(400);
    }
}