<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use App\Hydrators\DishHydrator;
use App\Repository\CategoryRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("orion/dish")
 */
class DishController extends AbstractController
{
    /**
     * @Route("/new/{idCategory}", options={"expose"=true}, name="admin_dish_new" , methods={"GET"})
     * @throws \Exception
     */
    public function index(Request $request, $idCategory): Response
    {
        if ($request->isXmlHttpRequest()) {
            $form = $this->createForm(DishType::class, [$idCategory]);

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
    public function upload(Request $request, CategoryRepository $categoryRepository, SluggerInterface $slugger): Response
    {
        if ($request->isXmlHttpRequest()) {
            $dish = (new DishHydrator($categoryRepository, $request->request->get('dish')))->getObject();
            $file = $request->files->get('dish')['photo'];

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('dish_photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $dish->setPhoto($newFilename);
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
}
