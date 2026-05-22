<?php

namespace App\Controller\Finance;

use App\Entity\FeeInvoice;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeeInvoiceController extends AbstractController
{
//    #[Route('/finance/invoices', name: 'finance_invoices')]
//    public function index(EntityManagerInterface $em): Response
//    {
//        $invoices = $em->getRepository(FeeInvoice::class)
//            ->findBy([], ['id' => 'DESC']);
//
//        return $this->render('finance/fee_invoice/index.html.twig', [
//            'invoices' => $invoices
//        ]);
//    }
//
//    #[Route('/finance/invoices/new', name: 'finance_invoice_new')]
//    public function new(Request $request, EntityManagerInterface $em): Response
//    {
//        if ($request->isMethod('POST')) {
//
//            $invoice = new FeeInvoice();
//
//            $student = $em->getRepository(Student::class)
//                ->find($request->request->get('student_id'));
//
//            $total = $request->request->get('totalAmount');
//
//            $invoice->setStudent($student);
//            $invoice->setTotalAmount($total);
//            $invoice->setPaidAmount(0);
//            $invoice->setStatus('pending');
//            $invoice->setDueDate(new \DateTime($request->request->get('dueDate')));
//
//            $em->persist($invoice);
//            $em->flush();
//
//            return $this->redirectToRoute('finance_invoices');
//        }
//
//        $students = $em->getRepository(Student::class)->findAll();
//
//        return $this->render('finance/fee_invoice/invoice_new.html.twig', [
//            'students' => $students
//        ]);
//    }
}