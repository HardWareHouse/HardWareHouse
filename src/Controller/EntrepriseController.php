<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale<%app.supported_locales%>}/entreprise')]
#[IsGranted('ROLE_USER')]
class EntrepriseController extends AbstractController
{   
    #[Route('/', name: 'app_my_entreprise_show', methods: ['GET'])]
    public function show(): Response
    {   
        $entreprise = $this->getUser()->getEntreprise();
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    #[Route('/creation', name: 'app_my_entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   
        // $this->getUser()->setRoles(['ROLE_ADMIN']);
        // $entityManager->flush();
        // dd($this->getUser());
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUser()->setEntreprise($entreprise);
            $entityManager->persist($entreprise);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprise/create.html.twig', [
            'entreprise' => $entreprise,
            'companyCreationForm' => $form,
        ]);
    }


    #[Route('/edit', name: 'app_my_entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $entreprise = $this->getUser()->getEntreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

}
