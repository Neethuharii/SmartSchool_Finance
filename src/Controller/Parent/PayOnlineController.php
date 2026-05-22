<?php

namespace App\Controller\Parent;

use App\Entity\Student;
use App\Entity\FeeInvoice;
use App\Entity\FeePayment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PayOnlineController extends AbstractController
{

  
#[Route('/parent/pay-online', name: 'parent_pay_online')]
public function payOnline(EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    
    $students = $em->getRepository(Student::class)->findBy(['parentUser' => $user]);

    if (!$students) {
        $this->addFlash('warning', 'No students linked to your account.');
        return $this->redirectToRoute('parent_dashboard');
    }

   
    $pendingInvoices = $em->getRepository(FeeInvoice::class)->findBy([
        'student' => $students,
        'status' => 'Unpaid' 
    ], ['createdAt' => 'DESC']);

    $totalToPay = 0;
    foreach ($pendingInvoices as $invoice) {
        $totalToPay += $invoice->getBalanceAmount();
    }

    return $this->render('parent/pay_online/index.html.twig', [
        'invoices' => $pendingInvoices,
        'totalBalance' => $totalToPay,
        'students' => $students
    ]);
}
    
    

#[Route('/parent/payment/process', name: 'process_payment_gateway', methods: ['POST'])]
public function processPayment(Request $request, EntityManagerInterface $em): Response
{
   
    $selectedInvoiceIds = $request->request->all('selected_invoices');
    $finalAmount = $request->request->get('final_amount');

    if (empty($selectedInvoiceIds)) {
        $this->addFlash('danger', 'No invoices selected.');
        return $this->redirectToRoute('parent_pay_online');
    }

    
    foreach ($selectedInvoiceIds as $id) {
        $invoice = $em->getRepository(FeeInvoice::class)->find($id);
        
        if ($invoice) {
            $invoice->setStatus('Paid');
            $invoice->setPaidAmount($invoice->getTotalAmount());
            $invoice->setBalanceAmount(0);
           
        }
    }

    $em->flush(); 

return $this->render('parent/pay_online/success_simulated.html.twig', [
    'amount' => $finalAmount,
    'invoiceId' => $selectedInvoiceIds[0], 
    'count' => count($selectedInvoiceIds)
]);
}


    #[Route('/parent/payment/receipt/{id}', name: 'parent_payment_receipt')]
    public function viewReceipt(int $id, EntityManagerInterface $em): Response
    {
        $invoice = $em->getRepository(FeeInvoice::class)->find($id);

        if (!$invoice) {
            throw $this->createNotFoundException('Invoice not found');
        }

        return $this->render('parent/pay_online/receipt.html.twig', [
            'invoice' => $invoice
        ]);
    }

    // pdf download
    #[Route('/parent/payment/receipt/{id}/download', name: 'parent_payment_download')]
    public function downloadReceipt(int $id, EntityManagerInterface $em): Response
    {
        $invoice = $em->getRepository(FeeInvoice::class)->find($id);

        return $this->render('parent/pay_online/receipt_pdf.html.twig', [
            'invoice' => $invoice
        ]);
    }

}