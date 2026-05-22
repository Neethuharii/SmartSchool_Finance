<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_portal_hub');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $user = new User();
          
            $user->setFirstName($request->request->get('first_name'));
            $user->setLastName($request->request->get('last_name'));
            $user->setPhone($request->request->get('phone'));
            $user->setEmail($request->request->get('email'));
            $user->setStatus('active');
            $user->setCreatedAt(new \DateTimeImmutable());

            $selectedRole = $request->request->get('role', 'ROLE_PARENT');
            $user->setRoles([$selectedRole]); 

            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $request->request->get('password'))
            );

          
            $em->persist($user);

            
            if ($selectedRole === 'ROLE_TEACHER') {
                $teacherProfile = new Teacher();
                
               
                $teacherProfile->setUser($user); 
                
           
                $teacherProfile->setFirstName($user->getFirstName());
                $teacherProfile->setLastName($user->getLastName());
                
                $teacherProfile->setStatus('active');
                $teacherProfile->setStaffId('TCH-' . rand(1000, 9999));
                
                $em->persist($teacherProfile);
            }

         
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank.');
    }

    #[Route('/portal', name: 'app_portal_hub')]
public function portalHub(): Response
{
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Use isGranted for reliable role checking
    if ($this->isGranted('ROLE_ADMIN')) {
        return $this->redirectToRoute('dashboard');
    }

    if ($this->isGranted('ROLE_ACCOUNTANT')) {
        return $this->redirectToRoute('admin_finance_dashboard');
    }

    if ($this->isGranted('ROLE_TEACHER')) {
        return $this->redirectToRoute('teacher_dashboard');
    }

    if ($this->isGranted('ROLE_PARENT')) {
        return $this->redirectToRoute('parent_dashboard'); 
    }

    throw new \Exception('User ID ' . $user->getUserIdentifier() . ' does not have a portal assigned. Found roles: ' . implode(', ', $user->getRoles()));
}
}