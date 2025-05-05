<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends AbstractAuthenticator
{
    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator   )
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;

    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $client->getAccessToken();
        $googleUser = $client->fetchUserFromToken($accessToken);
        $googleData = $googleUser->toArray();

        /** @var GoogleUser $googleUser */
        $email = $googleUser->getEmail();

        return new SelfValidatingPassport(
            new UserBadge($email, function (string $userIdentifier) use ($googleUser, $googleData, $request) {
                $existingUser = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $userIdentifier]);

                if ($existingUser) {
                    $session = $request->getSession();
                    $session->set('id', $existingUser->getId());
                    $session->set('nom', $existingUser->getNom());
                    $session->set('prenom', $existingUser->getPrenom());
                    $session->set('email', $existingUser->getEmail());
                    $session->set('image', $googleData['picture'] ?? null);
                   
                   
                    return $existingUser;
                }

                $user = new Users();
                $user->setEmail($googleUser->getEmail());
                $user->setNom($googleUser->getFirstName() ?? '');
                $user->setPrenom($googleUser->getLastName() ?? '');
                $user->setLocalisation($googleData['locale'] ?? '');  // Changed from null to empty string
                $user->setRole('USER');
                $user->setPasswordHash('');
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $session = $request->getSession();
                $session->set('id', $user->getId());
                $session->set('nom', $user->getNom());
                $session->set('prenom', $user->getPrenom());
                $session->set('email', $user->getEmail());
                $session->set('image', $googleData['picture'] ?? null);

                return $user;
            }),
            [new RememberMeBadge()]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        $user = $token->getUser();
       
        if ($user instanceof Users) {
            // Store user ID in session for success case
            $request->getSession()->set('user_id', $user->getId());
            return new RedirectResponse($this->urlGenerator->generate('app_users_editProfile', [
                'id' => $user->getId()
            ]));
        }
       
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?RedirectResponse
    {
        // Get user ID from session if available
        $userId = $request->getSession()->get('user_id');
       
        if ($userId) {
            return new RedirectResponse($this->urlGenerator->generate('app_users_editProfile', [
                'id' => $userId
            ]));
        }
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
}