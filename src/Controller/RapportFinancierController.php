<?php

namespace App\Controller;

use DateTime;
use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use App\Repository\PaiementRepository;
use App\Repository\EntrepriseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RapportFinancierController extends AbstractController
{
    #[Route('/{_locale<%app.supported_locales%>}/admin/rapport', name: 'app_rapport_financier_admin')]
    public function adminRapport(PaiementRepository $paiementRepository, EntrepriseRepository $entrepriseRepository,TranslatorInterface $translator, FactureRepository $factureRepository, DevisRepository $devisRepository, Request $request): Response    
    {
        $companyId = $request->query->get('companyId', null);
    
        if ($companyId !== null) {

            $paiements = $paiementRepository->findByCompany($companyId);
            $factures = $factureRepository->findByCompany($companyId);
            $devis = $devisRepository->findByCompany($companyId);
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
                'entrepriseId' => $paiement->getEntrepriseId()->getId()     
            ];
        }

        $facturesData = [];

        foreach ($factures as $facture) {
            $facturesData[] = [
                'id' => $facture->getId(),
                'dateFacturation' => $facture->getDateFacturation()->format('Y-m-d H:i:s'),
                'status' => $facture->getStatutPaiement(),
                'entrepriseId' => $facture->getEntrepriseId()->getId()     

            ];
        }

         $devisData = [];

        foreach ($devis as $devis) {
            $devisData[] = [
                'id' => $devis->getId(),
                'dateFacturation' => $devis->getDateCreation()->format('Y-m-d H:i:s'),
                'status' => $devis->getStatus(), 
                'entrepriseId' => $devis->getEntrepriseId()->getId()     
    
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
        
        return $this->render('rapport_financier/admin.html.twig', [
            'controller_name' => 'RapportFinancierController', 'paiements' => json_encode($paiementsArray), 'translatedMonths' => json_encode($translatedMonths), 'entreprises' => json_encode($entreprises), 'facturesData' => json_encode($facturesData), 'devisData' => json_encode($devisData), 'translatedMethods' => json_encode($translatedMethods), 'translatedStatusFacture' => json_encode($translatedStatusFacture), 'translatedStatusDevis' => json_encode($translatedStatusDevis)
        ]);}

    #[Route('/{_locale<%app.supported_locales%>}/rapport', name: 'app_rapport_financier')]
    public function comptableRapport(AuthorizationCheckerInterface $authChecker, PaiementRepository $paiementRepository, EntrepriseRepository $entrepriseRepository,TranslatorInterface $translator, FactureRepository $factureRepository, DevisRepository $devisRepository): Response    
    {
        if (!$authChecker->isGranted('ROLE_COMPTABLE')) {
            throw new AccessDeniedException('Access Denied.');
        }
        /** @var \App\Entity\User $user */

        $user = $this->getUser();
        $company = $user->getEntreprise();

        $paiements = $paiementRepository->findByEntreprise($company);
        $factures = $factureRepository->findByEntreprise($company);
        $devis = $devisRepository->findByEntreprise($company);

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
            'controller_name' => 'RapportFinancierController', 'paiements' => json_encode($paiementsArray), 'translatedMonths' => json_encode($translatedMonths), 'facturesData' => json_encode($facturesData), 'devisData' => json_encode($devisData), 'translatedMethods' => json_encode($translatedMethods), 'translatedStatusFacture' => json_encode($translatedStatusFacture), 'translatedStatusDevis' => json_encode($translatedStatusDevis)
        ]);}
    
    #[Route(path: '/{_locale<%app.supported_locales%>}/admin/csv-methodes/{company}/{year}', name: 'app_csv_methodes')]
    public function csvMethodes(PaiementRepository $repository, EntrepriseRepository $entrepriseRepository, TranslatorInterface $translator, $year, $company): Response
{
    if ($company == 'all' or $company == 'undefined'){
    $paiements = $repository->findPaymentsByYear($year);
    } else {
    $paiements = $repository->findByYearAndCompany($year, $company);
    $entreprise = $entrepriseRepository->find($company);
    $companyName = $entreprise->getNom();
    }

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

        $csvContent .= sprintf(
            "%s; %s; %s\n",
            $paiement['date_paiement'],
            $montant,
            $translatedMethod,
        );
    }

    $response = new Response($csvContent);

    $response->headers->set('Content-Type', 'text/csv');
    if ($company == 'all' or $company == 'undefined'){
    $response->headers->set('Content-Disposition','attachment; filename="' . $translator->trans('payment_method') . '_' . $year . '.csv"');
    } else {
    $response->headers->set('Content-Disposition','attachment; filename="' . $translator->trans('payment_method') . '_' . $companyName . '_' . $year . '.csv"');
    }


    return $response;
}

