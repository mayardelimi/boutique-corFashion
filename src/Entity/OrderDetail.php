<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
class OrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    private ?Order $myOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $productName = null;

    #[ORM\Column(length: 255)]
    private ?string $productIllustration = null;

    #[ORM\Column]
    private ?float $productQuantity = null;

    #[ORM\Column]
    private ?float $productTva = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?productVariant $product_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMyOrder(): ?Order
    {
        return $this->myOrder;
    }

    public function setMyOrder(?Order $myOrder): static
    {
        $this->myOrder = $myOrder;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductIllustration(): ?string
    {
        return $this->productIllustration;
    }

    public function setProductIllustration(string $productIllustration): static
    {
        $this->productIllustration = $productIllustration;

        return $this;
    }

    public function getProductQuantity(): ?float
    {
        return $this->productQuantity;
    }

    public function setProductQuantity(float $productQuantity): static
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    public function getProductTva(): ?float
    {
        return $this->productTva;
    }

    public function setProductTva(float $productTva): static
    {
        $this->productTva = $productTva;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }
    public function getProductPriceWt(){
        $coeff = 1 + ($this->productTva /100);
        return $coeff * $this->productQuantity;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getProductId(): ?productVariant
    {
        return $this->product_id;
    }

    public function setProductId(?productVariant $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }
}
