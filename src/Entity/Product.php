<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\Groups;

/**
 * @Hateoas\Relation(
 *    "self",
 *    href = @Hateoas\Route(
 *          "product_detail",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *    ),
 *    exclusion = @Hateoas\Exclusion(groups = {"product:read"})
 * )
 * @Hateoas\Relation(
 *    "list",
 *    href = @Hateoas\Route(
 *          "product_list",
 *          absolute = true
 *    ),
 *    exclusion = @Hateoas\Exclusion(groups = {"product:read"})
 * )
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Groups(['product:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['product:read'])]
    private ?ProductBrand $brand = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['product:read'])]
    private ?int $price = null;

    #[ORM\Column(length: 4, options: ['default' => 'EUR'])]
    #[Groups(['product:read'])]
    private ?string $price_currency = 'EUR';

    /**
     * @var Collection<int, ProductDetail>|ProductDetail[]
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductDetail::class)]
    #[Groups(['product:read'])]
    private Collection $productDetails;

    /**
     * @var Collection<int, ProductStock>|ProductStock[]
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductStock::class, orphanRemoval: true)]
    #[Groups(['product:read'])]
    private Collection $productStocks;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
        $this->productDetails = new ArrayCollection();
        $this->productStocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): ?ProductBrand
    {
        return $this->brand;
    }

    public function setBrand(?ProductBrand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceCurrency(): ?string
    {
        return $this->price_currency;
    }

    public function setPriceCurrency(string $price_currency): self
    {
        $this->price_currency = $price_currency;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, ProductDetail>
     */
    public function getProductDetails(): Collection
    {
        return $this->productDetails;
    }

    public function addProductDetail(ProductDetail $productDetail): self
    {
        if (!$this->productDetails->contains($productDetail)) {
            $this->productDetails->add($productDetail);
            $productDetail->setProduct($this);
        }

        return $this;
    }

    public function removeProductDetail(ProductDetail $productDetail): self
    {
        // set the owning side to null (unless already changed)
        if ($this->productDetails->removeElement($productDetail) && $productDetail->getProduct() === $this) {
            $productDetail->setProduct(null);
        }

        return $this;
    }

    /**
     * @return Collection|ProductStock[]
     */
    public function getProductStocks(): Collection
    {
        return $this->productStocks;
    }

    public function addProductStock(ProductStock $productStock): self
    {
        if (!$this->productStocks->contains($productStock)) {
            $this->productStocks[] = $productStock;
            $productStock->setProduct($this);
        }

        return $this;
    }

    public function removeProductStock(ProductStock $productStock): self
    {
        // set the owning side to null (unless already changed)
        if ($this->productStocks->removeElement($productStock) && $productStock->getProduct() === $this) {
            $productStock->setProduct(null);
        }

        return $this;
    }
}
