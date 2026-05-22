<?php

namespace App\Controller\Finance;

use App\Entity\FeePayment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DailyCollectionController extends AbstractController
{
    #[Route('/finance/daily-collection', name: 'finance_daily_collection')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
     
        $dateParam = $request->query->get('date', (new \DateTime())->format('Y-m-d'));
        $selectedDate = new \DateTime($dateParam);
        
       
        $startOfDay = clone $selectedDate;
        $startOfDay->setTime(0, 0, 0);
        
        $endOfDay = clone $selectedDate;
        $endOfDay->setTime(23, 59, 59);

       
      
$payments = $em->getRepository(FeePayment::class)->createQueryBuilder('p')
    ->where('p.paidAt BETWEEN :start AND :end') 
    ->setParameter('start', $startOfDay)
    ->setParameter('end', $endOfDay)
    ->orderBy('p.paidAt', 'DESC')
    ->getQuery()
    ->getResult();


$totalCollection = 0;
$methodBreakdown = [
    'Cash' => 0,
    'Bank Transfer' => 0,
    'Card/Online' => 0
];

foreach ($payments as $payment) {
    $amount = $payment->getAmount();
    $totalCollection += $amount;
    
   
    $methodName = $payment->getMethod() ?? 'Cash'; 
    if (isset($methodBreakdown[$methodName])) {
        $methodBreakdown[$methodName] += $amount;
    } else {
        $methodBreakdown['Other'] = ($methodBreakdown['Other'] ?? 0) + $amount;
    }
}

        return $this->render('finance/daily_collection/index.html.twig', [
            'selectedDate' => $selectedDate,
            'payments' => $payments,
            'totalCollection' => $totalCollection,
            'methodBreakdown' => $methodBreakdown
        ]);
    }
}