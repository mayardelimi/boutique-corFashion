<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price_h = null;

    #[ORM\Column]
    private ?float $price_d = null;

    #[ORM\ManyToOne(inversedBy: 'cities')]
    private ?carrier $carriercity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPriceH(): ?float
    {
        return $this->price_h;
    }

    public function setPriceH(float $price_h): static
    {
        $this->price_h = $price_h;

        return $this;
    }

    public function getPriceD(): ?float
    {
        return $this->price_d;
    }

    public function setPriceD(float $price_d): static
    {
        $this->price_d = $price_d;

        return $this;
    }

    public function getCarriercity(): ?carrier
    {
        return $this->carriercity;
    }

    public function setCarriercity(?carrier $carriercity): static
    {
        $this->carriercity = $carriercity;

        return $this;
    }
}
