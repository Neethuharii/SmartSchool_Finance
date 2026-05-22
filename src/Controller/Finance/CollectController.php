<?php

namespace App\Controller\Finance;

use App\Entity\Student;
use App\Entity\FeePayment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/finance')]
class CollectController extends AbstractController
{
    #[Route('/collect-payment', name: 'finance_collect_payment', methods: ['GET', 'POST'])]
    public function collect(Request $request, EntityManagerInterface $em): Response
    {
  
if ($request->isMethod('POST')) {
    $studentId = $request->request->get('student_id');
    $amountPaid = (float)$request->request->get('amount');
    $method = $request->request->get('method');

    $student = $em->getRepository(Student::class)->find($studentId);
    if (!$student) {
        $this->addFlash('danger', "Student not found.");
        return $this->redirectToRoute('finance_collect_payment');
    }

    //  Record the Payment in the history table
    $payment = new FeePayment();
    $payment->setStudent($student); 
    $payment->setAmount($amountPaid);
    $payment->setMethod($method);
    $payment->setPaidAt(new \DateTimeImmutable());
    $em->persist($payment);

    // Deduct the money from the Invoices
    $remainingPayment = $amountPaid;
    
    // Fetch Unpaid or Partial invoices (Oldest first)
    $invoices = $em->getRepository(\App\Entity\FeeInvoice::class)->findBy(
        ['student' => $student],
        ['createdAt' => 'ASC']
    );

    foreach ($invoices as $invoice) {
        if ($remainingPayment <= 0) break; 
        
        // Skip already paid invoices
        if ($invoice->getStatus() === 'Paid') continue;

        $currentBalance = (float)$invoice->getBalanceAmount();

        if ($remainingPayment >= $currentBalance) {
            // This invoice is now fully PAID
            $remainingPayment -= $currentBalance;
            $invoice->setPaidAmount($invoice->getTotalAmount());
            $invoice->setBalanceAmount(0);
            $invoice->setStatus('Paid');
        } else {
            // This invoice is PARTIALLY paid
            $newPaidTotal = (float)$invoice->getPaidAmount() + $remainingPayment;
            $invoice->setPaidAmount($newPaidTotal);
            $invoice->setBalanceAmount($currentBalance - $remainingPayment);
            $invoice->setStatus('Partial');
            $remainingPayment = 0;
        }
    }

    $em->flush();

    $this->addFlash('success', 'Payment recorded and student balance updated!');
    return $this->redirectToRoute('finance_collect_payment');
}

        $students = $em->getRepository(Student::class)->findAll();
        return $this->render('finance/collect/index.html.twig', [
            'students' => $students
        ]);
    }

  #[Route('/get-student-data/{id}', name: 'api_get_student_data', methods: ['GET'])]
public function getStudentData(int $id, EntityManagerInterface $em): JsonResponse
{
    $student = $em->getRepository(Student::class)->find($id);
    
    if (!$student) {
        return new JsonResponse(['error' => 'Student not found'], 404);
    }

    $totalDue = 0;

    
    if (method_exists($student, 'getFeeInvoices')) {
        foreach ($student->getFeeInvoices() as $invoice) {
            if ($invoice->getStatus() !== 'Paid') {
                $totalDue += (float)$invoice->getBalanceAmount();
            }
        }
    } else {
        
        $invoices = $em->getRepository(\App\Entity\FeeInvoice::class)->findBy([
            'student' => $student
        ]);
        foreach ($invoices as $invoice) {
            if ($invoice->getStatus() !== 'Paid') {
                $totalDue += (float)$invoice->getBalanceAmount();
            }
        }
    }

    return new JsonResponse([
        'baseFee'       => number_format($totalDue, 2, '.', ''),
        'discountName'  => 'Standard',
        'discountValue' => '0.00',
        'totalDue'      => number_format($totalDue, 2, '.', ''),
    ]);
}
}