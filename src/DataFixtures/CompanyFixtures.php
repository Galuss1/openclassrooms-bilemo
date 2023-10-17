<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->createCompany(0, 'Telecom Paris', $manager);

        $manager->flush();
    }

    public function createCompany(int $countCompany, string $name, ObjectManager $manager): void
    {
        $company = new Company();
        $company->setName($name);

        $this->addReference('company-' . $countCompany, $company);

        $manager->persist($company);
    }
}
