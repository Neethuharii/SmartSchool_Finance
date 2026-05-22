<?php

namespace App\Controller\Admin;

use App\Entity\SalarySlip;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/payroll')]
class PayrollController extends AbstractController
{
  
    #[Route('/', name: 'admin_payroll_index')]
    public function index(): Response
    {
        return $this->render('admin/payroll/index.html.twig', [
            'currentMonth' => date('F Y')
        ]);
    }

    #[Route('/process', name: 'admin_payroll_process', methods: ['POST'])]
    public function process(EntityManagerInterface $em): Response
    {
       
        $teachers = $em->getRepository(Teacher::class)->findAll();
        $currentMonth = date('F Y');
        $generatedCount = 0;

        foreach ($teachers as $teacher) {
         
            $existingSlip = $em->getRepository(SalarySlip::class)->findOneBy([
                'teacher_id' => $teacher->getId(),
                'month' => $currentMonth
            ]);

            
            if (!$existingSlip) {
               
            $slip->setTeacherId($teacher->getId());
                
                // Set the relation 
                $slip->setTeacherId($teacher); 
                $slip->setMonth($currentMonth);
                $slip->setPaymentDate(new \DateTime());
                $slip->setStatus('Paid');

                
                $basicPay = 25000.00; 
                $deductions = 450.00; 
                $netPay = $basicPay - $deductions;

                $slip->setBasicPay($basicPay);
                $slip->setDeductions($deductions);
                $slip->setNetPay($netPay);

               
                $em->persist($slip);
                $generatedCount++;
            }
        }

        $em->flush();
 
        $this->addFlash('success', "Payroll complete! Successfully generated {$generatedCount} salary slips for {$currentMonth}.");

        return $this->redirectToRoute('admin_payroll_index');
    }
}