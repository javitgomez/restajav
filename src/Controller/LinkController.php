<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LinkType;
use App\Entity\Link;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("orion/link")
 */
class LinkController extends AbstractController
{
    private $em ;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="admin_links")
     */
    public function index(Request $request, UrlGeneratorInterface $urlGenerator, SluggerInterface $slugger): Response
    {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $route = $urlGenerator->generate($link->getHref());
            if (null !== $link->getMarker()) {
                $route .= '#'. $link->getMarker();
            }
            $link->setSlug($slugger->slug($link->getName()));
            $link->setHref($route);
            $this->em->persist($link);
            $this->em->flush();

            $this->addFlash('success', 'Datos actualizados correctamente');

            return $this->redirectToRoute('admin_links');
        }

        return $this->render('link/index.html.twig', [
            'controller_name' => 'LinkController',
            'form' => $form->createView()
        ]);
    }
}
