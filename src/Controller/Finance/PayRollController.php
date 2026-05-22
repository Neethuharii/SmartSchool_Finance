<?php

namespace App\Controller\Finance;

use App\Entity\Teacher;
use App\Entity\Expense;
use App\Entity\SalarySlip;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayRollController extends AbstractController
{
    #[Route('/finance/payroll', name: 'finance_payroll', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $currentMonth = "May 2026"; 

        // =====================================================================
        // SINGLE TEACHER PAYMENT SUBMISSION
        // =====================================================================
        if ($request->isMethod('POST')) {
            $teacherId = $request->request->get('teacher_id');
            $amount = $request->request->get('amount');
            $month = $request->request->get('month');

            $teacher = $em->getRepository(Teacher::class)->find($teacherId);

            if ($teacher) {
                //  Record Expense
                $expense = new Expense();
                $expense->setTitle("Salary Payment: " . $teacher->getFirstName() . " (" . $month . ")");
                $expense->setAmount((float)$amount);
                $expense->setCategory('Salary');
                $expense->setCreatedAt(new \DateTimeImmutable());
                if (method_exists($expense, 'setTeacher')) {
                    $expense->setTeacher($teacher);
                }
                $em->persist($expense);

                // Generate Payslip
                $slip = new SalarySlip();
                $slip->setTeacherId($teacher); 
                $slip->setMonth($month);
                $slip->setPaymentDate(new \DateTime());
                $slip->setStatus('Paid');
                $basicPay = (float)$amount;
                $slip->setBasicPay($basicPay);
                $slip->setDeductions(0); 
                $slip->setNetPay($basicPay);
                $em->persist($slip);

                

                $em->flush();

                $this->addFlash('payment_success', true);
                $this->addFlash('paid_teacher_name', $teacher->getFirstName() . ' ' . $teacher->getLastName());
                
                return $this->redirectToRoute('finance_payroll');
            }
        }

        // =====================================================================
        // 2. FETCH DATA FOR THE VIEW
        // =====================================================================
        $teachers = $em->getRepository(Teacher::class)->findAll();
        
        $expenses = $em->getRepository(Expense::class)->findBy(['category' => 'Salary']);
        $paidTeacherIds = [];
        foreach ($expenses as $exp) {
            if ($exp->getTeacher() && str_contains($exp->getTitle(), $currentMonth)) {
                $paidTeacherIds[] = $exp->getTeacher()->getId();
            }
        }

        return $this->render('finance/pay_roll/index.html.twig', [
            'teachers' => $teachers,
            'paidTeacherIds' => $paidTeacherIds,
            'currentMonth' => $currentMonth
        ]);
    }

    // =========================================================================
    // 3. AUTOMATED BATCH PROCESSING 
    // =========================================================================
    #[Route('/finance/payroll/process-all', name: 'finance_payroll_process_all', methods: ['POST'])]
    public function processAll(EntityManagerInterface $em): Response
    {
        $currentMonth = "May 2026"; 
        $teachers = $em->getRepository(Teacher::class)->findAll();
        $generatedCount = 0;

        foreach ($teachers as $teacher) {
            $existingExpense = $em->getRepository(Expense::class)->createQueryBuilder('e')
                ->where('e.teacher = :teacher')
                ->andWhere('e.title LIKE :month')
                ->setParameter('teacher', $teacher)
                ->setParameter('month', '%' . $currentMonth . '%')
                ->getQuery()
                ->getOneOrNullResult();

            if (!$existingExpense) {
                
                $baseSalary = (float)($teacher->getBaseSalary() ?? 4500.00);

                // Create Expense
                $expense = new Expense();
                $expense->setTitle("Salary Payment: " . $teacher->getFirstName() . " (" . $currentMonth . ")");
                $expense->setAmount($baseSalary);
                $expense->setCategory('Salary');
                $expense->setCreatedAt(new \DateTimeImmutable());
                if (method_exists($expense, 'setTeacher')) {
                    $expense->setTeacher($teacher);
                }
                $em->persist($expense);

                // Create Payslip
                $slip = new SalarySlip();
                $slip->setTeacherId($teacher);
                $slip->setMonth($currentMonth);
                $slip->setPaymentDate(new \DateTime());
                $slip->setStatus('Paid');
                $slip->setBasicPay($baseSalary);
                $slip->setDeductions(0);
                $slip->setNetPay($baseSalary);
                $em->persist($slip);

                // // Create Notification
                // $notification = new Notification();
                // $notification->setTeacher($teacher);
                // $notification->setMessage("Your salary for {$currentMonth} has been credited. View your digital payslip now.");
                // $notification->setIsRead(false);
                // $notification->setCreatedAt(new \DateTime());
                // $em->persist($notification);

                $generatedCount++;
            }
        }

        $em->flush();
        $this->addFlash('batch_success', "Automation Complete! Successfully generated {$generatedCount} payslips & notifications for {$currentMonth}.");
        
        return $this->redirectToRoute('finance_payroll');
    }
}