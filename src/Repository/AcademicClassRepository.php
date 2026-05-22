<?php

namespace App\Repository;

use App\Entity\AcademicClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AcademicClass>
 *
 * @method AcademicClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method AcademicClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method AcademicClass[]    findAll()
 * @method AcademicClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcademicClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcademicClass::class);
    }

//    /**
//     * @return AcademicClass[] Returns an array of AcademicClass objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AcademicClass
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
