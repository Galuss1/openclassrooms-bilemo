<?php

namespace App\DataFixtures;

use App\Entity\ProductStock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductStockFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $productStock = new ProductStock();
                $productStock->setModel($this->getReference('product-model-' . $i));
                $productStock->setColor($this->getReference('product-color-' . $j));
                $productStock->setQuantity(rand(0, 15));

                $manager->persist($productStock);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductColorFixtures::class,
            ProductModelFixtures::class,
        ];
    }
}