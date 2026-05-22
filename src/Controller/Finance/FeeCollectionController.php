<?php


namespace App\Controller\Finance;

use App\Entity\FeeInvoice;
use App\Entity\Payment;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeeCollectionController extends AbstractController
{
    #[Route('/finance/fee-collection', name: 'finance_fee_collection')]
    public function collect(Request $request, EntityManagerInterface $em): Response
    {
        $studentRepo = $em->getRepository(Student::class);
        $invoiceRepo = $em->getRepository(FeeInvoice::class);

        $students = $studentRepo->findAll();

        $selectedStudent = null;
        $invoices = [];

        if ($request->isMethod('POST')) {

            $studentId = $request->request->get('student_id');
            $invoiceId = $request->request->get('invoice_id');
            $amount = (float) $request->request->get('amount');

           
            if ($studentId) {
                $selectedStudent = $studentRepo->find($studentId);

                $invoices = $invoiceRepo->findBy([
                    'student' => $selectedStudent
                ]);
            }

          
            if ($invoiceId && $amount > 0) {

                $invoice = $invoiceRepo->find($invoiceId);

                if ($invoice) {

                    $balance = $invoice->getTotalAmount() - $invoice->getPaidAmount();

                   
                    if ($amount > $balance) {
                        $this->addFlash('error', 'Amount exceeds pending balance.');
                    } else {

                        $payment = new Payment();
                        $payment->setInvoice($invoice);
                        $payment->setAmount($amount);
                        $payment->setPaidAt(new \DateTime());

                     
                        $invoice->setPaidAmount($invoice->getPaidAmount() + $amount);

                        if ($invoice->getPaidAmount() >= $invoice->getTotalAmount()) {
                            $invoice->setStatus('PAID');
                        } else {
                            $invoice->setStatus('PARTIAL');
                        }

                        $em->persist($payment);
                        $em->flush();

                        $this->addFlash('success', 'Payment recorded successfully!');

                        return $this->redirectToRoute('finance_fee_collection');
                    }
                }
            }
        }

        return $this->render('finance/fee_collection/fee_collection.html.twig', [
            'students' => $students,
            'selectedStudent' => $selectedStudent,
            'invoices' => $invoices
        ]);
    }
}