<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CustomerAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerAddressRepository::class)]
class CustomerAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'customerAddresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(length: 50)]
    #[Groups(['customer:read'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Le nom du pays doit contenir au moins {{ limit }} caractères', maxMessage: 'Le nom du pays doit contenir au maximum {{ limit }} caractères')]
    private ?string $country = null;

    #[ORM\Column(length: 50)]
    #[Groups(['customer:read'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Le nom de la ville doit contenir au moins {{ limit }} caractères', maxMessage: 'Le nom de la ville doit contenir au maximum {{ limit }} caractères')]
    private ?string $city = null;

    #[ORM\Column()]
    #[Groups(['customer:read'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer', message: 'Le code postal doit être un nombre')]
    #[Assert\Length(min: 5, max: 5, exactMessage: 'Le code postal doit contenir {{ limit }} caractères')]
    private ?int $postal_code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['customer:read'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255, minMessage: 'L\'adresse doit contenir au moins {{ limit }} caractères', maxMessage: 'L\'adresse doit contenir au maximum {{ limit }} caractères')]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['customer:read'])]
    #[SerializedName('address_details')]
    #[Assert\Length(min: 5, max: 255, minMessage: 'Les détails de l\'adresse doivent contenir au moins {{ limit }} caractères', maxMessage: 'Les détails de l\'adresse doivent contenir au maximum {{ limit }} caractères')]
    private ?string $address_details = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postal_code;
    }

    public function setPostalCode(int $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddressDetails(): ?string
    {
        return $this->address_details;
    }

    public function setAddressDetails(?string $address_details): self
    {
        $this->address_details = $address_details;

        return $this;
    }
}
