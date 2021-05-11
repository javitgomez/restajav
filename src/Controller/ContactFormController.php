<?php

namespace App\Controller;

use App\Entity\ContactForm;
use App\Form\ContactFormType;
use App\Repository\ContactFormRepository;
use App\Services\ContactFormManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("orion/contact")
 */
class ContactFormController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    /**
     * @Route("/", name="admin_contact_form")
     */
    public function index(ContactFormRepository $contactFormRepository): Response
    {
        $messages = $contactFormRepository->findAll();

        return $this->render('contact_form/index.html.twig', [
            'controller_name' => 'ContactFormController',
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/answer/{id}", name="admin_contact_form_answer")
     * @ParamConverter("contactForm", class="App\Entity\ContactForm")
     */
    public function answer(Request $request, ContactFormManager $contactFormManager, ContactForm $contactForm): Response
    {
        $form = $this->createForm(ContactFormType::class, $contactForm);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormManager->answer($contactForm);

            $this->em->persist($contactForm);
            $this->em->flush();

            $this->addFlash('success', 'Se ha enviado un email al cliente');

            return $this->redirectToRoute('admin_contact_form');
        }

        return $this->render('contact_form/answer.html.twig', [
            'controller_name' => 'ContactFormController',
            'form' => $form->createView(),
            'contactForm' => $contactForm
        ]);
    }
}
