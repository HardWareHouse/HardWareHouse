<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale<%app.supported_locales%>}/paiement')]
#[IsGranted('ROLE_USER')]
class PaiementController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository): Response
    {
        $userEntreprise = $this->getUser()->getEntreprise();

        if ($this->isGranted('ROLE_ADMIN')) {
            $paiements = $paiementRepository->findAll();
        } else {
            $paiements = $paiementRepository->findBy(["entrepriseId" => $userEntreprise->getId()]);
        }

        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiements,
        ]);
    }

    #[Route('/new', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $userEntreprise = $this->getUser()->getEntreprise();

        $paiement = new Paiement();
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paiement->setEntrepriseId($userEntreprise);
            $this->entityManager->persist($paiement);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_paiement_index');
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        $userEntreprise = $this->getUser()->getEntreprise();

        if (!$this->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $paiement->getEntrepriseId()->getId()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_paiement_index');
        }

        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement): Response
    {
        $userEntreprise = $this->getUser()->getEntreprise();

        if (!$this->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $paiement->getEntrepriseId()->getId()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_paiement_index');
        }

        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_paiement_index');
        }

        return $this->render('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement): Response
    {
        $userEntreprise = $this->getUser()->getEntreprise();

        if (!$this->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $paiement->getEntrepriseId()->getId()) {
            $this->addFlash('danger', 'La requête que vous essayez de faire est illégale !');
            return $this->redirectToRoute('app_paiement_index');
        }

        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($paiement);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
