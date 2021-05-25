<?php

namespace App\Controller;

use App\Form\SearchFormDishType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("orion/promotions")
 */
class PromotionsController extends AbstractController
{
    /**
     * @Route("/", name="admin_promotions")
     */
    public function index(): Response
    {
        $form = $this->createForm(SearchFormDishType::class);


        return $this->render('promotions/index.html.twig', [
            'controller_name' => 'PromotionsController',
            'form' => $form->createView()
        ]);
    }
}
