<?php


namespace App\Controller;

use App\Entity\Book;
use App\Entity\ContactForm;
use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use App\Repository\TestimonialRepository;
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
    public function index(EventRepository $eventRepository, ImageRepository $imageRepository, TestimonialRepository $testimonialRepository): Response
    {
        $images = $imageRepository->findAll();
        $testimonials = $testimonialRepository->findBy(["published" => true ]);

        return $this->render('front/index.html.twig', [
            'events' => $eventRepository->findAll(),
             'images' => $images,
             'testimonials' => $testimonials
             
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
        if ($dataUser['name'] == '') {
            return new Response('El nombre no puede estar vacio.');
        }
        if ($dataUser['phone'] == '') {
            return new Response('El teléfono no puede estar vacio.');
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

    /**
     * @Route("/contact", name="user_contact", methods={"POST"})
     */
    public function contact(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $dataUser = $request->request->all();

        if ($dataUser['name'] == '') {
            return new Response('El nombre no puede estar vacio.');
        }
        if ($dataUser['email'] == '') {
            return new Response('El email no puede estar vacio.');
        }
        if ($dataUser['subject'] == '') {
            return new Response('El asunto no puede estar vacio.');
        }
        if ($dataUser['message'] == '') {
            return new Response('El mensaje no puede estar vacio.');
        }

        try {
            $contact = new ContactForm();
            $contact->setName($dataUser['name']);
            $contact->setEmail($dataUser['email']);
            $contact->setSubject($dataUser['subject']);
            $contact->setMessage($dataUser['message']);

            $entityManager->persist($contact);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new Response('KO');
        }

        return new Response('OK');
    }
}
