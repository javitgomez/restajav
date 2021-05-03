<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Services\BookManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("orion/book")
 */
class BookController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    /**
     * @Route("/", name="admin_book")
     */
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books
        ]);
    }

    /**
     * @Route("/workflow_canceled/{id}", name="workflow_canceled")
     * @ParamConverter("book", class="App\Entity\Book")
     */
    public function workflowCanceled(BookManager $bookManager, Book $book)
    {
        $bookManager->cancel($book);
        // TODO EVENT HERE FOR CANCEL BOOK
        // SEND EMAIL TO CLIENT
        $this->em->persist($book);
        $this->em->flush();

        return $this->redirectToRoute('admin_book');
    }

    /**
     * @Route("/workflow_answered/{id}", name="workflow_answered")
     * @ParamConverter("book", class="App\Entity\Book")
     */
    public function workflowAnswered(BookManager $bookManager, Book $book)
    {
        $bookManager->answer($book);
        // TODO EVENT HERE FOR CANCEL BOOK
        // SEND EMAIL TO CLIENT
        $this->em->persist($book);
        $this->em->flush();

        return $this->redirectToRoute('admin_book');
    }
}
