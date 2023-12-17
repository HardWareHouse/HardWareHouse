<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RapportFinancierController extends AbstractController
{
    #[Route('/rapport', name: 'app_rapport_financier')]
    public function index(): Response
    {
        return $this->render('rapport_financier/index.html.twig', [
            'controller_name' => 'RapportFinancierController',
        ]);
    }
}
