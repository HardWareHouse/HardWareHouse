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
        ];

        $translatedMethods = [
            'Carte bancaire' => $translator->trans('card'),
            'Espèces' => $translator->trans('cash'),
            'Chèque' => $translator->trans('check'),
            'Virement bancaire' => $translator->trans('transfer'),
        ];

        $translatedStatusDevis = [
            'Approuvé' => $translator->trans('approved'),
            'En attente' => $translator->trans('in_progress'),
            'Refusé' => $translator->trans('refused'),
        ];

        $translatedStatusFacture =[
            'Payé' => $translator->trans('paid'),
            'Non-payé' => $translator->trans('unpaid'),
            'En retard' => $translator->trans('late'),
        ];
        
        return $this->render('rapport_financier/index.html.twig', [
            'controller_name' => 'RapportFinancierController', 'paiements' => json_encode($paiementsArray), 'translatedMonths' => json_encode($translatedMonths), 'entreprises' => json_encode($entreprises), 'facturesData' => json_encode($facturesData), 'devisData' => json_encode($devisData), 'translatedMethods' => json_encode($translatedMethods), 'translatedStatusFacture' => json_encode($translatedStatusFacture), 'translatedStatusDevis' => json_encode($translatedStatusDevis)
        ]);
    }
    
    #[Route(path: '/{_locale<%app.supported_locales%>}/csv-methodes/{year}', name: 'app_csv_methodes')]
    public function csvMethodes(PaiementRepository $repository, TranslatorInterface $translator, $year): Response
{
    $paiements = $repository->findPaymentsByYear($year);

    $csvContent = $translator->trans('payment_date') . '; ' . 
                      $translator->trans('amount') . '; ' . 
                      $translator->trans('payment_method') . "\n";

    // Add data rows
    foreach ($paiements as $paiement) {
        $translatedMethod = [];

        if ($paiement['methode_paiement'] == 'Carte bancaire')
            $translatedMethod = $translator->trans('card');
        ;
        if ($paiement['methode_paiement'] == 'Espèces')
            $translatedMethod = $translator->trans('cash');
        ;
        if ($paiement['methode_paiement'] == 'Chèque')
            $translatedMethod = $translator->trans('check');
        ;
        if ($paiement['methode_paiement'] == 'Virement bancaire')
            $translatedMethod = $translator->trans('transfer');
        ;

        $montant = $this->formatTotalAmount($paiement['montant'], $translator);
        $montant .= ' €'; 

        $csvContent .= sprintf(
            "%s; %s; %s\n",
            $paiement['date_paiement'],
            $montant,
            $translatedMethod,
        );
    }

    $response = new Response($csvContent);

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition','attachment; filename="' . $translator->trans('payments') . '_' . $year . '.csv"');


    return $response;
}

#[Route(path: '/{_locale<%app.supported_locales%>}/csv-factures/{year}', name: 'app_csv_factures')]
    public function csvFactures(FactureRepository $repository, TranslatorInterface $translator, $year): Response
{
    $factures = $repository->findFacturesByYear($year);

    $csvContent = $translator->trans('invoice_number') . '; ' . 
                      $translator->trans('billing_date') . '; ' . 
                      $translator->trans('duedate') . '; ' . 
                      $translator->trans('status') . '; ' . 
                      $translator->trans('total_amount') . "\n";

    foreach ($factures as $facture) {

        $montantTotal = $this->formatTotalAmount($facture['total'], $translator);
        $montantTotal .= ' €'; 

        $translatedStatus = [];

        if ($facture['statut_paiement'] == 'Payé')
            $translatedStatus = $translator->trans('paid');
        ;
        if ($facture['statut_paiement'] == 'Non-payé')
            $translatedStatus = $translator->trans('unpaid');
        ;
        if ($facture['statut_paiement'] == 'En retard')
            $translatedStatus = $translator->trans('late');
        ;

        $csvContent .= sprintf(
            "%s; %s; %s; %s; %s\n",
            $facture['numero'],
            $facture['date_facturation'],
            $facture['date_paiement_due'], 
            $translatedStatus,
            $montantTotal,  
        );
    }

    $response = new Response($csvContent);

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $translator->trans('invoices') . '_' . $year . '.csv"');

    return $response;
}

private function formatTotalAmount($amount, TranslatorInterface $translator): string
{
    $locale = $translator->getLocale();
    
    // Check if the locale is French
    if ($locale === 'fr') {
        // Format amount in French style
        return number_format($amount, 2, ',', ' ') . ' €';
    } else {
        // Format amount in English style
        return number_format($amount, 2, '.', ',');
    }
}
}
