<?php

namespace App\Controller\Finance;

use App\Entity\BankTransaction;
use App\Entity\Expense;
use App\Entity\FeePayment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReconciliationController extends AbstractController
{
    #[Route('/finance/reconciliation', name: 'finance_reconciliation')]
    public function index(EntityManagerInterface $em): Response
    {
       
        $now = new \DateTime();
        $currentMonthLabel = $now->format('F Y'); 
        $firstDayOfMonth = (clone $now)->modify('first day of this month')->setTime(0, 0);
        $lastDayOfMonth = (clone $now)->modify('last day of this month')->setTime(23, 59, 59);

       
        $totalOutgoing = $em->createQueryBuilder()
            ->select('SUM(e.amount)')
            ->from(Expense::class, 'e')
            ->where('e.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $firstDayOfMonth)
            ->setParameter('end', $lastDayOfMonth)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

    
        $totalIncoming = $em->createQueryBuilder()
            ->select('SUM(p.amount)')
            ->from(FeePayment::class, 'p')
            ->where('p.paidAt BETWEEN :start AND :end')
            ->setParameter('start', $firstDayOfMonth)
            ->setParameter('end', $lastDayOfMonth)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        $netBalance = $totalIncoming - $totalOutgoing;
        $isProfitable = $netBalance >= 0;

      
        $transactions = $em->getRepository(BankTransaction::class)->findBy([], ['transactionDate' => 'DESC'], 10);

        return $this->render('finance/reconciliation/index.html.twig', [
            'currentMonth'  => $currentMonthLabel,
            'totalOutgoing' => (float)$totalOutgoing,
            'totalIncoming' => (float)$totalIncoming,
            'netBalance'    => abs($netBalance),
            'isProfitable'  => $isProfitable,
            'transactions'  => $transactions,
            'bankName'      => 'Arbor Finance & Co.',
            'lastSync'      => new \DateTime()
        ]);
    }

    #[Route('/finance/reconciliation/bank-sync/process', name: 'finance_reconciliation_bank_sync_process', methods: ['POST'])]
public function processBankSync(EntityManagerInterface $em): Response
{
    
    $incomingApiData = $this->generateDynamicBankFeed($em);

    foreach ($incomingApiData as $data) {
    
        $exists = $em->getRepository(BankTransaction::class)->findOneBy(['referenceId' => $data['ref']]);
        if ($exists) continue;

        $bankTxn = new BankTransaction();
        $bankTxn->setReferenceId($data['ref']);
        $bankTxn->setTransactionDate($data['date']);
        $bankTxn->setDescription($data['desc']);
        $bankTxn->setAmount($data['amount']);
        $bankTxn->setType($data['type']);

      
        $confidence = 0;
        $matchedRecord = null;

        if ($data['type'] === 'debit') {
            
            $match = $em->getRepository(Expense::class)->findOneBy(['amount' => abs($data['amount'])]);
            if ($match) {
                $matchedRecord = "Expense: " . $match->getTitle();
                $confidence = 100;
            }
        } else {
           
            $match = $em->getRepository(FeePayment::class)->findOneBy(['amount' => $data['amount']]);
            if ($match) {
                $matchedRecord = "Student Payment: " . $match->getStudent()->getFirstName();
                $confidence = 100;
            }
        }

        $bankTxn->setMatchedRecord($matchedRecord);
        $bankTxn->setConfidenceScore($confidence);
        $em->persist($bankTxn);
    }

    $em->flush(); 
    return $this->json(['success' => true]);
}

/**
 * Generates a dynamic feed by looking for recent activity in the system
 * that hasn't been matched to a bank transaction yet.
 */

private function generateDynamicBankFeed(EntityManagerInterface $em): array
{
    $feed = [];

    
    $recentExpenses = $em->getRepository(Expense::class)->findBy(
        [], 
        ['id' => 'DESC'], 
        3
    );

    foreach ($recentExpenses as $expense) {
        $feed[] = [
            'ref'    => 'BANK-EXP-' . $expense->getId() . '-' . strtoupper(bin2hex(random_bytes(2))),
            'date'   => $expense->getCreatedAt() ?? new \DateTime(),
            'desc'   => 'WIRE OUT: ' . strtoupper($expense->getTitle()),
            'amount' => -(float)$expense->getAmount(),
            'type'   => 'debit'
        ];
    }

   
    $recentPayments = $em->getRepository(FeePayment::class)->findBy(
        [], 
        ['id' => 'DESC'], 
        3
    );

    foreach ($recentPayments as $payment) {
        $feed[] = [
            'ref'    => 'STRIPE-PYMT-' . $payment->getId() . '-' . strtoupper(bin2hex(random_bytes(2))),
            'date'   => $payment->getPaidAt() ?? new \DateTime(),
            'desc'   => 'ONLINE PYMT: ' . strtoupper($payment->getStudent()->getLastName()),
            'amount' => (float)$payment->getAmount(),
            'type'   => 'credit'
        ];
    }

   
    if (empty($feed)) {
        $feed[] = [
            'ref'    => 'BANK-INIT-' . date('Ymd'),
            'date'   => new \DateTime(),
            'desc'   => 'INITIAL BANK SYNC CONNECTION TEST',
            'amount' => 0.00,
            'type'   => 'credit'
        ];
    }

  
    shuffle($feed);

    return $feed;
}
}