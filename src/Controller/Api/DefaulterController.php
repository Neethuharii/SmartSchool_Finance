<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaulterController extends AbstractController
{
    #[Route('/api/defaulter', name: 'app_api_defaulter')]
    public function index(): Response
    {
        return $this->render('api/defaulter/index.html.twig', [
            'controller_name' => 'DefaulterController',
        ]);
    }
}
