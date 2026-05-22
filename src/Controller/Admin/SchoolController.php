<?php

namespace App\Controller\Admin;

use App\Entity\School;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SchoolController extends AbstractController
{
    #[Route('/admin/school-setup', name: 'school_setup')]
    public function index(EntityManagerInterface $em): Response
    {
        $school = $em->getRepository(School::class)->findOneBy([]);

        return $this->render('admin/School/school_setup.html.twig', [
            'school' => $school
        ]);
    }

    #[Route('/admin/school-setup/save', name: 'school_setup_save')]
    public function save(Request $request, EntityManagerInterface $em): Response
    {
        $school = $em->getRepository(School::class)->findOneBy([]);

        if (!$school) {
            $school = new SchoolSetup();
        }

        if ($request->isMethod('POST')) {

            $school->setSchoolName($request->request->get('schoolName'));
            $school->setAddress($request->request->get('address'));
            $school->setPhone($request->request->get('phone'));
            $school->setEmail($request->request->get('email'));
            $school->setCurrency($request->request->get('currency'));
            $school->setIsActive($request->request->get('isActive') ? true : false);

            $em->persist($school);
            $em->flush();

            return $this->redirectToRoute('school_setup');
        }

        return $this->redirectToRoute('school_setup');
    }
}