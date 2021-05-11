<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dish;
use App\Form\CategoryType;
use App\Form\DishType;
use App\Form\DishUploadType;
use App\Hydrators\DishHydrator;
use App\Repository\CategoryRepository;
use App\Services\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("orion/dish")
 */
class DishController extends AbstractController
{
    /**
     * @Route("/nueva/{idCategory}", options={"expose"=true}, name="admin_dish_new" , methods={"GET"})
     * @throws \Exception
     */
    public function index(Request $request, $idCategory): Response
    {
        if ($request->isXmlHttpRequest()) {
            $form = $this->createForm(DishUploadType::class, [$idCategory]);

            return $this->render('dish/_form_ajax.html.twig', [
                    'form' => $form->createView(),
            ]);
        }

        throw new Exception('this request is not allowed here');
    }

    /**
     * @Route("/upload", options={"expose"=true}, name="admin_dish_upload" , methods={"POST"}  )
     * @throws \Exception
     */
    public function upload(Request $request, CategoryRepository $categoryRepository, UploadService $uploadService): Response
    {
        if ($request->isXmlHttpRequest()) {
            $dish = (new DishHydrator($categoryRepository, $request->request->get('dish_upload')))->getObject();
            $imageFile = $request->files->get('dish_upload')['photo'];

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded

            if ($imageFile instanceof UploadedFile) {
                $newImageFile = $uploadService->uploadFile($imageFile, $this->getParameter('dish_photo_directory'));
                $dish->setPhoto($newImageFile);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dish);
            $entityManager->flush();

            return $this->redirectToRoute(
                'admin_dish_new',
                ['idCategory' => $dish->getCategory()->getId()]
            );
        }
        throw new Exception('this request is not allowed here');
    }


    /**
     * @Route("/delete/{id}", name="admin_dish_delete" , options={"expose"=true} )
     * @ParamConverter("dish", class="App\Entity\Dish")
     */
    public function delete(EntityManagerInterface $entityManager, Dish $dish) : RedirectResponse
    {
        if ($dish) {
            $entityManager->remove($dish);
            $entityManager->flush();

            $this->addFlash('success', 'Registro eliminado correctamente');

            return $this->redirectToRoute('admin_category_edit', ['id' => $dish->getCategory()->getId()]);
        }

        throw new NotFoundHttpException('this category not exist');
    }

    /**
     * @Route("/edit/{id}", name="admin_dish_edit" )
     * @ParamConverter("dish", class="App\Entity\Dish")
     */
    public function edit(Request $request, UploadService $uploadService, Dish $dish)
    {
        $form = $this->createForm(DishType::class, $dish);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $imageFile = $request->files->get('dish')['photo'];

            if ($imageFile instanceof UploadedFile) {
                $newImageFile = $uploadService->uploadFile($imageFile, $this->getParameter('dish_photo_directory'));
                $dish->setPhoto($newImageFile);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dish);
            $entityManager->flush();

            $this->addFlash('success', 'Registro actualizado correctamente');

            return $this->redirectToRoute('admin_dish_edit', ['id'=> $dish->getId()]);
        }

        return $this->render('dish/edit.html.twig', [
            'controller_name' => 'DishController',
            'form' => $form->createView(),
            'dish' => $dish
        ]);
    }
}
