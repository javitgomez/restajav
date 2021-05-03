<?php


namespace App\Controller;

use App\Entity\Book;
use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository, ImageRepository $imageRepository): Response
    {
        $images = $imageRepository->findAll();

        return $this->render('front/index.html.twig', [
            'events' => $eventRepository->findAll(),
             'images' => $images
        ]);
    }

    /**
     * @Route("/book", name="user_book", methods={"POST"})
     */
    public function book(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $dataUser = $request->request->all();

        if ($dataUser['email'] == '') {
            return new Response('El email no puede estar vacio.');
        }
        try {
            $book = new Book();
            $book->setName($dataUser['name']);
            $book->setDate(new \DateTime());
            $book->setEmail($dataUser['email']);
            $book->setPhone($dataUser['phone']);
            $book->setMessage($dataUser['message']);
            $book->setPeople($dataUser['people']);
            $book->setTime(new \DateTime());

            $entityManager->persist($book);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new Response('KO');
        }

        return new Response('OK');
    }
}
