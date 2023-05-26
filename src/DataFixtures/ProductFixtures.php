<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->createProduct(0, 0, 'iPhone 12 Pro', "L'iPhone 12 Pro est un smartphone haut de gamme avec un grand écran, un appareil photo professionnel, une puce rapide et une connexion 5G.", $manager);
        $this->createProduct(1, 0, 'iPhone 12 Pro Max', "L'iPhone 12 Pro Max est un smartphone haut de gamme d'Apple, avec un grand écran, un appareil photo professionnel, une puce rapide et une connexion 5G. Il est la version maximisée de l'iPhone 12 Pro.", $manager);
        $this->createProduct(2, 0, 'iPhone 12', "L'iPhone 12 est un smartphone haut de gamme d'Apple, avec un grand écran, un appareil photo professionnel, une puce rapide et une connexion 5G. Il est également disponible en version Mini.", $manager);
        $this->createProduct(3, 0, 'iPhone 12 Mini', "L'iPhone 12 Mini est un smartphone haut de gamme d'Apple, avec un petit écran, un appareil photo professionnel, une puce rapide et une connexion 5G. Il est la version compacte de l'iPhone 12.", $manager);
        $this->createProduct(4, 1, 'Galaxy S21', 'Le Galaxy S21 est un smartphone haut de gamme de Samsung, avec un grand écran, un appareil photo professionnel, une puce rapide et une connexion 5G.', $manager);
        $this->createProduct(5, 1, 'Galaxy S21 Ultra', 'Le Galaxy S21 Ultra est un smartphone haut de gamme de Samsung, avec un écran encore plus grand, un appareil photo professionnel avancé, une puce rapide et une connexion 5G.', $manager);
        $this->createProduct(6, 1, 'Galaxy S21+', 'Le Galaxy S21+ est un smartphone haut de gamme de Samsung, avec un écran plus grand que le S21 mais moins grand que le S21 Ultra, un appareil photo professionnel, une puce rapide et une connexion 5G.', $manager);
        $this->createProduct(7, 2, 'HuaWei P40 Pro', 'Le HuaWei P40 Pro est un smartphone haut de gamme de Huawei, avec un grand écran, un appareil photo professionnel, une puce rapide et une connexion 5G.', $manager);
        $this->createProduct(8, 2, 'HuaWei P40', 'Le HuaWei P40 est un smartphone haut de gamme de Huawei, avec un grand écran, un appareil photo professionnel, une puce rapide et une connexion 5G.', $manager);
        $this->createProduct(9, 2, 'HuaWei P40 Lite', 'Le HuaWei P40 Lite est un smartphone haut de gamme de Huawei, avec un grand écran, un appareil photo professionnel de qualité décente, une puce rapide et une connexion 5G. Il est moins cher que les autres modèles de la série P40.', $manager);

        $manager->flush();
    }

    public function createProduct(
        int $productId,
        int $brand,
        string $name,
        string $description,
        ObjectManager $manager
    ): void {
        $product = new Product();
        $product->setBrand($this->getReference('product-brand-' . $brand));
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice(\rand(350, 1100));

        $this->addReference('product-' . $productId, $product);

        $manager->persist($product);
    }
}
