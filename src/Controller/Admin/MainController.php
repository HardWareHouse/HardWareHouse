<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\DevisRepository;
use App\Repository\ProduitRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/{_locale<%app.supported_locales%>}/admin')]
#[IsGranted('ROLE_ADMIN')]
class MainController extends AbstractController
{   
    private $userRepository;
    private $entrepriseRepository;
    private $devisRepository;
    private $produitRepository;

    public function __construct(UserRepository $userRepository, EntrepriseRepository $entrepriseRepository, DevisRepository $devisRepository, ProduitRepository $produitRepository)
    {
        $this->userRepository = $userRepository;
        $this->entrepriseRepository = $entrepriseRepository;
        $this->devisRepository = $devisRepository;
        $this->produitRepository = $produitRepository;
    }
    
    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(): Response
    {   
        $users = $this->userRepository->findAll();
        $entrepriseCount = $this->entrepriseRepository->count([]);
        $devisCount = $this->devisRepository->count([]);
        $produitCount = $this->produitRepository->count([]);

        $userCount = 0;
        foreach ($users as $user) {
            $rolesUser = $user->getRoles();
            if (is_array($rolesUser) && !in_array('ROLE_ADMIN', $rolesUser)) {
                $userCount+=1;
            }
        }

        $latestUsers = $this->userRepository->findBy([], ['CreatedAt' => 'DESC'], 5);

        $filteredUsers = [];
        foreach ($latestUsers as $user) {
            $rolesUser = $user->getRoles();
            if (is_array($rolesUser) && !in_array('ROLE_ADMIN', $rolesUser)) {
                $filteredUsers[] = $user;
            }
        }

        $latestEntreprises = $this->entrepriseRepository->findBy([], ['CreatedAt' => 'DESC'], 5);
 
        return $this->render('admin/index.html.twig', [
            'user_count' => $userCount,
            'entreprise_count' => $entrepriseCount,
            'devis_count' => $devisCount,
            'produit_count' => $produitCount,
            'latest_users' => $filteredUsers,
            'latest_entreprises' => $latestEntreprises,
        ]);
    }
}
