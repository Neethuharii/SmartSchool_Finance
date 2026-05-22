<?php

namespace App\Controller\Teacher;

use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class BankDetailsController extends AbstractController
{
  

#[Route('/teacher/bank-details', name: 'teacher_bank_details')]
public function index(EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    $teacher = $em->getRepository(Teacher::class)->findOneBy(['user' => $user]);

    if (!$teacher) {
        throw $this->createNotFoundException('Teacher profile not found.');
    }

    return $this->render('teacher/bank_details/index.html.twig', [
        'teacher' => $teacher,
    ]);
}
}
