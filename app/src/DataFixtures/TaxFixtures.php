<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Tax\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxFixtures extends Fixture
{
    /**
     * @return mixed[]
     */
    private function fakeData(): array
    {
        return [
            [
                'countryCode' => 'DE',
                'mask' => 'DEXXXXXXXXX',
                'taxRate' => '19',
            ],
            [
                'countryCode' => 'IT',
                'mask' => 'ITXXXXXXXXXXX',
                'taxRate' => '22',
            ],
            [
                'countryCode' => 'GR',
                'mask' => 'GRXXXXXXXXX',
                'taxRate' => '24',
            ],
            [
                'countryCode' => 'FR',
                'mask' => 'FRYYXXXXXXXXX',
                'taxRate' => '20',
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->fakeData() as $taxData) {
            $tax = (new Tax())
                ->setCountryCode($taxData['countryCode'])
                ->setMask($taxData['mask'])
                ->setRate($taxData['taxRate']);

            $manager->persist($tax);
        }

        $manager->flush();
    }
}
