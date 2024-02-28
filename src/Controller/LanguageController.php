<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LanguageController extends AbstractController
{
    #[Route('change-language/{locale}', name: 'change_language')]
    public function changeLanguage(Request $request, string $locale): Response
    {
        $locale = $request->getLocale();
        var_dump($locale);
        // Store the selected locale in the session
        $request->getSession()->set('locale', $locale);

        return new Response('Language changed successfully to:' . $locale);
        // return $this->redirect($request->headers->get('referer'));
    }

}