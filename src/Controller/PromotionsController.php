<?php

namespace App\Controller;

use App\Form\SearchFormDishType;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("orion/promotions")
 */
class PromotionsController extends AbstractController
{
    /**
     * @Route("/", name="admin_promotions" , methods={"GET","POST"})
     *
     */
    public function index(Request $request, DishRepository $dishRepository): Response
    {
        $form = $this->createForm(SearchFormDishType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $data = $form->get('criteria')->getData();
            $search = $dishRepository->searchDishByCriteria($data);

        }

        return $this->render('promotions/index.html.twig', [
            'controller_name' => 'PromotionsController',
            'form' => $form->createView(),
            'search' => $search ?? []
        ]);
    }
}
