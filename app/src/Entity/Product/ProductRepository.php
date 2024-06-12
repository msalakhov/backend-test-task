<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\ORM\EntityNotFoundException;

interface ProductRepository
{
    /**
     * @throws EntityNotFoundException
     */
    public function getById(int $productId): Product;
}
