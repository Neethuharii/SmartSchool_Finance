<?php

namespace App\Controller\Finance;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 

class BankAccountController extends AbstractController
{
    #[Route('/finance/banks', name: 'finance_banks')]
    public function index(EntityManagerInterface $em): Response
    {
        
        $accounts = [
            [
                'id' => 1,
                'name' => 'Main Operating Account',
                'bank' => 'HDFC Bank',
                'account_no' => '**** 8890',
                'balance' => 845000.00,
                'status' => 'Active',
                'color' => '#4318FF'
            ],
            [
                'id' => 2,
                'name' => 'Staff Salary Fund',
                'bank' => 'ICICI Bank',
                'account_no' => '**** 1122',
                'balance' => 125000.50,
                'status' => 'Active',
                'color' => '#05CD99'
            ]
        ];

        return $this->render('finance/bank_account/index.html.twig', [
            'accounts' => $accounts
        ]);
    }
}