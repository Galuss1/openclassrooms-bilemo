<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductDetail>
 *
 * @method null|ProductDetail find($id, $lockMode = null, $lockVersion = null)
 * @method null|ProductDetail findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDetail[] findAll()
 * @method ProductDetail[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ProductDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDetail::class);
    }

    public function save(ProductDetail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductDetail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
