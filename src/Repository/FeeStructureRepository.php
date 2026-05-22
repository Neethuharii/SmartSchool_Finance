<?php

namespace App\Repository;

use App\Entity\FeeStructure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeeStructure>
 *
 * @method FeeStructure|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeeStructure|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeeStructure[]    findAll()
 * @method FeeStructure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeeStructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeeStructure::class);
    }

//    /**
//     * @return FeeStructure[] Returns an array of FeeStructure objects
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

//    public function findOneBySomeField($value): ?FeeStructure
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
