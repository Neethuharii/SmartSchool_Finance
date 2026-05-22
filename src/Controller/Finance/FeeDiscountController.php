<?php

namespace App\Controller\Finance;

use App\Entity\FeeDiscount;
use App\Entity\Student; // Added
use App\Entity\StudentDiscount; // The new bridge entity
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/finance/discounts')]
class FeeDiscountController extends AbstractController
{
    #[Route('/', name: 'admin_fee_discounts', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        
        $students = $em->getRepository(Student::class)->findAll();
        $discounts = $em->getRepository(FeeDiscount::class)->findAll();
        $assignments = $em->getRepository(StudentDiscount::class)->findAll();

        
        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');

           
            if ($action === 'create_rule') {
                $name = $request->request->get('name');
                $type = $request->request->get('type');
                $value = (float)$request->request->get('value');
                $description = $request->request->get('description');

                if ($name && $value > 0) {
                    $discount = new FeeDiscount();
                    $discount->setName($name);
                    $discount->setType($type);
                    $discount->setValue($value);
                    $discount->setDescription($description);
                    $discount->setIsActive(true);
                    $em->persist($discount);
                    $em->flush();
                    $this->addFlash('success', "Rule '$name' created!");
                }
            } 
            
          
            elseif ($action === 'assign_student') {
                $studentId = $request->request->get('student_id');
                $discountId = $request->request->get('discount_id');

                $student = $em->getRepository(Student::class)->find($studentId);
                $discount = $em->getRepository(FeeDiscount::class)->find($discountId);

                if ($student && $discount) {
                    $assign = new StudentDiscount();
                    $assign->setStudent($student);
                    $assign->setDiscount($discount);
                    $assign->setAssignedAt(new \DateTimeImmutable());
                    $assign->setStatus('Active');
                    
                    $em->persist($assign);
                    $em->flush();
                    $this->addFlash('success', "Assigned {$discount->getName()} to {$student->getFirstName()}");
                }
            }

            return $this->redirectToRoute('admin_fee_discounts');
        }

        return $this->render('finance/fee_discount/index.html.twig', [
            'discounts' => $discounts,
            'students' => $students,
            'assignments' => $assignments,
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_fee_discount_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $discount = $em->getRepository(FeeDiscount::class)->find($id);
        if ($discount) {
           
            $em->remove($discount);
            $em->flush();
            $this->addFlash('success', 'Discount rule removed.');
        }
        return $this->redirectToRoute('admin_fee_discounts');
    }
}