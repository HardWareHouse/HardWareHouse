<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Entreprise;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/devis')]
#[IsGranted('ROLE_USER')]
class DevisController extends AbstractController
{
    private $userEntreprise;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EntityManagerInterface $entityManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_devis_index', methods: ['GET'])]
    public function index(DevisRepository $devisRepository): Response
    {   
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('devis/index.html.twig', [
                'devis' => $devisRepository->findAll(),
            ]);
        } else {
            $this->userEntreprise = $this->getUser()->getEntreprise();
            return $this->render('devis/index.html.twig', [
                'devis' => $devisRepository->findBy(["entrepriseId" => $this->userEntreprise->getId()]),
            ]);
        }
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, PdfService $pdfService): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $devi = new Devis();
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devi = $form->getData();
            $devi->setEntrepriseId($this->userEntreprise);

            $entityManager->persist($devi);
            $entityManager->flush();

            // Générer le contenu du PDF
            $html = $this->renderView('devis/pdf.html.twig', [
                'devis' => $devi,
                'entreprise' => $this->userEntreprise,
            ]);
            $pdfContent = $pdfService->showPdfFile($html);

            // Créer l'email
            $userEmail = $this->getUser()->getMail(); // ou getMail(), selon votre implémentation de l'entité User
            $email = (new Email())
                ->from('devis@hardwarehouse.com')
                ->to($userEmail)
                ->subject('Votre devis')
                ->html('Voici votre devis en pièce jointe.')
                ->attach($pdfContent, 'devis.pdf', 'application/pdf');

            // Envoyer l'email
            $mailer->send($email);

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devi): Response
    {
        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_devis_pdf', methods: ['GET'])]
    public function downloadPdf(Entreprise $entreprise, Devis $devis, PdfService $pdfService): Response
    {

        $html = $this->renderView('devis/pdf.html.twig', [
            'devis' => $devis,
            'entreprise' => $entreprise,
        ]);

        $pdfService->showPdfFile($html);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi): Response
    {
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($devi);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
