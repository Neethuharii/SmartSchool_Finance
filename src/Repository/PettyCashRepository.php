<?php

namespace App\Repository;

use App\Entity\PettyCash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PettyCash>
 *
 * @method PettyCash|null find($id, $lockMode = null, $lockVersion = null)
 * @method PettyCash|null findOneBy(array $criteria, array $orderBy = null)
 * @method PettyCash[]    findAll()
 * @method PettyCash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PettyCashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PettyCash::class);
    }

//    /**
//     * @return PettyCash[] Returns an array of PettyCash objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PettyCash
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
