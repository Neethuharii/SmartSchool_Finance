<?php

namespace App\Controller\Admin;

use App\Entity\User; 
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/staff')]
class UserController extends AbstractController
{
    
    // ==========================================
    // VIEW ALL & ADD STAFF
    // ==========================================
    
    #[Route('/', name: 'admin_manage_staff', methods: ['GET', 'POST'])]
    public function index(
        Request $request, 
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        
        if ($request->isMethod('POST')) {
            $token = $request->request->get('_token');
            
            if ($this->isCsrfTokenValid('add_staff', $token)) {
                $user = new User();
                $user->setFirstName($request->request->get('first_name'));
                $user->setLastName($request->request->get('last_name'));
                $user->setEmail($request->request->get('email'));
                $user->setPhone($request->request->get('phone'));
                $user->setRole($request->request->get('role'));
                $user->setStatus($request->request->get('status', 'active'));
               
                $plainPassword = $request->request->get('password');
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Staff member added successfully!');
                return $this->redirectToRoute('admin_manage_staff');
            } else {
                $this->addFlash('danger', 'Invalid form submission.');
            }
        }

        // --- FETCH ALL USERS ---
       
        $staffMembers = $userRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/user/usermangement.html.twig', [
            'staffMembers' => $staffMembers,
        ]);
    }

    // ==========================================
    //            EDIT STAFF 
    // ==========================================
    
    #[Route('/{id}/edit', name: 'admin_edit_staff', methods: ['POST'])]
    public function edit(
        int $id,
        Request $request, 
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager
    ): Response {
        
        $user = $userRepository->find($id);
        
        if (!$user) {
            $this->addFlash('danger', 'Staff member not found.');
            return $this->redirectToRoute('admin_manage_staff');
        }

        $token = $request->request->get('_token');
        
        if ($this->isCsrfTokenValid('edit_staff_' . $user->getId(), $token)) {
            $user->setFirstName($request->request->get('first_name'));
            $user->setLastName($request->request->get('last_name'));
            $user->setEmail($request->request->get('email'));
            $user->setPhone($request->request->get('phone'));
            $user->setRole($request->request->get('role'));
            $user->setStatus($request->request->get('status', 'active'));

            $entityManager->flush();
            $this->addFlash('success', 'Staff updated successfully!');
        } else {
            $this->addFlash('danger', 'Invalid security token.');
        }

        return $this->redirectToRoute('admin_manage_staff');
    }

    // ==========================================
    // DELETE STAFF
    // ==========================================
    
    #[Route('/{id}/delete', name: 'admin_delete_staff', methods: ['POST'])]
    public function delete(
        int $id,
        Request $request, 
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager
    ): Response {
        
        $user = $userRepository->find($id);
        
        if (!$user) {
            $this->addFlash('danger', 'Staff member not found.');
            return $this->redirectToRoute('admin_manage_staff');
        }

        $token = $request->request->get('_token');
        
        if ($this->isCsrfTokenValid('delete_staff_' . $user->getId(), $token)) {
            $entityManager->remove($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Staff deleted successfully.');
        } else {
            $this->addFlash('danger', 'Invalid security token.');
        }

        return $this->redirectToRoute('admin_manage_staff');
    }
}