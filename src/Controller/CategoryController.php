<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    /**
     * @Route("/delete/{id}", name="admin_category_delete" , options={"expose"=true} )
     * @ParamConverter("category", class="App\Entity\Category")
     */
    public function delete(EntityManagerInterface $entityManager, Category $category): RedirectResponse
    {
        if ($category) {
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('success', 'Registro eliminado correctamente');

            return $this->redirectToRoute('admin_category');
        }

        throw new NotFoundHttpException('this category not exist');
    }

    /**
     * @Route("/edit/{id}", name="admin_category_edit" )
     * @ParamConverter("category", class="App\Entity\Category")
     */
    public function edit(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('category/edit.html.twig', [
            'controller_name' => 'CategoryController',
            'form' => $form->createView(),
            'category' => $category
        ]);
    }
}
