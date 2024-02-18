<?php

namespace App\Controller;

use App\Repository\DevisRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\FactureRepository;
use App\Repository\PaiementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RapportFinancierController extends AbstractController
{
    #[Route('/{_locale<%app.supported_locales%>}/rapport', name: 'app_rapport_financier')]
    // #[Security('is_granted("ROLE_ADMIN") or is_granted("ROLE_COMPTABLE")')]
    public function index(AuthorizationCheckerInterface $authChecker, PaiementRepository $paiementRepository, EntrepriseRepository $entrepriseRepository,TranslatorInterface $translator, FactureRepository $factureRepository, DevisRepository $devisRepository): Response    
    {
        if (!$authChecker->isGranted('ROLE_ADMIN') && !$authChecker->isGranted('ROLE_COMPTABLE')) {
            throw new AccessDeniedException('Access Denied.');
        }
        $paiements = $paiementRepository->findAll();
        $factures = $factureRepository->findAll();
        $devis = $devisRepository->findAll();

        $entreprises = $entrepriseRepository->createQueryBuilder('e')
            ->select('e.id, e.nom')
            ->distinct()
            ->getQuery()
            ->getResult();

        $paiementsArray = [];
        foreach ($paiements as $paiement) {
            $paiementsArray[] = [
                'id' => $paiement->getId(),
                'datePaiement' => $paiement->getDatePaiement()->format('Y-m-d H:i:s'),
                'montant' => $paiement->getMontant(),
                'methodePaiement' => $paiement->getMethodePaiement(),
                'entreprise' => [
                    'id' => $paiement->getEntreprise()->getId(),
                    'name' => $paiement->getEntreprise()->getNom(),
                ],            
            ];
        }

        $facturesData = [];

        foreach ($factures as $facture) {
            $facturesData[] = [
                'id' => $facture->getId(),
                'dateFacturation' => $facture->getDateFacturation()->format('Y-m-d H:i:s'),
                'status' => $facture->getStatutPaiement(),           
            ];
        }

         $devisData = [];

        foreach ($devis as $devis) {
            $devisData[] = [
                'id' => $devis->getId(),
                'dateFacturation' => $devis->getDateCreation()->format('Y-m-d H:i:s'),
                'status' => $devis->getStatus(),           
            ];
        }

        // Get translated month names
        $translatedMonths = [
            'jan' => $translator->trans('jan'),
            'feb' => $translator->trans('feb'),
            'mar' => $translator->trans('mar'),
            'apr' => $translator->trans('apr'),
            'may' => $translator->trans('may'),
            'jun' => $translator->trans('jun'),
            'jul' => $translator->trans('jul'),
            'aug' => $translator->trans('aug'),
            'sep' => $translator->trans('sep'),
            'oct' => $translator->trans('oct'),
            'nov' => $translator->trans('nov'),
            'dec' => $translator->trans('dec')
            // Repeat for other months
        ];
        
        return $this->render('rapport_financier/index.html.twig', [
            'controller_name' => 'RapportFinancierController', 'paiements' => json_encode($paiementsArray), 'translatedMonths' => json_encode($translatedMonths), 'entreprises' => json_encode($entreprises), 'facturesData' => json_encode($facturesData), 'devisData' => json_encode($devisData),
        ]);
    }
    #[Route(path: '/csv-methodes/{year}', name: 'app_csv_methodes')]
    public function csvMethodes(PaiementRepository $repository, $year): Response
{
    $paiements = $repository->findPaymentsByYear($year);
    // Initialize CSV content with column names
    $csvContent = "Date Paiement; Montant; Methode Paiement\n";

    // Add data rows
    foreach ($paiements as $paiement) {
        $csvContent .= sprintf(
            "%s; %s; %s\n",
            $paiement['date_paiement'], // Access the date_paiement directly from the array
            $paiement['montant'],        // Access the montant directly from the array
            $paiement['methode_paiement'] // Access the methode_paiement directly from the array
        );
    }
    // Create the CSV response
    $response = new Response($csvContent);

    // Set response headers for CSV download
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="Paiements_' . $year . '.csv"');

    return $response;
}


}
