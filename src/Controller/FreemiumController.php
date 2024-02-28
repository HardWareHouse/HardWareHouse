<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FreemiumController extends AbstractController
{
    #[Route('/{_locale<%app.supported_locales%>}/freemium', name: 'app_freemium')]
    public function index(): Response
    {
        return $this->render('freemium/index.html.twig', [
            'controller_name' => 'FreemiumController',
        ]);
    }
}
