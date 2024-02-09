<?php

namespace App\Controller;

use App\Repository\PaiementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RapportFinancierController extends AbstractController
{
    #[Route('/{_locale<%app.supported_locales%>}/rapport', name: 'app_rapport_financier')]
    // #[Security('is_granted("ROLE_ADMIN") or is_granted("ROLE_COMPTABLE")')]
    public function index(AuthorizationCheckerInterface $authChecker, PaiementRepository $repository): Response    
    {
        if (!$authChecker->isGranted('ROLE_ADMIN') && !$authChecker->isGranted('ROLE_COMPTABLE')) {
            throw new AccessDeniedException('Access Denied.');
        }
        $paiements = $repository->findAll();
        $paiementsArray = [];
        foreach ($paiements as $paiement) {
            $paiementsArray[] = [
                'id' => $paiement->getId(),
                'datePaiement' => $paiement->getDatePaiement()->format('Y-m-d H:i:s'),
                'montant' => $paiement->getMontant(),
                'methodePaiement' => $paiement->getMethodePaiement(),
                // Add other properties as needed
            ];
        }
        
        return $this->render('rapport_financier/index.html.twig', [
            'controller_name' => 'RapportFinancierController', 'paiements' => json_encode($paiementsArray)
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
