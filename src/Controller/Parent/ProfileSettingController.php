<?php

namespace App\Controller\Parent;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileSettingController extends AbstractController
{
    #[Route('/parent/profile', name: 'parent_profile', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
     
$user = $this->getUser(); //
        if ($request->isMethod('POST')) {
            // Update Basic Info
            $user->setFirstName($request->request->get('first_name'));
            $user->setLastName($request->request->get('last_name'));
            $user->setPhone($request->request->get('phone'));

            // Handle Password Change
            $newPassword = $request->request->get('new_password');
            if (!empty($newPassword)) {
                $user->setPassword($hasher->hashPassword($user, $newPassword));
            }

            $em->flush();
            $this->addFlash('success', 'Your profile has been updated.');
            return $this->redirectToRoute('parent_profile');
        }

        return $this->render('parent/profile_setting/index.html.twig', [
            'user' => $user
        ]);
    }
}