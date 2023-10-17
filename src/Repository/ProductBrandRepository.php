<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductBrand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductBrand>
 *
 * @method null|ProductBrand find($id, $lockMode = null, $lockVersion = null)
 * @method null|ProductBrand findOneBy(array $criteria, array $orderBy = null)
 * @method ProductBrand[] findAll()
 * @method ProductBrand[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ProductBrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductBrand::class);
    }

    public function save(ProductBrand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductBrand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
