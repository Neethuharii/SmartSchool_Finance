<?php

namespace App\Controller\Teacher;

use App\Entity\ExpenseClaim;
use App\Entity\PettyCash;
use App\Entity\SalarySlip;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherDashboardController extends AbstractController
{
    #[Route('/teacher/dashboard', name: 'teacher_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
       
        $securityUser = $this->getUser();

        if (!$securityUser) {
            throw $this->createAccessDeniedException('Teacher session not found.');
        }

        // -------------------------------------------------
        // FETCH CORRECT TEACHER PROFILE
        // -------------------------------------------------
        $teacherEntity = $em->getRepository(Teacher::class)->findOneBy([
            'user' => $securityUser
        ]);

        if (!$teacherEntity) {
            throw $this->createNotFoundException('Teacher profile not mapped.');
        }

        $teacherId = $teacherEntity->getId();

        // -------------------------------------------------
        // PENDING CLAIMS
        // -------------------------------------------------
        $pendingClaims = $em->getRepository(ExpenseClaim::class)->findBy([
            'teacher_id' => $teacherId,
            'status' => 'Pending Review'
        ]);

        $pendingClaimsAmount = 0;

        foreach ($pendingClaims as $claim) {
            $pendingClaimsAmount += (float)$claim->getAmount();
        }

        // -------------------------------------------------
        // CLASS FUNDS
        // -------------------------------------------------
        $unremittedFunds = $em->getRepository(PettyCash::class)->findBy([
            'teacher_id' => $teacherId,
            'status' => 'Unremitted'
        ]);

        $classFundsAmount = 0;

        foreach ($unremittedFunds as $fund) {
            $classFundsAmount += (float)$fund->getAmount();
        }

        // -------------------------------------------------
        // LATEST PAYROLL
        // -------------------------------------------------
        $latestPayroll = $em->getRepository(SalarySlip::class)->findOneBy(
            ['teacher_id' => $teacherId],
            ['payment_date' => 'DESC']
        );

        $latestSalary = [
            'amount' => $latestPayroll ? (float)$latestPayroll->getNetPay() : 0,
            'status' => $latestPayroll ? $latestPayroll->getStatus() : 'Pending',
            'date' => $latestPayroll
                ? $latestPayroll->getPaymentDate()->format('d M Y')
                : '-'
        ];

        // -------------------------------------------------
        // RECENT ACTIVITY
        // -------------------------------------------------
        $recentActivity = [];

        if ($latestPayroll) {
            $recentActivity[] = [
                'title' => 'Salary Processed',
                'date' => $latestPayroll->getPaymentDate()->format('d M Y'),
                'status' => $latestPayroll->getStatus(),
                'icon' => 'fa-money-bill-wave',
                'color' => 'success'
            ];
        }

        foreach (array_slice($pendingClaims, 0, 3) as $claim) {
            $recentActivity[] = [
                'title' => $claim->getCategory() . ' Claim',
                'date' => $claim->getSubmittedAt()->format('d M Y'),
                'status' => $claim->getStatus(),
                'icon' => 'fa-file-invoice',
                'color' => 'warning'
            ];
        }

        foreach (array_slice($unremittedFunds, 0, 3) as $fund) {
            $recentActivity[] = [
                'title' => $fund->getSourceEvent(),
                'date' => $fund->getCollectedAt()->format('d M Y'),
                'status' => $fund->getStatus(),
                'icon' => 'fa-coins',
                'color' => 'info'
            ];
        }

        // -------------------------------------------------
        // BASE SALARY
        // -------------------------------------------------
        $baseSalary = (float)$teacherEntity->getBaseSalary();

        // -------------------------------------------------
        // RENDER
        // -------------------------------------------------
        return $this->render('teacher/dashboard/index.html.twig', [
            
             'teacher' => $teacherEntity,

            'teacherName' => $teacherEntity->getFirstName() . ' ' . $teacherEntity->getLastName(),

            'currentMonth' => date('F Y'),

            'base_salary' => $baseSalary,

            'latestSalary' => $latestSalary,

            'pendingClaimsAmount' => $pendingClaimsAmount,

            'classFunds' => $classFundsAmount,

            'recentActivity' => $recentActivity
        ]);
    }
}