#[Route(path: '/{_locale<%app.supported_locales%>}/admin/csv-factures/{company}/{year}', name: 'app_csv_factures')]
    public function csvFactures(FactureRepository $repository, EntrepriseRepository $entrepriseRepository, TranslatorInterface $translator, $year, $company): Response
{
    if ($company == 'all' or $company == 'undefined'){
    $factures = $repository->findFacturesByYear($year);
    } else {
    $factures = $repository->findByYearAndCompany($year, $company);
    $entreprise = $entrepriseRepository->find($company);
    $companyName = $entreprise->getNom();
    }

    $csvContent = $translator->trans('invoice_number') . '; ' . 
                      $translator->trans('billing_date') . '; ' . 
                      $translator->trans('duedate') . '; ' . 
                      $translator->trans('status') . '; ' . 
                      $translator->trans('total_amount') . "\n";

    foreach ($factures as $facture) {

        $montantTotal = $this->formatTotalAmount($facture['total'], $translator);

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
    if ($company == 'all' or $company == 'undefined'){
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $translator->trans('invoices') . '_' . $year . '.csv"');
    } else {
    $response->headers->set('Content-Disposition','attachment; filename="' . $translator->trans('invoices') . '_' . $companyName . '_' . $year . '.csv"');
    }
    
    return $response;
}

#[Route(path: '/{_locale<%app.supported_locales%>}/admin/csv-devis/{company}/{year}', name: 'app_csv_devis')]
    public function csvDevis(DevisRepository $repository, TranslatorInterface $translator, $year): Response
{
    $devis = $repository->findByYear($year);

    $csvContent = $translator->trans('number') . '; ' . 
                      $translator->trans('created_on') . '; ' . 
                      $translator->trans('status') . '; ' . 
                      $translator->trans('total_amount') . "\n";

    foreach ($devis as $devis) {

        $montantTotal = $this->formatTotalAmount($devis['total'], $translator);

        $translatedStatus = [];

        if ($devis['status'] == 'Approuvé')
            $translatedStatus = $translator->trans('approved');
        ;
        if ($devis['status'] == 'Refusé')
            $translatedStatus = $translator->trans('refused');
        ;
        if ($devis['status'] == 'En attente')
            $translatedStatus = $translator->trans('in_progress');
        ;

        $csvContent .= sprintf(
            "%s; %s; %s; %s\n",
            $devis['numero'],
            $devis['date_creation'],
            $translatedStatus,
            $montantTotal,  
        );
    }

    $response = new Response($csvContent);

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $translator->trans('estimates') . '_' . $year . '.csv"');

    return $response;
}

#[Route(path: '/{_locale<%app.supported_locales%>}/admin/csv-revenue/{company}/{year}', name: 'app_csv_devis')]
    public function csvRevenue(PaiementRepository $repository, TranslatorInterface $translator, $year): Response
{
    $payments = $repository->findPaymentsByYear($year);

    $csvContent = $translator->trans('month') . '; ' . 
                      $translator->trans('total_amount') . "\n";

    // Initialize an array to store monthly totals
    $monthlyTotals = array_fill(1, 12, 0);

    // Calculate monthly totals
    foreach ($payments as $payment) {
        // Extract the month from the payment date
        $paymentMonth = (int) date('n', strtotime($payment['date_paiement']));

        // Add the payment amount to the corresponding month's total
        $monthlyTotals[$paymentMonth] += $payment['montant'];
    }

    // Format and add monthly totals to CSV content
    for ($month = 1; $month <= 12; $month++) {
        // Get the translated month name
        $monthName = $this->getTranslatedMonthName($month, $translator);

        $montantTotal = $this->formatTotalAmount($monthlyTotals[$month], $translator);

        $csvContent .= sprintf(
            "%s; %s\n",
            $monthName,
            $montantTotal
        );
    }

    $response = new Response($csvContent);

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $translator->trans('revenue') . '_' . $year . '.csv"');

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
        return number_format($amount, 2, '.', ',') . ' €';
    }
}

private function getTranslatedMonthName(int $month, TranslatorInterface $translator): string
{
    // Create a DateTime object with the given month and year
    $dateTime = new DateTime("2022-$month-01"); // Choose any year, as we only need the month

    // Get the translated month name using the translator service
    $monthName = $translator->trans($dateTime->format('F')); // 'F' returns the full month name

    return $monthName;
}

}
