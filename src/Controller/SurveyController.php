<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\User;
use App\Form\SurveyFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\CustomManagerRepository;
use App\Entity\Link;
use App\Entity\Testimonial;
use App\Repository\SurveyRepository;

/**
 * @Route("/survey")
 */
class SurveyController extends AbstractController
{
    private $em;

    private $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->em = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/", name="admin_survey_index", methods={"GET"})
     */
    public function index(SurveyRepository $surveyRepository): Response
    {
        return $this->render('survey/index.html.twig', [
            'surveys' => $surveyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/public/{id}", name="admin_survey_public", methods={"GET"})
     * @ParamConverter("survey", class="App\Entity\Survey")
     */
    public function published(Survey $survey): Response
    {
        $survey->setPublic(true);
       
        $testimonial = new Testimonial();
        $testimonial->setName($survey->getUser()->getUserName());
        $testimonial->setEmail($survey->getUser()->getEmail());
        $testimonial->setJob('Freelancer');
        $testimonial->setValoration($survey->getValoration());
        $testimonial->setRate($survey->getSatisfaction());
        $testimonial->setComment($survey->getComment());
        $testimonial->setPublished(true);

        $this->em->persist($survey);
        $this->em->persist($testimonial);
        $this->em->flush();

        return $this->redirectToRoute('admin_survey_index');
    }

    /**
     * @Route("/delete/{id}", name="admin_survey_delete", methods={"GET"})
     * @ParamConverter("survey", class="App\Entity\Survey")
     */
    public function delete(Survey $survey): Response
    {
        $this->em->remove($survey);
        $this->em->flush();

        return $this->redirectToRoute('admin_survey_index');
    }


    /**
     * @Route("/do/{id}", name="do_survey")
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function doSurvey(Request $request, User $user, CustomManagerRepository $customManagerRepository): Response
    {
        $survey = new Survey();
        $form = $this->createForm(SurveyFormType::class, $survey);

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $survey->setUser($user);
            $this->em->persist($survey);
            $this->em->flush();

            $this->addFlash('success_survey', 'Su valoración ha sido enviada correctamente.');
            $toRoute = $this->urlGenerator->generate('user_index');
            return $this->redirect($toRoute);
        }
        $links['header'] = $this->prepareLinksHeader();
        return $this->render('survey/new.html.twig', [
            'form' => $form->createView(),
            'links' => $links,
            'customManager' => $customManagerRepository->find(1)
        ]);
    }

    private function prepareLinksHeader() : array
    {
        $links = $this->em->getRepository(Link::class)->findAll();
        $menu = [];
        $only = ['home','about','menu','especiales','eventos','chef','galeria','cesta','contacto'];

        if (!empty($links)) {
            foreach ($links as $link) {
                if (in_array($link->getSlug(), $only)) {
                    $menu[$link->getId()] = ['name' => $link->getName(), 'href' => $link->getHref()];
                }
            }
        } else {
            throw new Exception('this menu should be filled before');
        }

        return $menu;
    }
}
