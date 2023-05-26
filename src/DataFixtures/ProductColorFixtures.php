<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProductColor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ProductColorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; ++$i) {
            $productColor = new ProductColor();

            match ($i) {
                0 => $productColor->setName('Gris sideral'),
                1 => $productColor->setName('Blanc'),
                2 => $productColor->setName('Noir'),
                3 => $productColor->setName('Or'),
                4 => $productColor->setName('Argent'),
                default => throw new \Exception('Invalid data in ProductColorFixtures'),
            };

            $this->addReference('product-color-' . $i, $productColor);

            $manager->persist($productColor);
        }

        $manager->flush();
    }
}
