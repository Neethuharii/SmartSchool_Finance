<?php

namespace App\Controller\Admin;

use App\Entity\AcademicYear;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcademicYearController extends AbstractController
{
    #[Route('/admin/academic-year', name: 'academic_year')]
    public function index(EntityManagerInterface $em): Response
    {
        $years = $em->getRepository(AcademicYear::class)->findAll();

        return $this->render('admin/academic_year/academic_year.html.twig', [
            'years' => $years
        ]);
    }

    #[Route('/admin/academic-year/new', name: 'academic_year_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {

            $year = new AcademicYear();

            $year->setName($request->request->get('name'));
            $year->setStartDate(new \DateTimeImmutable($request->request->get('startDate')));
            $year->setEndDate(new \DateTimeImmutable($request->request->get('endDate')));
            $year->setIsActive($request->request->get('isActive') ? true : false);

            $em->persist($year);
            $em->flush();

            return $this->redirectToRoute('academic_year');
        }

        return $this->render('admin/academic_year/academic_year_new.html.twig');
    }
}