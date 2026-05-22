<?php

namespace App\Repository;

use App\Entity\Syllabus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Syllabus>
 *
 * @method Syllabus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Syllabus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Syllabus[]    findAll()
 * @method Syllabus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SyllabusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Syllabus::class);
    }

//    /**
//     * @return Syllabus[] Returns an array of Syllabus objects
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

//    public function findOneBySomeField($value): ?Syllabus
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
