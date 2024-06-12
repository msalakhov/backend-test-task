<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    /** @var numeric-string $price */
    #[ORM\Column(type: 'decimal', options: ['precision' => 8, 'scale' => 2])]
    private string $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return numeric-string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param numeric-string $price
     */
    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }
}
