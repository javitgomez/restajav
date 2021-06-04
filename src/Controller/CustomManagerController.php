<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CustomManagerType;
use App\Entity\CustomManager;
use App\Repository\CustomManagerRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Services\UploadService;

/**
* @Route("orion/manager")
*/
class CustomManagerController extends AbstractController
{
    private $em ;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="custom_manager")
     */
    public function index(Request $request, CustomManagerRepository $customManagerRepository, UploadService $uploadService): Response
    {
        $customManager = $customManagerRepository->find(1);

        $form = $this->createForm(CustomManagerType::class, $customManager);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $request->files->get('custom_manager')['photo'];

            if ($photoFile instanceof UploadedFile) {
                $newImageFile = $uploadService->uploadFile($photoFile, $this->getParameter('manager'));
                $customManager->setPhotoDescription($newImageFile);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customManager);
            $entityManager->flush();

            $this->addFlash('success', 'Configuración actualizada correctamente');

            return $this->redirectToRoute('custom_manager');
        }

        return $this->render('custom_manager/index.html.twig', [
            'controller_name' => 'CustomManagerController',
            'form' => $form->createView()
        ]);
    }
}
