<?php

namespace App\Controller\Admin;

use App\Entity\FeeInvoice;
use App\Entity\Student;
use App\Entity\FeeCategory;
use App\Entity\Section;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/billing')]
class FeeCategoryController extends AbstractController
{
    // ==========================================
    //    GENERATE SINGLE INVOICE
    // ==========================================
   
    #[Route('/invoice/new', name: 'admin_invoice_new', methods: ['GET', 'POST'])]
    public function newInvoice(Request $request, EntityManagerInterface $em): Response
    {
        
        $students = $em->getRepository(Student::class)
            ->createQueryBuilder('s')
            ->leftJoin('s.section', 'sec')
            ->leftJoin('sec.academicClass', 'ac')
            ->addSelect('sec')
            ->addSelect('ac')
            ->getQuery()
            ->getResult();

        // Dynamically grab all categories available
        $categories = $em->getRepository(FeeCategory::class)->findBy(['isActive' => true]);

        if ($request->isMethod('POST')) {
            $studentId = $request->request->get('student_id');
            $categoryId = $request->request->get('category_id');
            $totalAmount = (float)$request->request->get('total_amount');
            
            if (empty($studentId) || empty($categoryId)) {
                $this->addFlash('danger', 'Error: You must select both a Student and a Fee Category.');
                return $this->redirectToRoute('admin_invoice_new');
            }

            $student = $em->getRepository(Student::class)->find($studentId);
            $category = $em->getRepository(FeeCategory::class)->find($categoryId);

            if (!$student) {
                $this->addFlash('danger', "Error: Could not find Student ID {$studentId} in the database.");
                return $this->redirectToRoute('admin_invoice_new');
            }
            if (!$category) {
                $this->addFlash('danger', "Error: You selected Category ID {$categoryId}, but that ID does not exist in your database table!");
                return $this->redirectToRoute('admin_invoice_new');
            }
            if ($totalAmount <= 0) {
                $this->addFlash('danger', "Error: Amount must be greater than zero. You entered: {$totalAmount}");
                return $this->redirectToRoute('admin_invoice_new');
            }

            $invoice = new FeeInvoice();
            $invoice->setStudent($student);
            $invoice->setFeeCategory($category); 
            $invoice->setTotalAmount($totalAmount);
            $invoice->setPaidAmount("0"); 
            $invoice->setBalanceAmount($totalAmount); 
            $invoice->setStatus('Unpaid');
            $invoice->setCreatedAt(new \DateTimeImmutable());

            $em->persist($invoice);
            $em->flush();

            $this->addFlash('success', 'Fee Invoice for $' . number_format($totalAmount, 2) . ' generated successfully for ' . $student->getFirstName() . '.');
            return $this->redirectToRoute('admin_invoice_new'); 
        }

        return $this->render('admin/fee_category/fee_categories.html.twig', [
            'students' => $students,
            'categories' => $categories, 
        ]);
    }

    // ==========================================
    //     VIEW ALL INVOICES 
    // ==========================================
    
    #[Route('/invoices', name: 'admin_invoice_list', methods: ['GET'])]
    public function listInvoices(EntityManagerInterface $em): Response
    {
      
        $invoices = $em->getRepository(FeeInvoice::class)
            ->createQueryBuilder('i')
            ->leftJoin('i.student', 's')
            ->leftJoin('i.feeCategory', 'fc')
            ->addSelect('s')
            ->addSelect('fc')
            ->orderBy('i.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('admin/fee_category/invoice_list.html.twig', [
            'invoices' => $invoices,
        ]);
    }
    
    // ==========================================
    //     GENERATE BULK INVOICES
    // ==========================================
    #[Route('/bulk-invoice', name: 'admin_bulk_invoice', methods: ['GET', 'POST'])]
    public function bulkInvoice(Request $request, EntityManagerInterface $em): Response
    {
        $sections = $em->getRepository(Section::class)->findAll();
        $categories = $em->getRepository(FeeCategory::class)->findBy(['isActive' => true]);

        if ($request->isMethod('POST')) {
            $sectionId = $request->request->get('section_id');
            $categoryId = $request->request->get('category_id');
            $totalAmount = (float)$request->request->get('total_amount');

            if (empty($sectionId) || empty($categoryId) || $totalAmount <= 0) {
                $this->addFlash('danger', 'Error: Please select a Class, a Fee Category, and enter an amount greater than zero.');
                return $this->redirectToRoute('admin_bulk_invoice');
            }

            $section = $em->getRepository(Section::class)->find($sectionId);
            $category = $em->getRepository(FeeCategory::class)->find($categoryId);

            if (!$section || !$category) {
                $this->addFlash('danger', 'Error: Invalid Class or Category selected.');
                return $this->redirectToRoute('admin_bulk_invoice');
            }

            $students = $em->getRepository(Student::class)->findBy(['section' => $section]);

            if (empty($students)) {
                $this->addFlash('warning', 'No students found in this class. No invoices were generated.');
                return $this->redirectToRoute('admin_bulk_invoice');
            }

            $count = 0;
            foreach ($students as $student) {
                $invoice = new FeeInvoice();
                $invoice->setStudent($student);
                $invoice->setFeeCategory($category);
                $invoice->setTotalAmount($totalAmount);
                $invoice->setPaidAmount("0");
                $invoice->setBalanceAmount($totalAmount);
                $invoice->setStatus('Unpaid');
                $invoice->setCreatedAt(new \DateTimeImmutable());

                $em->persist($invoice); 
                $count++;
            }

            $em->flush();

            $className = $section->getAcademicClass()->getName() . ' ' . $section->getName();
            $this->addFlash('success', "Massive Success! {$count} invoices of $" . number_format($totalAmount, 2) . " were instantly generated for {$className}.");
            
            return $this->redirectToRoute('admin_invoice_list');
        }

        return $this->render('admin/fee_category/fee_category_new.html.twig', [
            'sections' => $sections,
            'categories' => $categories,
        ]);
    }
}