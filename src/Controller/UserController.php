<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEntrepriseType;
use App\Form\UserEntrepriseEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/{_locale<%app.supported_locales%>}/user')]
class UserController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private $userEntreprise;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EmailVerifier $emailVerifier)
    {   
        $this->authorizationChecker = $authorizationChecker;
        $this->emailVerifier = $emailVerifier;
    }

    private function checkUserAccessToUser($userEntreprise, $user): ?Response
    {
        $userEntreprise = $user->getEntreprise();
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $userEntreprise->getId()) {
            $this->addFlash(
                'danger',
                'La requête que vous essayez de faire est illégal !'
            );
            return $this->redirectToRoute('app_my_user_index', [], Response::HTTP_SEE_OTHER);
        }
        return null;
    }

    #[Route('/', name: 'app_my_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {   
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('user/index.html.twig', [
                'users' => $userRepository->findAll(),
            ]);
        } else {
            $this->userEntreprise = $this->getUser()->getEntreprise();
            return $this->render('user/index.html.twig', [
                'users' => $userRepository->findBy(["entreprise" => $this->userEntreprise->getId()]),
            ]);
        }
    }

    #[Route('/new', name: 'app_my_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserEntrepriseType::class, $user);
        $form->handleRequest($request);
        $this->userEntreprise = $this->getUser()->getEntreprise();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData() !== $form->get('confirmPassword')->getData()) {
                // Gestion de l'erreur si les mots de passe ne correspondent pas
                $form->get('confirmPassword')->addError(new FormError('Les deux mots de passe ne correspondent pas !'));
                return $this->render('user/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setEntreprise($this->userEntreprise);
            $entityManager->persist($user);
            $entityManager->flush();
        
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('ariaaman@outlook.fr', 'HardWareHouse'))
                    ->to($user->getMail())
                    ->subject('Veuillez confirmer votre adresse mail')
                    ->htmlTemplate('emails/registration.html.twig')
                    ->context([
                        'username' => $user->getUsername()
                    ])
            );

            return $this->redirectToRoute('app_my_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_my_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToUser($this->userEntreprise, $user);
        if ($response !== null) {
            return $response;
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{uuid}/edit', name: 'app_my_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToUser($this->userEntreprise, $user);
        if ($response !== null) {
            return $response;
        }

        $form = $this->createForm(UserEntrepriseEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
        
            return $this->redirectToRoute('app_my_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_my_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToUser($this->userEntreprise, $user);
        if ($response !== null) {
            return $response;
        }

        if ($this->isCsrfTokenValid('delete'.$user->getUuid(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_my_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
