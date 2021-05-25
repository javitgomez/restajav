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

/**
 * @Route("/survey")
 */
class SurveyController extends AbstractController
{
    private $em;

    private $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager,UrlGeneratorInterface $urlGenerator){
        $this->em = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/do/{id}", name="do_survey")
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function doSurvey(Request $request, User $user): Response
    {
        $survey = new Survey();
        $form = $this->createForm(SurveyFormType::class, $survey);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            /** @var User $user */
            $user = $this->getUser();
            $survey->setUser($user);
            $this->em->persist($survey);
            $this->em->flush();

            $this->addFlash('success_survey', 'Su valoración ha sido enviada correctamente.');
            $toRoute = $this->urlGenerator->generate('user_index');
            return $this->redirect($toRoute);
        }

        return $this->render('survey/index.html.twig',[
            'form' => $form->createView()
        ]);

    }



}
