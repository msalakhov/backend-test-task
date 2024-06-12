<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product\Product;
use App\Entity\Product\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class DoctrineProductRepository implements ProductRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     */
    public function getById(int $productId): Product
    {
        $product = $this
            ->entityManager
            ->getRepository(Product::class)
            ->findOneBy([
                'id' => $productId,
            ]);

        if ($product === null) {
            throw new EntityNotFoundException(
                sprintf('Product with id: %d not found', $productId)
            );
        }

        return $product;
    }
}
