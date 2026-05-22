<?php

namespace App\Controller\Parent;

use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/parent/dashboard', name: 'parent_dashboard')]
    public function index(StudentRepository $studentRepo): Response
    {
        $user = $this->getUser();
        $students = [];

      
        if ($this->isGranted('ROLE_PARENT')) {

            $students = $studentRepo->findBy([
                'parentUser' => $user
            ]);

        }

     
        elseif ($this->isGranted('ROLE_STUDENT')) {

            $student = $studentRepo->findOneBy([
                'studentUser' => $user
            ]);

            if ($student) {
                $students[] = $student;
            }
        }


        if (empty($students)) {

            $this->addFlash(
                'warning',
                'No student records associated with this account.'
            );
        }

        return $this->render(
            'parent/dashboard/index.html.twig',
            [
                'students' => $students,
                'user' => $user
            ]
        );
    }
}