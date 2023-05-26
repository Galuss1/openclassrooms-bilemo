<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductDetailRepository::class)]
class ProductDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 20)]
    #[Groups(['product:read'])]
    private ?string $screen_size = null;

    #[ORM\Column(length: 20)]
    #[Groups(['product:read'])]
    private ?string $storage_capacity = null;

    #[ORM\Column(length: 20)]
    #[Groups(['product:read'])]
    private ?string $operating_system = null;

    #[ORM\Column(length: 20)]
    #[Groups(['product:read'])]
    private ?string $network = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getScreenSize(): ?string
    {
        return $this->screen_size;
    }

    public function setScreenSize(string $screen_size): self
    {
        $this->screen_size = $screen_size;

        return $this;
    }

    public function getStorageCapacity(): ?string
    {
        return $this->storage_capacity;
    }

    public function setStorageCapacity(string $storage_capacity): self
    {
        $this->storage_capacity = $storage_capacity;

        return $this;
    }

    public function getOperatingSystem(): ?string
    {
        return $this->operating_system;
    }

    public function setOperatingSystem(string $operating_system): self
    {
        $this->operating_system = $operating_system;

        return $this;
    }

    public function getNetwork(): ?string
    {
        return $this->network;
    }

    public function setNetwork(string $network): self
    {
        $this->network = $network;

        return $this;
    }
}
