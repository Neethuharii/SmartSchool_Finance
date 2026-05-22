<?php

namespace App\Repository;

use App\Entity\SalarySlip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SalarySlip>
 *
 * @method SalarySlip|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalarySlip|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalarySlip[]    findAll()
 * @method SalarySlip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalarySlipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SalarySlip::class);
    }

//    /**
//     * @return SalarySlip[] Returns an array of SalarySlip objects
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

//    public function findOneBySomeField($value): ?SalarySlip
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
