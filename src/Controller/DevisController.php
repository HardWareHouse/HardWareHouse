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

#[Route('/{_locale<%app.supported_locales%>}/devis')]
#[IsGranted('ROLE_USER')]
class DevisController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_devis_index', methods: ['GET'])]
    public function index(DevisRepository $devisRepository): Response
    {   
        $userEntreprise = $this->getUser()->getEntreprise();

        if ($this->isGranted('ROLE_ADMIN')) {
            $devis = $devisRepository->findAll();
        } else {
            $devis = $devisRepository->findBy(["entrepriseId" => $userEntreprise->getId()]);
        }

        return $this->render('devis/index.html.twig', [
            'devis' => $devis,
        ]);
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MailerInterface $mailer, PdfService $pdfService): Response
    {
        $userEntreprise = $this->getUser()->getEntreprise();
        $devi = new Devis();
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($devi->getDetailDevis() as $detaildevis) {
                $detaildevis->setPrix(
                    $detaildevis->getProduit()->getPrix() * $detaildevis->getQuantite()
                );
            }
            $devi = $form->getData();
            if (!$this->isGranted('ROLE_ADMIN')) {
                $devi->setEntrepriseId($userEntreprise);
            }

            $this->entityManager->persist($devi);
            $this->entityManager->flush();

            // Générer le contenu du PDF
            $html = $this->renderView('devis/pdf.html.twig', [
                'devis' => $devi,
                'entreprise' => $userEntreprise,
            ]);
            $pdfContent = $pdfService->generatePdfContent($html);

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
        $userEntreprise = $this->getUser()->getEntreprise();

        if (!$this->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $devi->getEntrepriseId()->getId()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_devis_index');
        }

        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_devis_pdf', methods: ['GET'])]
    public function downloadPdf(Devis $devi, PdfService $pdfService): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $devi->getEntrepriseId()->getId()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_devis_index');
        }

        $html = $this->renderView('devis/pdf.html.twig', [
            'devis' => $devi,
            'entreprise' => $devi->getEntrepriseId(),
        ]);

        $pdfService->showPdfFile($html);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi): Response
    {   
        $userEntreprise = $this->getUser()->getEntreprise();
        if (!$this->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $devi->getEntrepriseId()->getId()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_devis_index');
        }

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
        $userEntreprise = $this->getUser()->getEntreprise();
        
        if (!$this->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $devi->getEntrepriseId()->getId()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_devis_index');
        }
        
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($devi);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
