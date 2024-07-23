<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\AbstractOAuthAuthenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GoogleAuthenticator extends AbstractOAuthAuthenticator
{
    protected string $serviceName= "google";

    //si l'utilisateur utilise un compte google qui a déjà été enregistré, retourne son compte ; sinon il va falloir le créer
    protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $repository): ?User
    {
        //si user ne vient pas de google
        if(!($resourceOwner instanceof GoogleUser)){
            throw New \RuntimeException(message: "En attente d'un utilisateur google");
        }

        if(true !== ($resourceOwner->toArray()['email_verified'] ?? null )) {
            throw New AuthenticationException(message: "email non vérifié");
        }

        return $repository->findOneBy([
            'google_id' => $resourceOwner->getId(),
            'email' => $resourceOwner->getEmail()
        ]);

    }
    // public function supports(Request $request): ?bool
    // {
    //     // TODO: Implement supports() method.
    // }

    // public function authenticate(Request $request): Passport
    // {
    //     // TODO: Implement authenticate() method.
    // }

    // public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    // {
    //     // TODO: Implement onAuthenticationSuccess() method.
    // }

    // public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    // {
    //     // TODO: Implement onAuthenticationFailure() method.
    // }

    //    public function start(Request $request, AuthenticationException $authException = null): Response
    //    {
    //        /*
    //         * If you would like this class to control what happens when an anonymous user accesses a
    //         * protected page (e.g. redirect to /login), uncomment this method and make this class
    //         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
    //         *
    //         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
    //         */
    //    }
}
