<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager ;
    }

    /**
     * @Route("/", name="admin_promotions" , methods={"GET","POST"})
     *
     * @param Request $request
     * @param PromotionRepository $promotionsRepository
     * @return Response
     */
    public function index(Request $request, PromotionRepository $promotionsRepository) : Response
    {
        return $this->render('promotions/index.html.twig', [
            'controller_name' => 'PromotionController',
            'promotions' => $promotionsRepository->findAll()
        ]);
    }

    /**
     * @Route("/add", name="admin_promotions_add" , methods={"GET","POST"})
     *
     * @param Request $request
     * @param DishRepository $dishRepository
     * @param PromotionRepository $promotionsRepository
     * @return Response
     */
    public function add(Request $request, DishRepository $dishRepository, PromotionRepository $promotionsRepository) : Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $promotion->setStatus(false);
            $this->em->persist($promotion);
            $this->em->flush();

            return $this->redirectToRoute('admin_promotions');
        }
        
        return $this->render('promotions/new.html.twig', [
            'controller_name' => 'PromotionController',
            'form' => $form->createView()
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

        $this->addFlash('success', 'Promoción eliminada correctamente');

        return $this->redirectToRoute('admin_promotions');
    }

    /**
    * @Route("/getDishByName/{criteria}", name="admin_promotion_find_dish_by_name" , methods={"GET"} , options={"expose"=true} )
    *
    * @param Request $request
    * @param DishRepository $dishRepository
    * @return Response
    */
    public function getDishByName(Request $request, DishRepository $dishRepository, string $criteria) : Response
    {
        $dishes = $dishRepository->searchDishByCriteria($criteria);
        if (null !== $dishes) {
            return new Response(json_encode($dishes));
        } else {
            return new Response(json_encode(['no-records']));
        }
    }
}
