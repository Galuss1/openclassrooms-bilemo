<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductColorRepository::class)]
class ProductColor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Groups(['product:read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, ProductStock>|ProductStock[]
     */
    #[ORM\OneToMany(mappedBy: 'color', targetEntity: ProductStock::class, orphanRemoval: true)]
    private Collection $productStocks;

    public function __construct()
    {
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

    /**
     * @return Collection<int, ProductStock>
     */
    public function getProductStocks(): Collection
    {
        return $this->productStocks;
    }

    public function addProductStock(ProductStock $productStock): self
    {
        if (!$this->productStocks->contains($productStock)) {
            $this->productStocks->add($productStock);
            $productStock->setColor($this);
        }

        return $this;
    }

    public function removeProductStock(ProductStock $productStock): self
    {
        // Set the owning side to null (unless already changed)
        if ($this->productStocks->removeElement($productStock) && $productStock->getColor() === $this) {
            $productStock->setColor(null);
        }

        return $this;
    }
}
