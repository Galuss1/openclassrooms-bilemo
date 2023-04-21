<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method null|Customer find($id, $lockMode = null, $lockVersion = null)
 * @method null|Customer findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[] findAll()
 * @method Customer[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function save(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//  /**
//   * @return Customer[] Returns an array of Customer objects
//   */
//  public function findByExampleField($value): array
//  {
//      return $this->createQueryBuilder('c')
//          ->andWhere('c.exampleField = :val')
//          ->setParameter('val', $value)
//          ->orderBy('c.id', 'ASC')
//          ->setMaxResults(10)
//          ->getQuery()
//          ->getResult()
//      ;
//  }

//  public function findOneBySomeField($value): ?Customer
//  {
//      return $this->createQueryBuilder('c')
//          ->andWhere('c.exampleField = :val')
//          ->setParameter('val', $value)
//          ->getQuery()
//          ->getOneOrNullResult()
//      ;
//  }
}
