<?php

namespace App\Controller\Finance;
use App\Entity\FeePayment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HistoryController extends AbstractController
{

#[Route('/finance/history', name: 'finance_payment_history')]
public function history(EntityManagerInterface $em): Response
{
    // Fetch payments, ordered by newest first
    $payments = $em->getRepository(FeePayment::class)->findBy([], ['paidAt' => 'DESC']);

    return $this->render('finance/history/index.html.twig', [
        'payments' => $payments,
    ]);
}
}