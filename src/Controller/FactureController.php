<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\DetailFacture;
use App\Entity\Devis;
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

#[Route('/{_locale<%app.supported_locales%>}/facture')]
#[IsGranted('ROLE_USER')]
class FactureController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository): Response
    {   
        $userEntreprise = $this->getUser()->getEntreprise();

        if ($this->isGranted('ROLE_ADMIN')) {
            $factures = $factureRepository->findAll();
        } else {
            $factures = $factureRepository->findBy(["entrepriseId" => $userEntreprise->getId()]);
        }

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
        ]);
    }

    #[Route('/new/{deviId}', name: 'app_facture_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, PdfService $pdfService): Response
    {   
        $deviId = $request->get('deviId');
        $devi = $entityManager->getRepository(Devis::class)->find($deviId);
        if (!$deviId || !$devi) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_devis_index');
        }

        $userEntreprise = $this->getUser()->getEntreprise();
        
        $facture = new Facture();
        $facture->setDevi($devi);
        $facture->setEntrepriseId($userEntreprise);

        // Génération du numéro de facture
        $numero = preg_replace('/DEVIS/i', '', $devi->getNumero());
        $facture->setNumero('FACTURE'.$numero);
        $facture->setClientId($devi->getClientId());
        $facture->setTotal($devi->getTotal());

        // Ajout des détails de la facture
        foreach ($devi->getDetailDevis() as $detaildevis) {
            $detaildevis->getProduit()->setStock($detaildevis->getProduit()->getStock() - $detaildevis->getQuantite());

            if ($detaildevis->getProduit()->getStock() < 0) {
                $this->addFlash('danger', 'Le stock du produit '.$detaildevis->getProduit()->getNom().' est insuffisant pour valider le devis.');
                return $this->redirectToRoute('app_devis_index');
            }

            $detailFacture = new DetailFacture();
            $detailFacture->setPrix($detaildevis->getPrix());
            $detailFacture->setQuantite($detaildevis->getQuantite());
            $detailFacture->setProduit($detaildevis->getProduit());
            $detailFacture->setFacture($facture);

            $facture->addDetailFacture($detailFacture);

            $entityManager->persist($detailFacture);
        }

        // Ajout de la facture au devis
        $devi->addFacture($facture);

        // Persist et flush des entités
        $entityManager->persist($devi);
        $entityManager->persist($facture);
        $entityManager->flush();

        // Génération du contenu PDF
        $html = $this->renderView('facture/pdf.html.twig', [
            'facture' => $facture,
        ]);
        $pdfContent = $pdfService->generatePdfContent($html);
        $emailContent = $this->renderView('facture/email.html.twig', [
        ]);
        // Création de l'email
        $userEmail = $this->getUser()->getMail();
        $email = (new Email())
            ->from('facture@hardwarehouse.com')
            ->to($userEmail)
            ->subject('Votre facture')
            ->html($emailContent)
            ->attach($pdfContent, 'facture.pdf', 'application/pdf');

        // Envoi de l'email
        $mailer->send($email);

        // Redirection vers la liste des factures
        $this->addFlash('succes', 'Votre devis a bien été confirmé !');
        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {   
        $userEntreprise = $this->getUser()->getEntreprise();

        if (!$this->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $facture->getEntrepriseId()->getId()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_facture_index');
        }

        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }
    #[Route('/{id}/pdf', name: 'app_facture_pdf', methods: ['GET'])]
    public function downloadPdf(Facture $facture, PdfService $pdfService): Response
    {   
        $userEntreprise = $this->getUser()->getEntreprise();

        if (!$this->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $facture->getEntrepriseId()->getId()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_facture_index');
        }

        $path = $this->getParameter('kernel.project_dir') . '/public/assets/icon/hwh.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logoHwh = 'data:image/' . $type . ';base64,' . base64_encode($data);


        $client = $facture->getClientId();
        $entreprise = $facture->getEntrepriseId();

        $html = $this->renderView('facture/pdf.html.twig', [
            'facture' => $facture,
            'logoHwh' => $logoHwh,
            'client' => $client,
            'entreprise' => $entreprise,
            
        ]);

        $pdfService->showPdfFile($html);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture): Response
    {   
        $userEntreprise = $this->getUser()->getEntreprise();

        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_facture_index');
        } else{
            if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
                $this->entityManager->remove($facture);
                $this->entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
