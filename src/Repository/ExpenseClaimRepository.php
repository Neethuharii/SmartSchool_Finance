<?php

namespace App\Repository;

use App\Entity\ExpenseClaim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExpenseClaim>
 *
 * @method ExpenseClaim|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenseClaim|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenseClaim[]    findAll()
 * @method ExpenseClaim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseClaimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenseClaim::class);
    }

//    /**
//     * @return ExpenseClaim[] Returns an array of ExpenseClaim objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExpenseClaim
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
