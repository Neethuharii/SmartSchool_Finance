<?php

namespace App\Controller\Teacher;
use App\Entity\Teacher;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
class ProfileSettingsController extends AbstractController
{
   

#[Route('/teacher/profile', name: 'teacher_profile', methods: ['GET', 'POST'])]
public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
{
    $user = $this->getUser();
    $teacher = $em->getRepository(Teacher::class)->findOneBy(['user' => $user]);

    if ($request->isMethod('POST')) {
        
        $user->setPhone($request->request->get('phone'));
        
       
        $newPassword = $request->request->get('new_password');
        if (!empty($newPassword)) {
            $user->setPassword($hasher->hashPassword($user, $newPassword));
        }

        $em->flush();
        $this->addFlash('success', 'Profile updated successfully!');
        return $this->redirectToRoute('teacher_profile');
    }

    return $this->render('teacher/profile_settings/index.html.twig', [
        'teacher' => $teacher,
    ]);
}
}
