<?php

namespace App\DataFixtures;

use App\Entity\Product\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    /**
     * @return mixed[]
     */
    private function fakeData(): array
    {
        return [
            [
                'name' => 'Iphone',
                'price' => '100',
            ],
            [
                'name' => 'Наушники',
                'price' => '20',
            ],
            [
                'name' => 'Чехол',
                'price' => '10',
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->fakeData() as $productData) {
            $product = (new Product())
                ->setName($productData['name'])
                ->setPrice($productData['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
