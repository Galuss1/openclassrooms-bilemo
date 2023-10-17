<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProductBrand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ProductBrandFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; ++$i) {
            $productBrand = new ProductBrand();

            match ($i) {
                0 => $productBrand->setName('Apple'),
                1 => $productBrand->setName('Samsung'),
                2 => $productBrand->setName('Huawei'),
                default => throw new \Exception('Invalid data in ProductBrandFixtures'),
            };

            $this->addReference('product-brand-' . $i, $productBrand);

            $manager->persist($productBrand);
        }

        $manager->flush();
    }
}
