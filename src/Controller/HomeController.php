<?php

namespace App\Controller;

use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use App\Repository\PaiementRepository;
use App\Repository\ProduitRepository;
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
    return $this->redirectToRoute('app_freemium', ['_locale' => 'fr']);
    }
    
    #[Route('/{_locale<%app.supported_locales%>}/', name: 'app_home')]
    public function index(
    Security $security,
    PaiementRepository $paiementRepository,
    FactureRepository $factureRepository,
    DevisRepository $devisRepository,
    ProduitRepository $produitRepository
): Response {
    $factures = [];
    /** @var \App\Entity\User $user */
    if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            // $factures = $factureRepository->findAll();
            // $devisAttente = $devisRepository->findBy(["status" => 'En attente']);
            // $devisApprouve = $devisRepository->findBy(["status" => 'Approuvé']);
            // $produits = $produitRepository->findLatestProducts();
            // $bestSellers = $produitRepository->findBestSellers();
            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        } else {

    $entrepriseId = $this->getUser()->getEntreprise()->getId();

    $factures = $factureRepository->findBy(['entrepriseId' => $entrepriseId]);
    $devisAttente = $devisRepository->findBy(["entrepriseId" => $entrepriseId, "status" => 'En attente']);
    $devisApprouve = $devisRepository->findBy(["entrepriseId" => $entrepriseId, "status" => 'Approuvé']);
    $produits = $produitRepository->findLatestProductsByEntrepriseId($entrepriseId);
    $bestSellers = $produitRepository->findBestSellersByEntreprise($entrepriseId);
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

    $facturesAttente = $factureRepository->findBy(["entrepriseId" => $entrepriseId, "statutPaiement" => 'Impayée']);
    $facturesLate = $factureRepository->findBy(["entrepriseId" => $entrepriseId, "statutPaiement" => 'En retard']);
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
    

    foreach ($factures as $facture) {
        dump($facture);
        $paiements = $facture->getPaiementId();
        dump($paiements);
        foreach ($paiements as $paiement) {
            $totalPaiements += $paiement->getMontant();
            $datePaiement = $paiement->getDatePaiement();

            if ($datePaiement->format('Y-m-d') === $today->format('Y-m-d')) {
                $totalPaiementsToday += $paiement->getMontant();
            }

            if ($datePaiement >= $firstDayOfMonth && $datePaiement <= $lastDayOfMonth) {
                $totalPaiementsThisMonth += $paiement->getMontant();
         }
            if ($datePaiement->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                $totalPaiementsYesterday += $paiement->getMontant();
            }
            if ($datePaiement >= $firstDayOfLastMonth && $datePaiement <= $lastDayOfLastMonth) {
                $totalPaiementsLastMonth += $paiement->getMontant();
            }
        }
    }

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
        'produits' => $produits,
        'bestSellers' => $bestSellers,
    ]);
}
}
