<?php

namespace App\Repository;

use App\Entity\FeePayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeePayment>
 *
 * @method FeePayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeePayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeePayment[]    findAll()
 * @method FeePayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeePaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeePayment::class);
    }

//    /**
//     * @return FeePayment[] Returns an array of FeePayment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FeePayment
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
