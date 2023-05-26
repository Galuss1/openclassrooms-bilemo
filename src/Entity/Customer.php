<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "customer_detail",
 *          parameters = { "companyId" = "expr(object.getCompany().getId())", "customerId" = "expr(object.getId())" },
 *          absolute = true
 *    ),
 *    exclusion = @Hateoas\Exclusion(groups = {"customer:read"})
 * )
 * @Hateoas\Relation(
 *    "list",
 *     href = @Hateoas\Route(
 *          "customer_list",
 *          parameters = { "companyId" = "expr(object.getCompany().getId())" },
 *          absolute = true
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups = {"customer:read"})
 * )
 * @Hateoas\Relation(
 *    "delete",
 *    href = @Hateoas\Route(
 *          "customer_delete",
 *          parameters = { "companyId" = "expr(object.getCompany().getId())", "customerId" = "expr(object.getId())" },
 *          absolute = true
 *    ),
 *    exclusion = @Hateoas\Exclusion(groups = {"customer:read"}, excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 * )
 * @Hateoas\Relation(
 *    "create",
 *    href = @Hateoas\Route(
 *          "customer_create",
 *          parameters = { "companyId" = "expr(object.getCompany().getId())" },
 *          absolute = true
 *    ),
 *    exclusion = @Hateoas\Exclusion(groups = {"customer:read"}, excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 * )
 */
#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['customer:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['customer:read'])]
    private ?Company $company = null;

    #[ORM\Column(length: 50)]
    #[Groups(['customer:read'])]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères', maxMessage: 'Le prénom doit contenir au maximum {{ limit }} caractères')]
    private ?string $first_name = null;

    #[ORM\Column(length: 50)]
    #[Groups(['customer:read'])]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Le nom doit contenir au moins {{ limit }} caractères', maxMessage: 'Le nom doit contenir au maximum {{ limit }} caractères')]
    private ?string $last_name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['customer:read'])]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email n'est pas valide")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ['ROLE_CUSTOMER'];

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le mot de passe est obligatoire')]
    #[Assert\Length(min: 8, max: 255, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères', maxMessage: 'Le mot de passe doit contenir au maximum {{ limit }} caractères')]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    #[Groups(['customer:read'])]
    #[Assert\NotBlank(message: 'Le numéro de téléphone est obligatoire')]
    #[Assert\Length(min: 10, max: 12, minMessage: 'Le numéro de téléphone doit contenir au moins {{ limit }} caractères', maxMessage: 'Le numéro de téléphone doit contenir au maximum {{ limit }} caractères')]
    private ?string $phone_number = null;

    /**
     * @var Collection<int, CustomerAddress>|CustomerAddress[]
     */
    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: CustomerAddress::class, orphanRemoval: true)]
    #[Groups(['customer:read'])]
    private Collection $customerAddresses;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
        $this->customerAddresses = new ArrayCollection();
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Methode getUsername() which returns the field used by the authentication.
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_CUSTOMER
        $roles[] = 'ROLE_CUSTOMER';

        return \array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
