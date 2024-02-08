<?php

namespace App\Controller;

use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use App\Repository\PaiementRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    #[Route('/')]
    public function indexNoLocale(): Response
    {
    return $this->redirectToRoute('app_home', ['_locale' => 'fr']);
    }
    
    #[Route('/{_locale<%app.supported_locales%>}/', name: 'app_home')]
    public function index(
    Security $security,
    PaiementRepository $paiementRepository,
    FactureRepository $factureRepository,
    DevisRepository $devisRepository
): Response {
    /** @var \App\Entity\User $user */
    $user = $security->getUser();
    $entrepriseId = $user->getEntreprise()->getId(); // Assuming you have a method to get the user's associated company ID

    // Retrieve factures for the user's entreprise
    $factures = $factureRepository->findByEntrepriseId($entrepriseId);

    // Initialize total paiements sum
    $totalPaiements = 0;

    // Loop through each facture to find associated paiements and sum their amounts
    foreach ($factures as $facture) {
        // Retrieve paiements associated with the current facture
        $paiements = $facture->getPaiements();

        // Sum the amounts of paiements associated with the current facture
        foreach ($paiements as $paiement) {
            $totalPaiements += $paiement->getMontant();
        }
    }

    // Retrieve devis for the user's entreprise
    $devis = $devisRepository->findByEntrepriseId($entrepriseId);

    // Pass data to Twig template
    return $this->render('home/index.html.twig', [
        'controller_name' => 'HomeController',
        'totalPaiements' => $totalPaiements,
        'factures' => $factures,
        'devis' => $devis,
    ]);
}
}