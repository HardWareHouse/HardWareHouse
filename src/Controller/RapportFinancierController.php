<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RapportFinancierController extends AbstractController
{
    #[Route('/{_locale<%app.supported_locales%>}/rapport', name: 'app_rapport_financier')]
    public function index(AuthorizationCheckerInterface $authChecker): Response    
    {
        if (!$authChecker->isGranted('ROLE_ADMIN') && !$authChecker->isGranted('ROLE_COMPTABLE')) {
            throw new AccessDeniedException('Access Denied.');
        }
        
        return $this->render('rapport_financier/index.html.twig', [
            'controller_name' => 'RapportFinancierController',
        ]);
    }
}
