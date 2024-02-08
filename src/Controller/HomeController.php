<?php

namespace App\Controller;

use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    private $userEntreprise;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EntityManagerInterface $entityManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }

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
    $factures = [];
    /** @var \App\Entity\User $user */
    $entrepriseId = $this->getUser()->getEntreprise()->getId();
    if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $factures = $factureRepository->findAll();
            $devisAttente = $devisRepository->findBy(["status" => 'En attente']);
            $devisApprouve = $devisRepository->findBy(["status" => 'Approuvé']);
            
        } else {
            $factures = $factureRepository->findBy(["entrepriseId" => $entrepriseId]);
            $devisAttente = $devisRepository->findBy(["entrepriseId" => $entrepriseId, "status" => 'En attente']);
            $devisApprouve = $devisRepository->findBy(["entrepriseId" => $entrepriseId, "status" => 'Approuvé']);
        }

    $devisAttenteCount = count($devisAttente);
    $devisApprouveCount = count($devisApprouve);
    $devisAttenteMontant = 0;
    $devisApprouveMontant = 0;
    foreach ($devisAttente as $attente){
        $devisAttenteMontant += $attente->getTotal();
    }
    foreach ($devisApprouve as $approuve){
        $devisApprouveMontant += $approuve->getTotal();
    }

    $facturesAttente = $factureRepository->findBy(["statutPaiement" => 'non payé']);
    $facturesLate = $factureRepository->findBy(["statutPaiement" => 'en retard']);
    $facturesAttenteCount = count($facturesAttente);
    $facturesLateCount = count($facturesLate);
    $facturesAttenteMontant = 0;
    $facturesLateMontant = 0;
    foreach ($facturesAttente as $attente){
        $facturesAttenteMontant += $attente->getTotal();
    }
    foreach ($facturesLate as $late){
        $facturesLateMontant += $late->getTotal();
    }

    $today = new \DateTime();
    $yesterday = (new \DateTime())->modify('-1 day');


    $firstDayOfMonth = new \DateTime('first day of this month');

    $lastDayOfMonth = new \DateTime('last day of this month');

    $firstDayOfLastMonth = (new \DateTime())->modify('first day of last month');

    $lastDayOfLastMonth = (new \DateTime())->modify('last day of last month');


    $totalPaiementsToday = 0;
    $totalPaiementsThisMonth = 0;
    $totalPaiementsYesterday = 0;
    $totalPaiementsLastMonth = 0;
    $totalPaiements = 0;
    

    // Loop through each facture to find associated paiements and sum their amounts
    foreach ($factures as $facture) {
        // Retrieve paiements associated with the current facture
        $paiements = $facture->getPaiementId();

        // Sum the amounts of paiements associated with the current facture
        foreach ($paiements as $paiement) {
            $totalPaiements += $paiement->getMontant();
            $datePaiement = $paiement->getDatePaiement();

            // Check if the datePaiement is today
            if ($datePaiement->format('Y-m-d') === $today->format('Y-m-d')) {
                $totalPaiementsToday += $paiement->getMontant();
            }

            // Check if the datePaiement is within the current month
            if ($datePaiement >= $firstDayOfMonth && $datePaiement <= $lastDayOfMonth) {
                $totalPaiementsThisMonth += $paiement->getMontant();
         }
            // Check if the datePaiement is yesterday
            if ($datePaiement->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                $totalPaiementsYesterday += $paiement->getMontant();
            }

            // Check if the datePaiement is within the last month
            if ($datePaiement >= $firstDayOfLastMonth && $datePaiement <= $lastDayOfLastMonth) {
                $totalPaiementsLastMonth += $paiement->getMontant();
            }
        }
    }

    // Pass data to Twig template
    return $this->render('home/index.html.twig', [
        'controller_name' => 'HomeController',
        'totalPaiements' => $totalPaiements,
        'factures' => $factures,
        'devisAttente' => $devisAttenteCount,
        'devisApprouve' => $devisApprouveCount,
        'devisAttenteMontant' => $devisAttenteMontant,
        'devisApprouveMontant' => $devisApprouveMontant,
        'facturesAttente' => $facturesAttenteCount,
        'facturesLate' => $facturesLateCount,
        'facturesAttenteMontant' => $facturesAttenteMontant,
        'facturesLateMontant' => $facturesLateMontant,
        'totalPaiementsToday' => $totalPaiementsToday,
        'totalPaiementsThisMonth' => $totalPaiementsThisMonth,
        'totalPaiementsYesterday' => $totalPaiementsYesterday,
        'totalPaiementsLastMonth' => $totalPaiementsLastMonth,
        
    ]);
}
}