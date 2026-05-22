<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Student;
use App\Repository\StudentRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/parents')]
class ManageParentController extends AbstractController
{
   #[Route('/', name: 'admin_manage_parents', methods: ['GET', 'POST'])]
    public function index(
        Request $request, 
        UserRepository $userRepository, 
        StudentRepository $studentRepository, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        
        // ==========================================
        //    Add Parent
        // ==========================================
        
        if ($request->isMethod('POST')) {
            $token = $request->request->get('_token');
            
            if ($this->isCsrfTokenValid('add_parent', $token)) {
                
              
                $user = new User();
                $user->setFirstName($request->request->get('first_name'));
                $user->setLastName($request->request->get('last_name'));
                $user->setEmail($request->request->get('email'));
                $user->setPhone($request->request->get('phone'));
                
               $user->setRoles(['ROLE_PARENT']); 
               $user->setStatus($request->request->get('status', 'active'));
                
                
                $hashedPassword = $passwordHasher->hashPassword(
                    $user, 
                    $request->request->get('password')
                );
                $user->setPassword($hashedPassword);

              
                $entityManager->persist($user);
                $entityManager->flush(); 
                

                // --- LINK STUDENTS TO THIS PARENT ---
               
                $selectedStudentIds = $request->request->all('student_ids'); 
                
                if (!empty($selectedStudentIds)) {
                    foreach ($selectedStudentIds as $studentId) {
                        $student = $studentRepository->find($studentId);
                        if ($student) {
                            // Link the student to this parent record
                            $student->setParentUser($user); 
                        }
                    }
                 
                    $entityManager->flush();
                }

                $this->addFlash('success', 'Parent account created and linked to student(s) successfully!');
                return $this->redirectToRoute('admin_manage_parents');
            } else {
                $this->addFlash('danger', 'Security token expired. Please try again.');
            }
        }


     // fetches all users who have the role ROLE_PARENT.
     $parents = $userRepository->createQueryBuilder('u')
    ->where('u.roles LIKE :role')
    ->setParameter('role', '%"ROLE_PARENT"%') 
    ->orderBy('u.id', 'DESC')
    ->getQuery()
    ->getResult();

        // Fetch Students for the Dropdown
        $students = $studentRepository->findBy([], ['first_name' => 'ASC']);

        return $this->render('admin/manage_parent/index.html.twig', [
            'parents' => $parents,
            'students' => $students,
        ]);
    }
    // ==========================================
    // EDIT PARENT
    // ==========================================
    
    #[Route('/{id}/edit', name: 'admin_edit_parent', methods: ['POST'])]
    public function edit(
        int $id,
        Request $request, 
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager
    ): Response {
        
        $user = $userRepository->find($id);
        
        if (!$user) {
            $this->addFlash('danger', 'Parent not found.');
            return $this->redirectToRoute('admin_manage_parents');
        }

        $token = $request->request->get('_token');
        
        if ($this->isCsrfTokenValid('edit_parent_' . $user->getId(), $token)) {
            $user->setFirstName($request->request->get('first_name'));
            $user->setLastName($request->request->get('last_name'));
            $user->setEmail($request->request->get('email'));
            $user->setPhone($request->request->get('phone'));
            $user->setStatus($request->request->get('status', 'active'));

            $entityManager->flush();
            $this->addFlash('success', 'Parent account updated successfully!');
        } else {
            $this->addFlash('danger', 'Invalid security token.');
        }

        return $this->redirectToRoute('admin_manage_parents');
    }

    // ==========================================
    // DELETE PARENT
    // ==========================================
    // 
    #[Route('/{id}/delete', name: 'admin_delete_parent', methods: ['POST'])]
    public function delete(
        int $id,
        Request $request, 
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager
    ): Response {
        
        $user = $userRepository->find($id);
        
        if (!$user) {
            $this->addFlash('danger', 'Parent not found.');
            return $this->redirectToRoute('admin_manage_parents');
        }

        $token = $request->request->get('_token');
        
        if ($this->isCsrfTokenValid('delete_parent_' . $user->getId(), $token)) {
            $entityManager->remove($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Parent account deleted successfully.');
        } else {
            $this->addFlash('danger', 'Invalid security token.');
        }

        return $this->redirectToRoute('admin_manage_parents');
    }
}