<?php

namespace App\Controller\Finance;

use App\Entity\FeePayment;
use App\Entity\Expense;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LedgerController extends AbstractController
{


#[Route('/finance/expense-ledger', name: 'finance_expense_ledger')]
public function ledger(Request $request, EntityManagerInterface $em): Response
{
    $category = $request->query->get('category');
    $startDate = $request->query->get('start_date');
    $endDate = $request->query->get('end_date');

    $queryBuilder = $em->getRepository(Expense::class)->createQueryBuilder('e')
        ->orderBy('e.createdAt', 'DESC');

    // Filter by Category
    if ($category) {
        $queryBuilder->andWhere('e.category = :cat')->setParameter('cat', $category);
    }

    // Filter by Date Range
    if ($startDate && $endDate) {
        $queryBuilder->andWhere('e.createdAt BETWEEN :start AND :end')
            ->setParameter('start', new \DateTime($startDate))
            ->setParameter('end', new \DateTime($endDate . ' 23:59:59'));
    }

    $expenses = $queryBuilder->getQuery()->getResult();

    // Calculate total for the current view
    $totalOutflow = array_reduce($expenses, fn($sum, $e) => $sum + $e->getAmount(), 0);

    return $this->render('finance/ledger/ledger.html.twig', [
        'expenses' => $expenses,
        'totalOutflow' => $totalOutflow,
        'currentCategory' => $category
    ]);
}
}