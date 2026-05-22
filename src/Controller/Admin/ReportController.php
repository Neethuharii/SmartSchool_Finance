<?php

namespace App\Controller\Admin;

use App\Entity\FeeInvoice;
use App\Entity\FeePayment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reports')]
class ReportController extends AbstractController
{
    #[Route('/revenue', name: 'admin_report_revenue')]
    public function revenueReport(EntityManagerInterface $em): Response
    {
        // 1. Total Billed (Sum of all invoices issued)
        $totalBilled = $em->createQuery(
            'SELECT SUM(i.totalAmount) FROM App\Entity\FeeInvoice i'
        )->getSingleScalarResult() ?? 0;

        // 2. Total Collected (Sum of all actual payments)
        $totalCollected = $em->createQuery(
            'SELECT SUM(p.amount) FROM App\Entity\FeePayment p'
        )->getSingleScalarResult() ?? 0;

        // 3. Arrears (Sum of all balances remaining on invoices)
        $totalPending = $em->createQuery(
            'SELECT SUM(i.balanceAmount) FROM App\Entity\FeeInvoice i'
        )->getSingleScalarResult() ?? 0;

        // 4. Monthly Collection Trend (Real database data)
        
        $monthlyRevenue = $em->getConnection()->fetchAllAssociative("
            SELECT MONTHNAME(paid_at) as month, SUM(amount) as total 
            FROM fee_payment 
            WHERE YEAR(paid_at) = YEAR(CURDATE())
            GROUP BY MONTH(paid_at)
            ORDER BY MONTH(paid_at)
        ");

        return $this->render('admin/report/index.html.twig', [
            'totalBilled' => $totalBilled,
            'totalCollected' => $totalCollected,
            'totalPending' => $totalPending,
            'monthlyRevenue' => $monthlyRevenue
        ]);
    }
    
    #[Route('/defaulters', name: 'admin_report_defaulters')]
public function defaulterReport(EntityManagerInterface $em): Response
{
    // Fetch all invoices where balance is greater than zero
    $defaulters = $em->createQuery(
        'SELECT i FROM App\Entity\FeeInvoice i 
         JOIN i.student s 
         WHERE i.balanceAmount > 0 
         ORDER BY i.balanceAmount DESC'
    )->getResult();

    return $this->render('admin/report/defaulter.html.twig', [
        'defaulters' => $defaulters,
    ]);
}
}