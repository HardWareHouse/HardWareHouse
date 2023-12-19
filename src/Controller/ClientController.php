<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/{_locale<%app.supported_locales%>}/client')]
#[IsGranted('ROLE_USER')]
class ClientController extends AbstractController
{   
    private $userEntreprise;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EntityManagerInterface $entityManager)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }

    private function checkUserAccessToClient($userEntreprise, $client): ?Response
    {
        $clientEntreprise = $client->getEntrepriseId();
        if ($userEntreprise->getId() !== $clientEntreprise->getId()) {
            $this->addFlash(
                'danger',
                'Vous ne pouvez pas accéder à ce client!'
            );
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }
        return null;
    }

    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {  
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('client/index.html.twig', [
                'clients' => $clientRepository->findAll(),
            ]);
        } else {
            $this->userEntreprise = $this->getUser()->getEntreprise();
            return $this->render('client/index.html.twig', [
                'clients' => $clientRepository->findBy(["entrepriseId" => $this->userEntreprise->getId()]),
            ]);
        }
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();
            $client->setEntrepriseId($this->userEntreprise);

            $this->entityManager->persist($client);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToClient($this->userEntreprise, $client);
        if ($response !== null) {
            return $response;
        }

        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client): Response
    {
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToClient($this->userEntreprise, $client);
        if ($response !== null) {
            return $response;
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client): Response
    {   
        $this->userEntreprise = $this->getUser()->getEntreprise();
        $response = $this->checkUserAccessToClient($this->userEntreprise, $client);
        if ($response !== null) {
            return $response;
        }
        
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($client);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
