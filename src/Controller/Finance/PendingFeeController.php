<?php



namespace App\Controller\Finance;

use App\Entity\FeeInvoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PendingFeeController extends AbstractController
{
    #[Route('/finance/pending-fees', name: 'finance_pending_fees')]
    public function index(EntityManagerInterface $em): Response
    {
        $invoiceRepo = $em->getRepository(FeeInvoice::class);

     
        $pendingInvoices = $invoiceRepo->createQueryBuilder('i')
            ->where('i.status != :paid')
            ->setParameter('paid', 'PAID')
           
            ->getQuery()
            ->getResult();

        return $this->render('finance/pending_fee/index.html.twig', [
            'invoices' => $pendingInvoices
        ]);
    }
}