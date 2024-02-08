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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/{_locale<%app.supported_locales%>}/paiement')]
class PaiementController extends AbstractController
{
    private $userEntreprise;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EntityManagerInterface $entityManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }
    private function checkUserAccessToPayment($userEntreprise, $paiement): ?Response
    {
        $paiementEntreprise = $paiement->getEntreprise();
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN') && $userEntreprise->getId() !== $paiementEntreprise->getId()) {
            $this->addFlash(
                'danger',
                'La requête que vous essayez de faire est illégal !'
            );
            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }
        return null;
    }

    #[Route('/', name: 'app_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository): Response
    {   if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiementRepository->findAll(),
        ]);
    } else {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiementRepository->findBy(["entreprise" => $this->userEntreprise->getId()]),
        ]);
    }

    }

    #[Route('/new', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $paiement = new Paiement();
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paiement = $form->getData();
            $paiement->setEntreprise($this->userEntreprise);
            $this->entityManager->persist($paiement);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToPayment($this->userEntreprise, $paiement);
        if ($response !== null) {
            return $response;
        }

        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToPayment($this->userEntreprise, $paiement);
        if ($response !== null) {
            return $response;
        }

        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_paiment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToPayment($this->userEntreprise, $paiement);
        if ($response !== null) {
            return $response;
        }

        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($paiement);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
