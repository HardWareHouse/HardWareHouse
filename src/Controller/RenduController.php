<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/componentTailwindCss')]
class RenduController extends AbstractController
{
    #[Route('/', name: 'app_component_tailwind_css_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('rendu/index.html.twig');
    }
}