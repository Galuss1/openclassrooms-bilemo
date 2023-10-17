<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method null|Customer find($id, $lockMode = null, $lockVersion = null)
 * @method null|Customer findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[] findAll()
 * @method Customer[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CustomerRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
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

    public function findAllByCompanyWithPagination(int $companyId, int $page, int $limit)
    {
        return $this->createQueryBuilder('c')
            ->addSelect('c')
            ->where('c.company = :companyId')
            ->setParameter('companyId', $companyId)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByCompany(int $companyId, int $customerId)
    {
        return $this->createQueryBuilder('c')
            ->addSelect('c')
            ->where('c.company = :companyId')
            ->andWhere('c.id = :customerId')
            ->setParameter('companyId', $companyId)
            ->setParameter('customerId', $customerId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $customer, string $newHashedPassword): void
    {
        if (!$customer instanceof Customer) {
            throw new UnsupportedUserException(\sprintf('Instances of "%s" are not supported.', $customer::class));
        }

        $customer->setPassword($newHashedPassword);

        $this->save($customer, true);
    }
}
