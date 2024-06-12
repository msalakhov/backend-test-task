<?php

declare(strict_types=1);

namespace App\Entity\Tax;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['mask'])]
class Tax
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string')]
    private string $countryCode;

    #[ORM\Column(type: 'string')]
    private string $mask;

    #[ORM\Column(type: 'decimal', options: ['precision' => 4, 'scale' => 2])]
    private string $rate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getMask(): string
    {
        return $this->mask;
    }

    public function setMask(string $mask): self
    {
        $this->mask = $mask;

        return $this;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
