<?php


namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @return Response
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('front/index.html.twig', [
            'events' => $eventRepository->findAll()
        ]);
    }
}
