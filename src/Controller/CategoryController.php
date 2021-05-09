<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("orion/category")
*/
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="admin_category")
     */
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_category');
        }


        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'form' => $form->createView(),
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
