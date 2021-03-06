<?php

namespace App\Controller;

use App\Entity\Chef;
use App\Form\ChefType;
use App\Repository\ChefRepository;
use App\Services\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("orion/chef")
 */
class ChefController extends AbstractController
{
    /**
     * @Route("/", name="admin_chef_index", methods={"GET"})
     */
    public function index(ChefRepository $chefRepository): Response
    {
        return $this->render('chef/index.html.twig', [
            'chefs' => $chefRepository->findAll(),
        ]);
    }

    /**
     * @Route("/nuevo", name="admin_chef_new", methods={"GET","POST"})
     */
    public function new(Request $request, UploadService $uploadService): Response
    {
        $chef = new Chef();
        $form = $this->createForm(ChefType::class, $chef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo')->getData();

            if ($imageFile instanceof UploadedFile) {
                $newImageFile = $uploadService->uploadFile($imageFile, $this->getParameter('chefs_photo_directory'));
                $chef->setImage($newImageFile);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chef);
            $entityManager->flush();

            return $this->redirectToRoute('admin_chef_new');
        }

        return $this->render('chef/new.html.twig', [
            'chef' => $chef,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_chef_show", methods={"GET"})
     */
    public function show(Chef $chef): Response
    {
        return $this->render('chef/show.html.twig', [
            'chef' => $chef,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_chef_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Chef $chef, UploadService $uploadService): Response
    {
        $form = $this->createForm(ChefType::class, $chef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo')->getData();

            if ($imageFile instanceof UploadedFile) {
                $newImageFile = $uploadService->uploadFile($imageFile, $this->getParameter('chefs_photo_directory'));
                $chef->setImage($newImageFile);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chef);
            $entityManager->flush();

            return $this->redirectToRoute('admin_chef_new');
        }

        return $this->render('chef/edit.html.twig', [
            'chef' => $chef,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_chef_delete", methods={"POST"})
     */
    public function delete(Request $request, Chef $chef): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chef->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($chef);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_chef_index');
    }

    /**
     * @Route("/delete/list/{id}", name="admin_chef_delete_by_list", methods={"GET"})
     * @param Request $request
     * @param Chef $chef
     * @return Response
     */
    public function deleteByList(Request $request, Chef $chef): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($chef);
        $entityManager->flush();


        return $this->redirectToRoute('admin_chef_index');
    }
}
