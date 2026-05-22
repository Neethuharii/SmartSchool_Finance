<?php

namespace App\Controller\Teacher;

use App\Entity\SalarySlip;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaxStatementController extends AbstractController
{
   

#[Route('/teacher/tax-statement', name: 'teacher_tax_statement')]
public function taxStatement(EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    $teacher = $em->getRepository(Teacher::class)->findOneBy(['user' => $user]);
    $year = "2026"; 

    // Fetch all slips for the current year
    $slips = $em->getRepository(SalarySlip::class)->findBy(['teacher_id' => $teacher]);
    
    $totalGross = 0;
    $totalDeductions = 0;
    
    foreach ($slips as $slip) {
        if (str_contains($slip->getMonth(), $year)) {
            $totalGross += (float)$slip->getBasicPay();
            $totalDeductions += (float)$slip->getDeductions();
        }
    }

    return $this->render('teacher/tax_statement/index.html.twig', [
        'teacher' => $teacher,
        'year' => $year,
        'totalGross' => $totalGross,
        'totalDeductions' => $totalDeductions,
        'totalNet' => $totalGross - $totalDeductions,
        'slips' => $slips
    ]);
}
}