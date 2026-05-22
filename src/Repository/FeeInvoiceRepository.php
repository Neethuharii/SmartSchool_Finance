<?php

namespace App\Repository;

use App\Entity\FeeInvoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeeInvoice>
 *
 * @method FeeInvoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeeInvoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeeInvoice[]    findAll()
 * @method FeeInvoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeeInvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeeInvoice::class);
    }

//    /**
//     * @return FeeInvoice[] Returns an array of FeeInvoice objects
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

//    public function findOneBySomeField($value): ?FeeInvoice
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
