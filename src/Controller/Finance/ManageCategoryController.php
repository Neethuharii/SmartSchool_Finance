<?php

namespace App\Controller\Finance;

use App\Entity\FeeCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageCategoryController extends AbstractController
{
#[Route('/finance/categories', name: 'admin_fee_categories', methods: ['GET', 'POST'])]
public function manageCategories(Request $request, EntityManagerInterface $em): Response
{
   
    if ($request->isMethod('POST')) {
        $name = $request->request->get('name');
        $description = $request->request->get('description');

        if ($name) {
            $category = new FeeCategory();
            $category->setName($name);
            $category->setDescription($description);
            $category->setIsActive(true);

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'New Fee Category added successfully!');
        }
        return $this->redirectToRoute('admin_fee_categories');
    }

    // Fetch all categories
    $categories = $em->getRepository(FeeCategory::class)->findAll();

    return $this->render('finance/dashboard/manage_category.html.twig', [
        'categories' => $categories
    ]);
}
// EDIT CATEGORY
#[Route('/finance/categories/edit/{id}', name: 'admin_fee_category_edit', methods: ['POST'])]
public function editCategory(int $id, Request $request, EntityManagerInterface $em): Response
{
    $category = $em->getRepository(FeeCategory::class)->find($id);
    if (!$category) {
        $this->addFlash('danger', 'Category not found.');
        return $this->redirectToRoute('admin_fee_categories');
    }

    $category->setName($request->request->get('name'));
    $category->setDescription($request->request->get('description'));
    $category->setIsActive((bool)$request->request->get('is_active'));

    $em->flush();
    $this->addFlash('success', 'Category updated successfully!');
    
    return $this->redirectToRoute('admin_fee_categories');
}

// DELETE CATEGORY
#[Route('/finance/categories/delete/{id}', name: 'admin_fee_category_delete', methods: ['POST'])]
public function deleteCategory(int $id, EntityManagerInterface $em): Response
{
    $category = $em->getRepository(FeeCategory::class)->find($id);
    
    if ($category) {
    
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'Category deleted successfully!');
    }

    return $this->redirectToRoute('admin_fee_categories');
}
}

