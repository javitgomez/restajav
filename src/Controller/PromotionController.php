<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\HiddenInputPromotionType;
use App\Form\SearchFormDishType;
use App\Form\SearchCategoryFormType;
use App\Repository\DishRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("orion/promotion")
 */
class PromotionController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager){
        $this->em = $entityManager ;
    }

    /**
     * @Route("/", name="admin_promotions" , methods={"GET","POST"})
     *
     * @param Request $request
     * @param DishRepository $dishRepository
     * @param PromotionRepository $promotionsRepository
     * @return Response
     */
    public function index(Request $request, DishRepository $dishRepository , PromotionRepository $promotionsRepository) : Response
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
            $promotion->setStatus(false);
            $this->em->persist($promotion);
            $this->em->flush();

            $this->redirectToRoute('admin_promotions');


        }

        return $this->render('promotions/index.html.twig', [
            'controller_name' => 'PromotionController',
            'formDishes' => $formDishes->createView(),
            'search' => $search ?? [],
            'formCategories' => $formCategories,
            'formHiddenInputs' => $formHiddenInputs->createView(),
            'promotions' => $promotionsRepository->findAll()
        ]);
    }

    /**
     * @Route("/activate/{id}", name="admin_promotions_activate")
     * @ParamConverter("promotion", class="App\Entity\Promotion")
     */
    public function activatePromotion(Promotion $promotion) : RedirectResponse
    {
        $promotion->setStatus(!($promotion->getStatus()));

        $this->em->persist($promotion);
        $this->em->flush();

        return $this->redirectToRoute('admin_promotions');

    }

    /**
     * @Route("/show/{id}", name="admin_promotions_show")
     * @ParamConverter("promotion", class="App\Entity\Promotion")
     */
    public function showPromotion(Promotion $promotion) : Response
    {
        return $this->render('promotions/show.html.twig', [
            'controller_name' => 'PromotionController',
            'promotion' => $promotion
        ]);

    }

    /**
     * @Route("/delete/{id}", name="admin_promotions_delete")
     * @ParamConverter("promotion", class="App\Entity\Promotion")
     */
    public function deletePromotion(Promotion $promotion) : Response
    {

        $this->em->remove($promotion);
        $this->em->flush();

        $this->addFlash('success','Promoción eliminada correctamente');

        return $this->redirectToRoute('admin_promotions');

    }
}
