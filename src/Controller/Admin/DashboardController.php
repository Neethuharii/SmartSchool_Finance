<?php

namespace App\Controller\Admin;

use App\Repository\StudentRepository;
use App\Repository\FeePaymentRepository;
use App\Repository\ExpenseRepository;
use App\Repository\FeeInvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin', name: 'dashboard')]
    public function index(
        StudentRepository $studentRepository,
        FeePaymentRepository $feePaymentRepository,
        FeeInvoiceRepository $feeInvoiceRepository,
        ExpenseRepository $expenseRepository
    ): Response {

        $totalStudents = $studentRepository->count([]);
        $activeStudents = $studentRepository->count(['status' => 'active']);
      
        $totalIncome = (float) $feePaymentRepository->createQueryBuilder('p')
            ->select('SUM(p.amount)')
            ->getQuery()
            ->getSingleScalarResult();  

        $totalExpenses = (float) $expenseRepository->createQueryBuilder('e')
            ->select('SUM(e.amount)')
            ->getQuery()
            ->getSingleScalarResult();

        $pendingFees = (float) $feeInvoiceRepository->createQueryBuilder('i')
            ->select('SUM(i.balanceAmount)')
            ->getQuery()
            ->getSingleScalarResult();

        $defaulters = (int) $feeInvoiceRepository->createQueryBuilder('i')
            ->select('COUNT(i.id)')
            ->where('i.balanceAmount > 0')
            ->getQuery()
            ->getSingleScalarResult();

        $feePayments = $feePaymentRepository->findBy([], ['id' => 'DESC'], 5);
        $expenses = $expenseRepository->findBy([], ['id' => 'DESC'], 5);

        $transactions = [];

        foreach ($feePayments as $p) {
            $transactions[] = [
                'date'   => $p->getPaidAt(),
               'name'   => 'Fee Payment - ' . ($p->getStudent() ? $p->getStudent()->getFirstName() . ' ' . $p->getStudent()->getLastName() : 'Unknown'),
                'type'   => 'income',
                'amount' => (float) $p->getAmount(),
                'status' => 'paid'
            ];
        }

        foreach ($expenses as $e) {
            $transactions[] = [
                'date'   => $e->getCreatedAt(),
                'name'   => $e->getTitle(),
                'type'   => 'expense',
                'amount' => (float) $e->getAmount(),
                'status' => 'expense'
            ];
        }

       
        usort($transactions, function ($a, $b) {
            $dateA = $a['date'] ?? new \DateTime('@0');
            $dateB = $b['date'] ?? new \DateTime('@0');
            return $dateB <=> $dateA;
        });

        $transactions = array_slice($transactions, 0, 6);

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $incomeData = [12000, 19000, 15000, 25000, 22000, 30000];
        $expenseData = [8000, 12000, 10000, 18000, 15000, 20000];

        return $this->render('admin/dashboard/dashboard.html.twig', [
            'totalStudents'  => $totalStudents,
            'activeStudents' => $activeStudents,
            'totalIncome'    => $totalIncome,
            'totalExpenses'  => $totalExpenses,
            'pendingFees'    => $pendingFees,
            'defaulters'     => $defaulters,
            'transactions'   => $transactions,
            'months'         => $months,
            'incomeData'     => $incomeData,
            'expenseData'    => $expenseData,
        ]);
    }
}