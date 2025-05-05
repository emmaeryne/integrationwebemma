<?php


namespace App\Controller;


use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FacebookAuthController extends AbstractController
{
    #[Route('/connect/facebook', name: 'connect_facebook_start')]
    public function connect(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('facebook')
            ->redirect([
                'public_profile', 'email' // Les permissions que vous demandez
        ]);
    }

    #[Route('/connect/facebook/check', name: 'connect_facebook_check')]
    public function connectCheck(Request $request, ClientRegistry $clientRegistry)
    {
        // Le bundle gère automatiquement la vérification
        // Cette route sert juste de point de redirection
    }
}