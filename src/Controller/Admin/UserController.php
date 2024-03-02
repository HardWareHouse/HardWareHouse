<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\FormError;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('/{_locale<%app.supported_locales%>}/admin/user')]
class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;
    private EmailVerifier $emailVerifier;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, 
                                EmailVerifier $emailVerifier, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->emailVerifier = $emailVerifier;
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {   
        $users = $userRepository->findAll();
        $filteredUsers = [];
        foreach ($users as $user) {
            $rolesUser = $user->getRoles();
            if (is_array($rolesUser) && !in_array('ROLE_ADMIN', $rolesUser)) {
                $filteredUsers[] = $user;
            }
        }

        return $this->render('admin/user/index.html.twig', [
            'users' => $filteredUsers,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData() !== $form->get('confirmPassword')->getData()) {
                $form->get('confirmPassword')->addError(new FormError('Les deux mots de passe ne correspondent pas !'));
                return $this->render('admin/user/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        
            $this->sendEmailConfirmation($user);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{uuid}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {   
        if ($this->getUser()->getUuid() === $user->getUuid()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_user_index');
        }

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
        
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {   
        if ($this->getUser()->getUuid() === $user->getUuid()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_user_index');
        }
        
        if ($this->isCsrfTokenValid('delete'.$user->getUuid(), $request->request->get('_token'))) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    private function sendEmailConfirmation(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('ariaaman@outlook.fr', 'HardWareHouse'))
            ->to($user->getMail())
            ->subject('Veuillez confirmer votre adresse mail')
            ->htmlTemplate('emails/registration.html.twig')
            ->context([
                'username' => $user->getUsername()
            ]);

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $email);
    }
}
