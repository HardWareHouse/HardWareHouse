<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
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

#[Route('/facture')]
#[IsGranted('ROLE_USER')]
class FactureController extends AbstractController
{
    private $userEntreprise;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EntityManagerInterface $entityManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }

    private function checkUserAccessToFacture($userEntreprise, $facture): ?Response
    {
        $factureEntreprise = $facture->getEntrepriseId();
        if ($userEntreprise->getId() !== $factureEntreprise->getId()) {
            $this->addFlash(
                'danger',
                'Vous ne pouvez pas accéder à cette facture!'
            );
            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }
        return null;
    }

    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository): Response
    {   
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('facture/index.html.twig', [
                'factures' => $factureRepository->findAll(),
            ]);
        } else {
            $this->userEntreprise = $this->getUser()->getEntreprise();
            return $this->render('facture/index.html.twig', [
                'factures' => $factureRepository->findBy(["entrepriseId" => $this->userEntreprise->getId()]),
            ]);
        }
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, PdfService $pdfService): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();

        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facture = $form->getData();
            $facture->setEntrepriseId($this->userEntreprise);
            //$client = $facture->getClientId();

            $this->entityManager->persist($facture);
            $this->entityManager->flush();

            // Générer le contenu du PDF
            $html = $this->renderView('facture/pdf.html.twig', [
                'facture' => $facture,
            ]);
            $pdfContent = $pdfService->generatePdfContent($html);


            // Créer l'email
            $userEmail = $this->getUser()->getMail();
            $email = (new Email())
                ->from('facture@hardwarehouse.com')
                ->to($userEmail)
                ->subject('Votre facture')
                ->html('Voici votre facture en pièce jointe.')
                ->attach($pdfContent, 'facture.pdf', 'application/pdf');

            // Envoyer l'email
            $mailer->send($email);


            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToFacture($this->userEntreprise, $facture);
        if ($response !== null) {
            return $response;
        }

        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }
    #[Route('/{id}/pdf', name: 'app_facture_pdf', methods: ['GET'])]
    public function downloadPdf(Client $client, Facture $facture, PdfService $pdfService): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToFacture($this->userEntreprise, $facture);
        if ($response !== null) {
            return $response;
        }

        $html = $this->renderView('facture/pdf.html.twig', [
            'facture' => $facture,
            'client' => $client,
        ]);

        $pdfService->showPdfFile($html);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToFacture($this->userEntreprise, $facture);
        if ($response !== null) {
            return $response;
        }

        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToFacture($this->userEntreprise, $facture);
        if ($response !== null) {
            return $response;
        }
        
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($facture);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
