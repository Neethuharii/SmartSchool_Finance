<?php

namespace App\Controller\Finance;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/finance/reports', name: 'finance_reports')]
    public function reports(EntityManagerInterface $em): Response
    {
        
        $incomeSql = "
            SELECT SUM(amount) as total, MONTH(paid_at) as month
            FROM fee_payment
            GROUP BY MONTH(paid_at)
            ORDER BY month ASC
        ";

        $expenseSql = "
            SELECT SUM(amount) as total, MONTH(created_at) as month
            FROM expense
            GROUP BY MONTH(created_at)
            ORDER BY month ASC
        ";

        $conn = $em->getConnection();

        $incomeData = $conn->executeQuery($incomeSql)->fetchAllAssociative();
        $expenseData = $conn->executeQuery($expenseSql)->fetchAllAssociative();


        $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        // Init arrays
        $revenue = array_fill(0, 12, 0);
        $expenses = array_fill(0, 12, 0);

        foreach ($incomeData as $row) {
            $revenue[$row['month'] - 1] = (float) $row['total'];
        }

        foreach ($expenseData as $row) {
            $expenses[$row['month'] - 1] = (float) $row['total'];
        }

        $totalIncome = array_sum($revenue);
        $totalExpense = array_sum($expenses);

        $expenseRatio = $totalIncome > 0
            ? round(($totalExpense / $totalIncome) * 100, 2) . '%'
            : '0%';

        return $this->render('finance/report/index.html.twig', [
            'monthlyRevenue' => $revenue,
            'monthlyExpenses' => $expenses,
            'labels' => $labels,
            'stats' => [
                'expenseRatio' => $expenseRatio,
                'collectionEfficiency' => '94%'
            ]
        ]);
    }
}