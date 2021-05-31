<?php

namespace App\Controller;

use App\Form\HiddenInputPromotionType;
use App\Form\SearchFormDishType;
use App\Form\SearchCategoryFormType;
use App\Repository\DishRepository;
use App\Repository\PromotionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("orion/promotions")
 */
class PromotionsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager){
        $this->em = $entityManager ;
    }

    /**
     * @Route("/", name="admin_promotions" , methods={"GET","POST"})
     *
     */
    public function index(Request $request, DishRepository $dishRepository , PromotionsRepository $promotionsRepository): Response
    {
        $formDishes = $this->createForm(SearchFormDishType::class);

        $formCategories = $this->createForm(SearchCategoryFormType::class)->createView();
        $formHiddenInputs = $this->createForm(HiddenInputPromotionType::class);

        $formDishes->handleRequest($request);
        if($formDishes->isSubmitted() && $formDishes->isValid()){
            $data = $formDishes->get('criteria')->getData();
            $search = $dishRepository->searchDishByCriteria($data);
        }

        $formHiddenInputs->handleRequest($request);
        if($formHiddenInputs->isSubmitted() && $formHiddenInputs->isValid()){
            $promotion = $formHiddenInputs->getData();
            $this->em->persist($promotion);
            $this->em->flush();
            $formHiddenInputs = $this->createForm(HiddenInputPromotionType::class);
        }

        return $this->render('promotions/index.html.twig', [
            'controller_name' => 'PromotionsController',
            'formDishes' => $formDishes->createView(),
            'search' => $search ?? [],
            'formCategories' => $formCategories,
            'formHiddenInputs' => $formHiddenInputs->createView(),
            'promotions' => $promotionsRepository->findAll()
        ]);
    }
}
