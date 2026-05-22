<?php

namespace App\Controller\Finance;
use App\Entity\FeeInvoice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractController
{
    #[Route('/finance/dashboard', name: 'admin_finance_dashboard')]
public function financeDashboard(EntityManagerInterface $em): Response
{
    $invoiceRepo = $em->getRepository(FeeInvoice::class);


    $totalBilled = $invoiceRepo->createQueryBuilder('i')
        ->select('SUM(i.totalAmount)')
        ->getQuery()
        ->getSingleScalarResult() ?? 0;

   
    $totalPaid = $invoiceRepo->createQueryBuilder('i')
        ->select('SUM(i.paidAmount)')
        ->getQuery()
        ->getSingleScalarResult() ?? 0;

    
    $totalBalance = $invoiceRepo->createQueryBuilder('i')
        ->select('SUM(i.balanceAmount)')
        ->getQuery()
        ->getSingleScalarResult() ?? 0;


    $today = new \DateTimeImmutable('today');
    $todayCollection = $invoiceRepo->createQueryBuilder('i')
        ->select('SUM(i.paidAmount)')
        ->where('i.createdAt >= :today')
        ->setParameter('today', $today)
        ->getQuery()
        ->getSingleScalarResult() ?? 0;

  
    $recentInvoices = $invoiceRepo->findBy([], ['createdAt' => 'DESC'], 5);

   
    $categoryData = $em->createQuery('
        SELECT c.name, SUM(i.totalAmount) as total
        FROM App\Entity\FeeInvoice i
        JOIN i.feeCategory c
        GROUP BY c.name
    ')->getResult();

    return $this->render('finance/dashboard/index.html.twig', [
        'stats' => [
            'totalBilled' => $totalBilled,
            'totalPaid' => $totalPaid,
            'totalBalance' => $totalBalance,
            'todayCollection' => $todayCollection,
        ],
        'recentInvoices' => $recentInvoices,
        'categoryData' => $categoryData
    ]);
}
}