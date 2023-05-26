<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProductDetail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ProductDetailFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->createProductDetail(0, '6.1 pouces', '128 Go', 'iOS 14', '5G', $manager);
        $this->createProductDetail(1, '6.7 pouces', '128 Go', 'iOS 14', '5G', $manager);
        $this->createProductDetail(2, '6.1 pouces', '128 Go', 'iOS 14', '5G', $manager);
        $this->createProductDetail(3, '5.4 pouces', '128 Go', 'iOS 14', '5G', $manager);
        $this->createProductDetail(4, '6.2 pouces', '128 Go', 'Android 11', '5G', $manager);
        $this->createProductDetail(5, '6.8 pouces', '128 Go', 'Android 11', '5G', $manager);
        $this->createProductDetail(6, '6.5 pouces', '128 Go', 'Android 11', '5G', $manager);
        $this->createProductDetail(7, '6.5 pouces', '128 Go', 'Android 11', '5G', $manager);
        $this->createProductDetail(8, '6.5 pouces', '128 Go', 'Android 11', '5G', $manager);
        $this->createProductDetail(9, '6.76 pouces', '128 Go', 'Android 11', '5G', $manager);

        $manager->flush();
    }

    public function createProductDetail(
        int $countProduct,
        string $screen_size,
        string $storage_capacity,
        string $operating_system,
        string $network,
        ObjectManager $manager
    ): void {
        $productDetail = new ProductDetail();
        $productDetail->setProduct($this->getReference('product-' . $countProduct));
        $productDetail->setScreenSize($screen_size);
        $productDetail->setStorageCapacity($storage_capacity);
        $productDetail->setOperatingSystem($operating_system);
        $productDetail->setNetwork($network);

        $manager->persist($productDetail);
    }

    public function getDependencies(): array
    {
        return [ProductFixtures::class];
    }
}
