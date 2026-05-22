<?php

namespace App\Controller\Teacher;

use App\Entity\SalarySlip;
use App\Entity\User;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class payslipController extends AbstractController
{
   #[Route('/teacher/payslips', name: 'teacher_payslips')]
public function myPayslips(EntityManagerInterface $em): Response
{
  
    $user = $this->getUser();
    
    $teacher = $em->getRepository(Teacher::class)->findOneBy(['user' => $user]);

    if (!$teacher) {
        throw $this->createNotFoundException('Teacher profile not found.');
    }

$payslips = $em->getRepository(SalarySlip::class)->findBy(
    ['teacher_id' => $teacher],
    ['payment_date' => 'DESC'] 
);
    return $this->render('teacher/payslip/payslip_list.html.twig', [
        'payslips' => $payslips
    ]);
}

#[Route('/teacher/payslip/download/{id}', name: 'teacher_payslip_download')]
public function downloadPayslip(
    int $id,
    EntityManagerInterface $em
): Response {

    $user = $this->getUser();

    $teacher = $em->getRepository(Teacher::class)->findOneBy([
        'user' => $user
    ]);

    if (!$teacher) {
        throw $this->createNotFoundException('Teacher not found.');
    }

    $slip = $em->getRepository(SalarySlip::class)->find($id);

    if (!$slip) {
        throw $this->createNotFoundException('Payslip not found.');
    }

    // SECURITY CHECK
    if ($slip->getTeacherId() !== $teacher) {
        throw $this->createAccessDeniedException();
    }

    return $this->render('teacher/payslip/download.html.twig', [
        'slip' => $slip,
        'teacher' => $teacher
    ]);
}
}