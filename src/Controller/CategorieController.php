<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/categorie')]
#[IsGranted('ROLE_USER')]
class CategorieController extends AbstractController
{   
    private $userEntreprise;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EntityManagerInterface $entityManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {   
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('categorie/index.html.twig', [
                'categories' => $categorieRepository->findAll(),
            ]);
        } else {
            $this->userEntreprise = $this->getUser()->getEntreprise();
            return $this->render('categorie/index.html.twig', [
                'categories' => $categorieRepository->findBy(["entrepriseId" => $this->userEntreprise->getId()]),
            ]);
        }
    }

    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = getData();
            $categorie->setEntrepriseId($this->userEntreprise);
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getUuid(), $request->request->get('_token'))) {
            $this->entityManager->remove($categorie);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
