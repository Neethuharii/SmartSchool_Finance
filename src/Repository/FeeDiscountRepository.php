<?php

namespace App\Repository;

use App\Entity\FeeDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeeDiscount>
 *
 * @method FeeDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeeDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeeDiscount[]    findAll()
 * @method FeeDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeeDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeeDiscount::class);
    }

//    /**
//     * @return FeeDiscount[] Returns an array of FeeDiscount objects
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

//    public function findOneBySomeField($value): ?FeeDiscount
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
