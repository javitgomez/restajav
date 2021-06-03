<?php


namespace App\Controller;

use App\Entity\Book;
use App\Entity\ContactForm;
use App\Entity\User;
use App\Events\UserRegistrationEvent;
use App\Form\ClientType;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\DishRepository;
use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use App\Repository\TestimonialRepository;
use App\Repository\UserRepository;
use App\Tools\StaticFunctions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @param EventRepository $eventRepository
     * @param ImageRepository $imageRepository
     * @param TestimonialRepository $testimonialRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(
        EventRepository $eventRepository,
        ImageRepository $imageRepository,
        TestimonialRepository $testimonialRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $images = $imageRepository->findAll();
        $testimonials = $testimonialRepository->findBy(["published" => true ]);
        $categories = $categoryRepository->findAll();


        return $this->render('front/index.html.twig', [
            'events' => $eventRepository->findAll(),
            'images' => $images,
            'testimonials' => $testimonials,
            'categories' => $categories
             
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

    /**
     * @Route("/registro", name="user_register", options={"expose"=true} )
     * @param Request $request
     * @return Response
     */
    public function register(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $user = new User();
        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $entityManager = $this->getDoctrine()->getManager();
            $user->setToken(StaticFunctions::generateToken());
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();

            $userRegistrationEvent = new UserRegistrationEvent($user);
            $dispatcher->dispatch($userRegistrationEvent, $userRegistrationEvent::USER_NEW_SIGNUP);

            $this->addFlash('success_register', 'Su cuenta de usuario ha sido creada correctamente');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'front/register.html.twig',
            ['form'=>$form->createView()]
        );
    }
}
