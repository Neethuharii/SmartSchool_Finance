<?php

namespace App\Repository;

use App\Entity\FeeCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeeCategory>
 *
 * @method FeeCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeeCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeeCategory[]    findAll()
 * @method FeeCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeeCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeeCategory::class);
    }

//    /**
//     * @return FeeCategory[] Returns an array of FeeCategory objects
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

//    public function findOneBySomeField($value): ?FeeCategory
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
