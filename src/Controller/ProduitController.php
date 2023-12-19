<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Repository\EntrepriseRepository;

#[Route('/{_locale<%app.supported_locales%>}/produit')]
#[IsGranted('ROLE_USER')]
class ProduitController extends AbstractController
{   
    private $userEntreprise;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EntityManagerInterface $entityManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }

    private function checkUserAccessToProduit($userEntreprise, $produit): ?Response
    {
        $produitEntreprise = $produit->getEntrepriseId();
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $produitEntreprise->getId()) {
            $this->addFlash(
                'danger',
                'Vous ne pouvez pas accéder à ce produit!'
            );
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }
        return null;
    }

    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(EntrepriseRepository $entrepriseRepository,ProduitRepository $produitRepository,): Response
    {   
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('produit/index.html.twig', [
                'produits' => $produitRepository->findAll()]);
        } else {
            $this->userEntreprise = $this->getUser()->getEntreprise();
            return $this->render('produit/index.html.twig', [
                'produits' => $produitRepository->findBy(["entrepriseId" => $this->userEntreprise->getId()]),
            ]);
        }
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            $produit->setEntrepriseId($this->userEntreprise);

            $this->entityManager->persist($produit);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToProduit($this->userEntreprise, $produit);
        if ($response !== null) {
            return $response;
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToProduit($this->userEntreprise, $produit);
        if ($response !== null) {
            return $response;
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToProduit($this->userEntreprise, $produit);
        if ($response !== null) {
            return $response;
        }

        if ($this->isCsrfTokenValid('delete'.$produit->getUuid(), $request->request->get('_token'))) {
            $this->entityManager->remove($produit);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
