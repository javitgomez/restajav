<?php

namespace App\Controller;

use App\Entity\User;
use App\Events\UserRegistrationEvent;
use App\Form\UserResetPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Tools\StaticFunctions;
use Doctrine\Instantiator\Exception\UnexpectedValueException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/orion")
 */
class UserController extends AbstractController
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
     * @Route("/", name="admin_user_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/check/mail/{token}", name="user_check_mail", methods={"GET"})
     * @param UserRepository $userRepository
     * @param EventDispatcherInterface $dispatcher
     * @param string $token
     * @return Response
     */

    public function checkUserEmail(UserRepository $userRepository, EventDispatcherInterface $dispatcher, string $token): Response
    {
        $user = $userRepository->findOneBy(['token'=>$token]);
        if (!$user) {
            throw new NotFoundHttpException('the user dont exist or the token is invalid!');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $user->setToken(null);
        $user->setActive(true);
        $entityManager->persist($user);
        $entityManager->flush();

        $userRegistrationEvent = new UserRegistrationEvent($user);
        $dispatcher->dispatch($userRegistrationEvent, $userRegistrationEvent::CONFIRMED_EMAIL);

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/reset/password/{idUser}", name="user_reset_password", methods={"GET","POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EventDispatcherInterface $dispatcher
     * @param $idUser
     * @return Response
     */
    public function resetUserPassword(Request $request, UserRepository $userRepository, EventDispatcherInterface $dispatcher, $idUser = null)
    {
        $form = $this->createForm(UserResetPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $userRepository->findOneBy(['email'=>$form->getData()['email']]);
            if (!$user) {
                throw new NotFoundHttpException('this user dont exist');
            }

            $userRegistrationEvent = new UserRegistrationEvent($user);
            $dispatcher->dispatch($userRegistrationEvent, $userRegistrationEvent::RESET_PASS_MAIL);

            return $this->render('user/reset_sended_email.html.twig', [
                    'form' => $form->createView(),
                ]);
        }

        return $this->render('user/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/change/password/{idUser}", name="user_change_password", methods={"GET","POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EventDispatcherInterface $dispatcher
     * @param $idUser
     * @return Response
     * @throws Exception
     */
    public function changeUserPassword(Request $request, UserRepository $userRepository, EventDispatcherInterface $dispatcher, $idUser = null)
    {
        if (!is_null($idUser)) {
            $form = $this->createForm(UserResetPasswordType::class, [$idUser]);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $newPass = $form->get('password-change')->getViewData();

                $user = $userRepository->findOneBy(['id' => $idUser]);
                if (!$user) {
                    throw new NotFoundHttpException('this user dont exist');
                }
                $encoded = $this->passwordEncoder->encodePassword($user, $newPass);
                $user->setPassword($encoded);
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                // TODO SEND_CONFIRMED_CHANGE_PASSWORD_EMAIL

                return $this->render('user/reset_ok.html.twig');
            }
            return $this->render('user/reset.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        throw new NotFoundHttpException('this user dont exist');
    }
    /**
     * @Route("/nuevo", name="admin_user_new", methods={"GET","POST"})
     * @param Request $request
     * @param EventDispatcherInterface $dispatcher
     * @return Response
     */
    public function new(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $entityManager = $this->getDoctrine()->getManager();
            $user->setToken(StaticFunctions::generateToken());
            $entityManager->persist($user);
            $entityManager->flush();

            $userRegistrationEvent = new UserRegistrationEvent($user);
            $dispatcher->dispatch($userRegistrationEvent, $userRegistrationEvent::USER_NEW_SIGNUP);

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
