<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Services\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("orion/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="admin_event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_event_new", methods={"GET","POST"})
     */
    public function new(Request $request, UploadService $uploadService): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $photoFile */
            $imageFile = $form->get('photo')->getData();

            if ($imageFile instanceof UploadedFile) {
                $newImageFile = $uploadService->uploadFile($imageFile, $this->getParameter('events_photo_directory'));
                $event->setImage($newImageFile);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('admin_event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event, UploadService $uploadService): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $imageFile = $form->get('photo')->getData();

            if ($imageFile instanceof UploadedFile) {
                $newImageFile = $uploadService->uploadFile($imageFile, $this->getParameter('events_photo_directory'));
                $event->setImage($newImageFile);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('admin_event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_event_delete", methods={"POST"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_event_index');
    }

    /**
     * @Route("/delete/list/{id}", name="admin_event_delete_by_list", methods={"GET"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function deleteByList(Request $request, Event $event): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();


        return $this->redirectToRoute('admin_event_index');
    }
}
