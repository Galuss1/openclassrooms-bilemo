<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductColor>
 *
 * @method null|ProductColor find($id, $lockMode = null, $lockVersion = null)
 * @method null|ProductColor findOneBy(array $criteria, array $orderBy = null)
 * @method ProductColor[] findAll()
 * @method ProductColor[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ProductColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductColor::class);
    }

    public function save(ProductColor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductColor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
