<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CustomerAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class CustomerAddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $this->createAddress($i, $manager);
        }

        $manager->flush();
    }

    public function createAddress(int $customerId, ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $customerAddress = new CustomerAddress();
        $customerAddress->setCustomer($this->getReference('customer-' . $customerId));
        $customerAddress->setCountry('France');
        $customerAddress->setCity($faker->city());
        $customerAddress->setPostalCode(\rand(30000, 95000));
        $customerAddress->setAddress($faker->streetAddress());
        if (($customerId % 2) === 0) {
            $customerAddress->setAddressDetails($faker->secondaryAddress());
        }

        $manager->persist($customerAddress);
    }

    public function getDependencies(): array
    {
        return [CustomerFixtures::class];
    }
}
