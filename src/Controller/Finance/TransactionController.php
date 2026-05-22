<?php

namespace App\Controller\Finance;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Expense;
use App\Entity\FeePayment;
use App\Entity\Student;

class TransactionController extends AbstractController
{
    #[Route('/finance/transactions', name: 'finance_transactions')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $type = $request->query->get('type');

        $payments = [];
        $expenses = [];

        
        if (!$type || $type === 'income') {
            $payments = $em->getRepository(FeePayment::class)
                ->findBy([], ['paidAt' => 'DESC']);
        }

        
        if (!$type || $type === 'expense') {
            $expenses = $em->getRepository(Expense::class)
                ->findBy([], ['createdAt' => 'DESC']);
        }

        $transactions = [];

       
        foreach ($payments as $p) {
            $transactions[] = [
                'id' => $p->getId(),
                'date' => $p->getPaidAt(),
                'title' => $p->getStudent() ? $p->getStudent()->getFullName() : 'Direct Payment',
                'category' => 'Fee Collection',
                'method' => $p->getMethod(),
                'amount' => $p->getAmount(),
                'type' => 'INCOME',
                'color' => 'success'
            ];
        }

       
        foreach ($expenses as $e) {
            $transactions[] = [
                'id' => $e->getId(),
                'date' => $e->getCreatedAt(), 
                'title' => $e->getTitle(),
                'category' => $e->getCategory(),
                'method' => 'Cash/Bank',
                'amount' => $e->getAmount(),
                'type' => 'EXPENSE',
                'color' => 'danger'
            ];
        }

        usort($transactions, fn($a, $b) => $b['date'] <=> $a['date']);

   
return $this->render('finance/transaction/index.html.twig', [
    'transactions' => $transactions,
    'students' => $em->getRepository(Student::class)->findAll() 
]);
    }
#[Route('/finance/transaction/quick-add', name: 'finance_transaction_quick_add', methods: ['GET', 'POST'])]
    public function quickAdd(Request $request, EntityManagerInterface $em): Response
    {
        $type = $request->request->get('type');
        $amount = (float) $request->request->get('amount');
        $title = $request->request->get('title');

        if ($type === 'EXPENSE') {

    $expense = new Expense();
    $expense->setTitle($title);
    $expense->setAmount($amount);
    $expense->setCategory('General');
  $expense->setCreatedAt(new \DateTimeImmutable());
    

    $em->persist($expense);

} else {
$studentId = $request->request->get('student_id');

$student = null;

if (!empty($studentId)) {
    $student = $em->getRepository(Student::class)->find($studentId);
}

    if (!$student) {
        $this->addFlash('error', 'Student required for payment');
        return $this->redirectToRoute('finance_transactions');
    }

    $payment = new FeePayment();
    $payment->setStudent($student); 
    $payment->setAmount($amount);
    $payment->setMethod($request->request->get('method') ?? 'Cash');
    $payment->setPaidAt(new \DateTimeImmutable());

    $em->persist($payment);
}

        $em->flush();

        $this->addFlash('success', 'Transaction recorded successfully!');

        return $this->redirectToRoute('finance_transactions');
    }
}