<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Teacher;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/teachers')]
class ManageTeacherController extends AbstractController
{
    #[Route('/', name: 'admin_manage_teachers', methods: ['GET', 'POST'])]
    public function index(Request $request, TeacherRepository $teacherRepo, EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
        
        // Create the User record first
        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setFirstName($request->request->get('first_name'));
        $user->setLastName($request->request->get('last_name'));
        $user->setRoles(['ROLE_TEACHER']);
        $user->setStatus('active');
        
        // Hash the password
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $request->request->get('password')
        );
        $user->setPassword($hashedPassword);
        
        $em->persist($user);

        // Create the Teacher profile and link to the User
        $teacher = new Teacher();
        $teacher->setFirstName($request->request->get('first_name'));
        $teacher->setLastName($request->request->get('last_name'));
        $teacher->setStaffId($request->request->get('staff_id'));
        
        $salary = $request->request->get('base_salary');
        $teacher->setBaseSalary(!empty($salary) ? $salary : 25000);
        $teacher->setStatus('active');
        
        // Link them
        $teacher->setUser($user); 

        $em->persist($teacher);
        $em->flush();

        $this->addFlash('success', 'Teacher account and profile created successfully!');
        return $this->redirectToRoute('admin_manage_teachers');
    }

        return $this->render('admin/manage_teacher/index.html.twig', [
            'teachers' => $teacherRepo->findAll(),
        ]);
    }
    
    // ==========================================
    // EDIT TEACHER
    // ==========================================
    #[Route('/{id}/edit', name: 'admin_edit_teacher', methods: ['GET', 'POST'])]
    public function edit(
        int $id, 
        Request $request, 
        TeacherRepository $teacherRepo, 
        EntityManagerInterface $em
    ): Response {
        $teacher = $teacherRepo->find($id);

        if (!$teacher) {
            $this->addFlash('danger', 'Teacher not found.');
            return $this->redirectToRoute('admin_manage_teachers');
        }

        if ($request->isMethod('POST')) {
            $teacher->setFirstName($request->request->get('first_name'));
            $teacher->setLastName($request->request->get('last_name'));
            $teacher->setStaffId($request->request->get('staff_id'));
            $teacher->setBaseSalary($request->request->get('base_salary'));
            $teacher->setStatus($request->request->get('status', 'active'));

            $em->flush(); // Save changes to existing object

            $this->addFlash('success', 'Teacher profile updated successfully!');
            return $this->redirectToRoute('admin_manage_teachers');
        }

     
        return $this->render('admin/manage_teacher/edit.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    // ==========================================
    // DELETE TEACHER
    // ==========================================
    #[Route('/{id}/delete', name: 'admin_delete_teacher', methods: ['POST'])]
    public function delete(
        int $id, 
        Request $request, 
        TeacherRepository $teacherRepo, 
        EntityManagerInterface $em
    ): Response {
        $teacher = $teacherRepo->find($id);

        if (!$teacher) {
            $this->addFlash('danger', 'Teacher not found.');
            return $this->redirectToRoute('admin_manage_teachers');
        }

        // Verify CSRF token for security
        if ($this->isCsrfTokenValid('delete_teacher_' . $teacher->getId(), $request->request->get('_token'))) {
            $em->remove($teacher);
            $em->flush();
            $this->addFlash('success', 'Teacher removed from registry.');
        } else {
            $this->addFlash('danger', 'Invalid security token.');
        }

        return $this->redirectToRoute('admin_manage_teachers');
    }
}