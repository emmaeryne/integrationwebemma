<?php

namespace App\Security;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FacebookAuthenticator extends OAuth2Authenticator
{
    private $clientRegistry;
    private $entityManager;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('facebook_main');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client, $request) {
                $facebookUser = $client->fetchUserFromToken($accessToken);
                $email = $facebookUser->getEmail();

                // Get high-quality profile picture URL
                $graphApi = $client->fetchUserFromToken($accessToken);
                $pictureUrl = 'https://graph.facebook.com/' . $facebookUser->getId() . 
                             '/picture?type=large&access_token=' . $accessToken->getToken();

                $existingUser = $this->entityManager->getRepository(Users::class)
                    ->findOneBy(['email' => $email]);

                if (!$existingUser) {
                    $existingUser = new Users();
                    $existingUser->setEmail($email);
                    $existingUser->setNom($facebookUser->getLastName() ?? '');
                    $existingUser->setPrenom($facebookUser->getFirstName() ?? '');
                    $existingUser->setIsActive(true);
                    $existingUser->setPasswordHash('');
                    $existingUser->setRole('ROLE_USER');
                    $existingUser->setFacebookId($facebookUser->getId());
                    $existingUser->setLocalisation('Not specified');
                    $existingUser->setTel('0000000000');
                    
                    // Set the profile picture URL
                    $existingUser->setImage($pictureUrl);
                    $existingUser->setImageUrl($pictureUrl);
                    
                    $this->entityManager->persist($existingUser);
                    $this->entityManager->flush();
                } else {
                    // Update existing user's profile picture
                    $existingUser->setImage($pictureUrl);
                    $existingUser->setImageUrl($pictureUrl);
                    $this->entityManager->flush();
                }

                // Store in session
                $request->getSession()->set('id', $existingUser->getId());
                $request->getSession()->set('image', $pictureUrl);
                $request->getSession()->set('email', $existingUser->getEmail());
                $request->getSession()->set('nom', $existingUser->getNom());
                $request->getSession()->set('prenom', $existingUser->getPrenom());
                $request->getSession()->set('role', $existingUser->getRole());
                $request->getSession()->set('image', $existingUser->getImage());
                $request->getSession()->set('is_logged_in', true);

                return $existingUser;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Additional session data can be set here if needed
        $user = $token->getUser();
        $request->getSession()->set('user_localisation', $user->getLocalisation());
        $request->getSession()->set('user_tel', $user->getTel());

        return new RedirectResponse($this->router->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}