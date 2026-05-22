<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeeController extends AbstractController
{
    #[Route('/admin/fee', name: 'app_admin_fee')]
    public function index(): Response
    {
        return $this->render('admin/fee/index.html.twig', [
            'controller_name' => 'FeeController',
        ]);
    }
}
