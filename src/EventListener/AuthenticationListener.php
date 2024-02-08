<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationListener
{
    private $security;
    private $urlGenerator;
    private $excludedRoutes;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator, array $excludedRoutes = [])
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->excludedRoutes = ['app_my_entreprise_new', 'app_login', 'app_logout'];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$event->isMainRequest()) {
            return;
        }

        $routeName = $request->attributes->get('_route');
        if (in_array($routeName, $this->excludedRoutes)) {
            return;
        }

        if ($this->security->getUser() instanceof UserInterface 
            && !$this->security->isGranted('ROLE_ADMIN')
            && !$this->security->getUser()->getEntreprise()) {
                
            $response = new RedirectResponse($this->urlGenerator->generate('app_my_entreprise_new'));
            $event->setResponse($response);
        }
    }
}
