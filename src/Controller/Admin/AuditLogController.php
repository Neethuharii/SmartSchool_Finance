<?php


namespace App\Controller\Admin;

use App\Entity\AuditLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/audit')]
class AuditLogController extends AbstractController
{
    #[Route('/logs', name: 'admin_audit_index')]
    public function index(EntityManagerInterface $em): Response
    {
        // Fetch logs with the user information, most recent first
        $logs = $em->getRepository(AuditLog::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/audit_log/audit_logs.html.twig', [
            'logs' => $logs
        ]);
    }
}