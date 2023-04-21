<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('customer:read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('customer:read')]
    private ?Company $company = null;

    #[ORM\Column(length: 50)]
    #[Groups('customer:read')]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups('customer:read')]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    #[Groups('customer:read')]
    private ?string $phone_number = null;

    /**
     * @var Collection<int, CustomerAddress>|CustomerAddress[]
     */
    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: CustomerAddress::class, orphanRemoval: true)]
    #[Groups('customer:read')]
    private Collection $customerAddresses;

    public function __construct()
    {
        $this->customerAddresses = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * @return Collection<int, CustomerAddress>
     */
    public function getCustomerAddresses(): Collection
    {
        return $this->customerAddresses;
    }

    public function addCustomerAddress(CustomerAddress $customerAddress): self
    {
        if (!$this->customerAddresses->contains($customerAddress)) {
            $this->customerAddresses->add($customerAddress);
            $customerAddress->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomerAddress(CustomerAddress $customerAddress): self
    {
        // set the owning side to null (unless already changed)
        if ($this->customerAddresses->removeElement($customerAddress) && $customerAddress->getCustomer() === $this) {
            $customerAddress->setCustomer(null);
        }

        return $this;
    }
}
