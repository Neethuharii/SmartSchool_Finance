<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use App\Repository\StudentRepository;
use App\Repository\AcademicClassRepository;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/students')]
class ManageStudentController extends AbstractController
{
#[Route('/', name: 'admin_manage_students', methods: ['GET', 'POST'])]
public function index(
Request $request,
 StudentRepository $studentRepository,
 AcademicClassRepository $classRepository,
 SectionRepository $sectionRepository,
 EntityManagerInterface $entityManager
): Response {


if ($request->isMethod('POST') && $request->request->get('_token')) {
$token = $request->request->get('_token');
if ($this->isCsrfTokenValid('add_student', $token)) {
$student = new Student();
$student->setAdmissionNo($request->request->get('admission_no'));
$student->setFirstName($request->request->get('first_name'));
$student->setLastName($request->request->get('last_name'));
$student->setContactPhone($request->request->get('contact_phone'));
$student->setContactEmail($request->request->get('contact_email'));
$student->setStatus($request->request->get('status', 'active'));

$sectionId = $request->request->get('class_and_section_id');
if ($sectionId) {
$section = $sectionRepository->find($sectionId);
if ($section) {
$student->setSection($section);
$student->setAcademicClass($section->getAcademicClass());
}
}

$entityManager->persist($student); //object should be saved into the database.
$entityManager->flush(); //Execute query in database
$this->addFlash('success', 'Student added successfully!'); //Stores a temporary success message in session.
return $this->redirectToRoute('admin_manage_students');
}
}

// Fetch students
$students = $studentRepository->findBy([], ['id' => 'DESC']);
$classes = $classRepository->findAll();

return $this->render('admin/manage_student/index.html.twig', [
'students' => $students,
 'classes' => $classes,
]);
}

#[Route('/edit/{id}', name: 'admin_edit_student', methods: ['POST'])]
public function edit(int $id, Request $request, StudentRepository $studentRepository, SectionRepository $sectionRepository, EntityManagerInterface $entityManager): Response
{
$student = $studentRepository->find($id);
if (!$student) {
$this->addFlash('danger', 'Student not found.');
return $this->redirectToRoute('admin_manage_students');
}

if ($this->isCsrfTokenValid('edit_student_' . $student->getId(), $request->request->get('_token'))) {
$student->setAdmissionNo($request->request->get('admission_no'));
$student->setFirstName($request->request->get('first_name'));
$student->setLastName($request->request->get('last_name'));

$student->setContactPhone($request->request->get('contact_phone') ?? '');
$student->setContactEmail($request->request->get('contact_email') ?? '');
$student->setStatus($request->request->get('status', 'active'));

$sectionId = $request->request->get('class_and_section_id');
if ($sectionId) {
$section = $sectionRepository->find($sectionId);
if ($section) {
$student->setSection($section);
$student->setAcademicClass($section->getAcademicClass());
}
}
$entityManager->flush();
$this->addFlash('success', 'Student updated successfully!');
}

return $this->redirectToRoute('admin_manage_students');
}

#[Route('/delete/{id}', name: 'admin_delete_student', methods: ['POST'])]
public function delete(int $id, Request $request, StudentRepository $studentRepository, EntityManagerInterface $entityManager): Response
{
$student = $studentRepository->find($id);

if ($student && $this->isCsrfTokenValid('delete_student_' . $student->getId(), $request->request->get('_token'))) {


$feeInvoiceRepo = $entityManager->getRepository(\App\Entity\FeeInvoice::class);
$invoices = $feeInvoiceRepo->findBy(['student' => $student]);


foreach ($invoices as $invoice) {
$entityManager->remove($invoice);
}


$entityManager->remove($student);


$entityManager->flush();

$this->addFlash('success', 'Student and their billing history deleted successfully.');
} else {
$this->addFlash('danger', 'Unable to delete student. Invalid token or record not found.');
}

return $this->redirectToRoute('admin_manage_students');
}
}