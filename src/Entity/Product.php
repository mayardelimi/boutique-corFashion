<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $tva = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $discount_pct = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductImage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $images;

    /**
     * @var Collection<int, ProductVariant>
     */
    #[ORM\OneToMany(targetEntity: ProductVariant::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $variants; // 🔄 Changed from $size

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->variants = new ArrayCollection(); // 🔄 Updated initialization
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ProductImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getPrice(): ?float
    {
        if ($this->price === null) {
            return null;
        }

        return $this->price * (1 - ($this->discount_pct / 100));
    }
    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getPriceWt(): float
    {
        $coef = 1 + ((float)$this->tva / 100);
        return $coef * $this->getPrice();
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): static
    {
        $this->tva = $tva;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDiscountPct(): ?int
    {
        return $this->discount_pct;
    }

    public function setDiscountPct(int $discount_pct): static
    {
        $this->discount_pct = $discount_pct;
        return $this;
    }

    /**
     * @return Collection<int, ProductVariant>
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }
    public function gettotalQTE(): int
    {
        $t = 0;
        $var= $this->getVariants();
            foreach ( $var as $item) {
                $t= $t +  $item->getQuantityAvailable();
            }
            return $t;
    }

    public function addVariant(ProductVariant $variant): static
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setProduct($this);
        }
        return $this;
    }

    public function removeVariant(ProductVariant $variant): static
    {
        if ($this->variants->removeElement($variant)) {
            if ($variant->getProduct() === $this) {
                $variant->setProduct(null);
            }
        }
        return $this;
    }
}
