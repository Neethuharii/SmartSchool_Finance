<?php

namespace App\Repository;

use App\Entity\StudentDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentDiscount>
 *
 * @method StudentDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentDiscount[]    findAll()
 * @method StudentDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentDiscount::class);
    }

//    /**
//     * @return StudentDiscount[] Returns an array of StudentDiscount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StudentDiscount
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
