<?php

namespace App\Controller\Admin;

use App\Entity\AcademicClass;
use App\Entity\Section;
use App\Repository\AcademicClassRepository;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/academics/classes')]
class ClassSectionController extends AbstractController
{
    // ==========================================
    // VIEW ALL, ADD CLASS, & ADD SECTION
    // ==========================================
   
    #[Route('/', name: 'admin_manage_classes', methods: ['GET', 'POST'])]
    public function index(
        Request $request, 
        AcademicClassRepository $classRepo, 
        EntityManagerInterface $entityManager
    ): Response {
        
        if ($request->isMethod('POST')) {
           
            if ($request->request->has('add_class') && $this->isCsrfTokenValid('add_class', $request->request->get('_token'))) {
                $class = new AcademicClass();
                $class->setName($request->request->get('class_name'));
                
                $entityManager->persist($class);
                $entityManager->flush();
                
                $this->addFlash('success', 'Class created successfully!');
                return $this->redirectToRoute('admin_manage_classes');
            }

           
            if ($request->request->has('add_section') && $this->isCsrfTokenValid('add_section', $request->request->get('_token'))) {
                $classId = $request->request->get('class_id');
                $academicClass = $classRepo->find($classId);

                if ($academicClass) {
                    $section = new Section();
                    $section->setName($request->request->get('section_name'));
                    $section->setAcademicClass($academicClass);
                    
                    $entityManager->persist($section);
                    $entityManager->flush();
                    
                    $this->addFlash('success', 'Section added to ' . $academicClass->getName() . '!');
                } else {
                    $this->addFlash('danger', 'Class not found.');
                }
                return $this->redirectToRoute('admin_manage_classes');
            }
        }

      
        $classes = $classRepo->findBy([], ['id' => 'ASC']);

        return $this->render('admin/class_section/classes.html.twig', [
            'classes' => $classes,
        ]);
    }

    // =============================
    // DELETE CLASS
    // =============================
    // 
    #[Route('/{id}/delete', name: 'admin_delete_class', methods: ['POST'])]
    public function deleteClass(
        int $id,
        Request $request, 
        AcademicClassRepository $classRepo, 
        EntityManagerInterface $entityManager
    ): Response {
        $class = $classRepo->find($id);
        
        if ($class && $this->isCsrfTokenValid('delete_class_' . $class->getId(), $request->request->get('_token'))) {
            $entityManager->remove($class);
            $entityManager->flush();
            $this->addFlash('success', 'Class and all its sections were deleted.');
        }

        return $this->redirectToRoute('admin_manage_classes');
    }

    // ==========================================
    // DELETE SECTION
    // ==========================================
    #[Route('/section/{id}/delete', name: 'admin_delete_section', methods: ['POST'])]
    public function deleteSection(
        int $id,
        Request $request, 
        SectionRepository $sectionRepo, 
        EntityManagerInterface $entityManager
    ): Response {
        $section = $sectionRepo->find($id);
        
        if ($section && $this->isCsrfTokenValid('delete_section_' . $section->getId(), $request->request->get('_token'))) {
            $entityManager->remove($section);
            $entityManager->flush();
            $this->addFlash('success', 'Section removed successfully.');
        }

        return $this->redirectToRoute('admin_manage_classes');
    }
}