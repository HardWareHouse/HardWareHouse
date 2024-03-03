<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\FormError;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/{_locale<%app.supported_locales%>}/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('plainPassword')->getData() !== $form->get('confirmPassword')->getData()) {
                // Gestion de l'erreur si les mots de passe ne correspondent pas
                $form->get('confirmPassword')->addError(new FormError('Les deux mots de passe ne correspondent pas !'));
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('hardwarehouse@outlook.fr', 'HardWareHouse'))
                    ->to($user->getMail())
                    ->subject('Veuillez confirmer votre adresse mail')
                    ->htmlTemplate('emails/registration.html.twig')
                    ->context([
                        'username' => $user->getUsername()
                    ])
            );


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {

        $id = $request->query->get('id');
        if (null === $id) {
            return $this->redirectToRoute('app_home');
        }
        $user = $userRepository->find($id);
        if (null === $user) {
            return $this->redirectToRoute('app_home');
        }
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // Redirect to the home page after successful verification
        return $this->redirectToRoute('app_home');
    }


    #[Route('/{_locale<%app.supported_locales%>}/register/cgu', name: 'app_cgu')]
    public function cgu(): Response
    {
        return $this->render('registration/cgu.html.twig');
    }

}
