<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Discount\Discount;
use App\Entity\Discount\DiscountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class DoctrineDiscountRepository implements DiscountRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     */
    public function getByCouponCode(string $couponCode): Discount
    {
        /** @var Discount | null $discount */
        $discount = $this
            ->entityManager
            ->getRepository(Discount::class)
            ->createQueryBuilder('d')
            ->where('d.code = :couponCode')
            ->setParameter('couponCode', $couponCode)
            ->getQuery()
            ->getOneOrNullResult();

        if ($discount === null) {
            throw new EntityNotFoundException(sprintf('Discount with coupon: %s not found', $couponCode));
        }

        return $discount;
    }
}
