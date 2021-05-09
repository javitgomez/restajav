<?php

namespace App\Controller;

use App\Form\DishType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("orion/dish")
 */
class DishController extends AbstractController
{
    /**
     * @Route("/new", options={"expose"=true}, name="admin_dish_new")
     * @throws \Exception
     */
    public function index(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $form = $this->createForm(DishType::class);

            return $this->render('dish/_form_ajax.html.twig', [
                    'form' => $form->createView()
                ]);
        }
        throw new Exception('this request is not allowed here');
    }

    /**
     * @Route("/upload", options={"expose"=true}, name="admin_dish_upload" , methods={"POST"}  )
     * @throws \Exception
     */
    public function upload(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['msg' => 'this ok']);
        }
        throw new Exception('this request is not allowed here');
    }
}
