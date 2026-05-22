<?php

namespace App\Controller\Admin;

use App\Entity\Subject;
use App\Entity\Syllabus;
use App\Repository\SubjectRepository;
use App\Repository\AcademicClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/academics/subjects')]
class ManageSubjectController extends AbstractController
{
    #[Route('/', name: 'admin_manage_subjects', methods: ['GET', 'POST'])]
    public function index(
        Request $request, 
        SubjectRepository $subjectRepo, 
        AcademicClassRepository $classRepo,
        EntityManagerInterface $entityManager
    ): Response {
        
        if ($request->isMethod('POST')) {
            //  ADD SUBJECT 
            if ($request->request->has('add_subject') && $this->isCsrfTokenValid('add_subject', $request->request->get('_token'))) {
                $class = $classRepo->find($request->request->get('class_id'));
                
                if ($class) {
                    $subject = new Subject();
                    $subject->setName($request->request->get('name'));
                    $subject->setSubjectCode($request->request->get('subject_code'));
                    $subject->setAcademicClass($class);
                    
                    $entityManager->persist($subject);
                    $entityManager->flush();
                    $this->addFlash('success', 'Subject created successfully!');
                }
                return $this->redirectToRoute('admin_manage_subjects');
            }

            // ADD SYLLABUS CHAPTER 
            if ($request->request->has('add_syllabus') && $this->isCsrfTokenValid('add_syllabus', $request->request->get('_token'))) {
                $subject = $subjectRepo->find($request->request->get('subject_id'));
                
                if ($subject) {
                    $syllabus = new Syllabus();
                    $syllabus->setChapterName($request->request->get('chapter_name'));
                    $syllabus->setDescription($request->request->get('description'));
                    $syllabus->setSubject($subject);
                    
                    $entityManager->persist($syllabus);
                    $entityManager->flush();
                    $this->addFlash('success', 'Chapter added to syllabus!');
                }
                return $this->redirectToRoute('admin_manage_subjects');
            }
        }

        return $this->render('admin/manage_subject/index.html.twig', [
            'subjects' => $subjectRepo->findAll(),
            'classes' => $classRepo->findAll(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_delete_subject', methods: ['POST'])]
    public function deleteSubject(int $id, Request $request, SubjectRepository $repo, EntityManagerInterface $em): Response
    {
        $subject = $repo->find($id);
        if ($subject && $this->isCsrfTokenValid('delete_subject_' . $subject->getId(), $request->request->get('_token'))) {
            $em->remove($subject);
            $em->flush();
            $this->addFlash('success', 'Subject deleted.');
        }
        return $this->redirectToRoute('admin_manage_subjects');
    }
}