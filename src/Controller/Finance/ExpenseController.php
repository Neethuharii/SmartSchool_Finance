<?php

namespace App\Controller\Finance;

use App\Entity\Expense; // Ensure this matches your entity name
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/finance/expenses')]
class ExpenseController extends AbstractController
{
    #[Route('/record', name: 'finance_record_expense', methods: ['GET', 'POST'])]
    public function record(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $expense = new Expense();
            $expense->setTitle($request->request->get('title'));
            $expense->setAmount((float)$request->request->get('amount'));
            $expense->setCategory($request->request->get('category'));
            $expense->setCreatedAt(new \DateTimeImmutable());

            $em->persist($expense);
            $em->flush();

            $this->addFlash('success', 'Expense recorded: ' . $expense->getTitle());
            return $this->redirectToRoute('finance_record_expense');
        }

        // Fetch recent expenses to show below the form
        $recentExpenses = $em->getRepository(Expense::class)->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('finance/expense/list.html.twig', [
            'recent_expenses' => $recentExpenses
        ]);
    }
    
    
}