<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductStock>
 *
 * @method null|ProductStock find($id, $lockMode = null, $lockVersion = null)
 * @method null|ProductStock findOneBy(array $criteria, array $orderBy = null)
 * @method ProductStock[] findAll()
 * @method ProductStock[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ProductStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductStock::class);
    }

    public function save(ProductStock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductStock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
