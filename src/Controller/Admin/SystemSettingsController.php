<?php

namespace App\Controller\Admin;

use App\Entity\SystemSettings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin/settings')]
class SystemSettingsController extends AbstractController
{
    
     #[Route('/', name: 'admin_settings', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Fetch the first record or create a new one if empty
        $settings = $em->getRepository(SystemSettings::class)->findOneBy([]) ?? new SystemSettings();

        if ($request->isMethod('POST')) {
            $settings->setSchoolName($request->request->get('school_name'));
            $settings->setCurrency($request->request->get('currency'));
            $settings->setTaxPercentage((float)$request->request->get('tax_percentage'));
            $settings->setLateFeeAmount($request->request->get('late_fee'));
            $settings->setReceiptPrefix($request->request->get('receipt_prefix'));
            $settings->setInvoicePrefix($request->request->get('invoice_prefix'));
            $settings->setAddress($request->request->get('address'));
            $settings->setEmailFrom($request->request->get('email_from'));
            $settings->setPhone($request->request->get('phone'));
            
          
            $settings->setLogo($request->request->get('logo_name', 'logo.png'));
            
            $settings->setUpdatedAt(new \DateTime());

            $em->persist($settings);
            $em->flush();

            $this->addFlash('success', 'System configuration updated!');
            return $this->redirectToRoute('admin_settings');
        }

        return $this->render('admin/system_settings/system_settings.html.twig', [
            'settings' => $settings,
        ]);
    }
}