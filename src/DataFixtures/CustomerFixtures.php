<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function removeAccents($string): string
    {
        $search = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'];
        $replace = ['A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'];

        return \str_replace($search, $replace, (string) $string);
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $this->createCustomer($i, 'company-0', 'password', ['ROLE_CUSTOMER'], $manager, null);
        }

        $this->createCustomer(20, 'company-0', 'password', ['ROLE_ADMIN'], $manager, null);
        $this->createCustomer(21, 'company-0', 'password', ['ROLE_ADMIN'], $manager, 'admin@bilemo.fr');

        $manager->flush();
    }

    public function createCustomer(int $customerId, string $companyReference, string $password, array $roles, ObjectManager $manager, ?string $email = null): void
    {
        $faker = Factory::create('fr_FR');

        $customer = new Customer();
        $customer->setCompany($this->getReference($companyReference));
        $customer->setFirstName($this->removeAccents($faker->firstName()));
        $customer->setLastName($this->removeAccents($faker->lastName()));

        $email = null !== $email ? $email : \strtolower($customer->getFirstName()) . '.' . \strtolower($customer->getLastName()) . '@email.fr';
        $customer->setEmail($email);

        $customer->setPassword($this->passwordHasher->hashPassword($customer, $password));
        $customer->setRoles($roles);
        $customer->setPhoneNumber(0 . \rand(6, 7) . \rand(10_000_000, 99_999_999));

        $this->addReference('customer-' . $customerId, $customer);

        $manager->persist($customer);
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class];
    }
}
