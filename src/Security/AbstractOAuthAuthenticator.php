<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Security\OAuthRegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;


abstract class AbstractOAuthAuthenticator extends OAuth2Authenticator 
// implements AuthenticationEntrypointInterface
{
    
    use TargetPathTrait;
    protected string $serviceName = "";

    public function __construct(
        private ClientRegistry $clientRegistry,
        private RouterInterface $router,
        private EntityManagerInterface $entityManager,
        private readonly UserRepository $repository,
        private readonly OAuthRegistrationService $registrationService
        )
    {
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    // public function supports(Request $request): ?bool
    // {
       
    //     return $request->attributes->get("_route") === "oauth_callback";
    // }

    public function supports(Request $request): ?bool
    {
        if ($request->attributes->get("_route") !== "oauth_callback") {
            return false;
        }

        $storedState = $request->getSession()->get('oauth_state');
        $receivedState = $request->query->get('state');

        if ($storedState !== $receivedState) {
            throw new \Exception('Invalid state parameter');
        }

        // Supprimer le state de la session une fois vérifié
        $request->getSession()->remove('oauth_state');

        return true;
    }


    public function authenticate(Request $request): SelfValidatingPassport
    {
        $client = $this->getClient();
        $accessToken = $this->fetchAccessToken($client);

        $resourceOwner = $this->getResourceOwnerFromCredentials($accessToken);
        $user = $this->getUserFromResourceOwner($resourceOwner, $this->repository);

        
        //si l'user n'existe pas, crée son compte
        if (null === $user) {
            $user = $this->registrationService->persist($resourceOwner);
        }

        return new SelfValidatingPassport(
            userBadge: new UserBadge($user->getEmail(), fn () => $user),
            badges: [
                new RememberMeBadge()
            ]
        );
    }

    public function getResourceOwnerFromCredentials(AccessToken $credentials): ResourceOwnerInterface
    {
        return $this->getClient()->fetchUserFromToken($credentials);
    }

    private function getClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient($this->serviceName);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        //targetPath est la page sur laquelle l'user était avant d'etre redirigé vers un login/register
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if($targetPath){
            return new RedirectResponse($targetPath);
        }
        

        $targetUrl = $this->router->generate('app_home');

        return new RedirectResponse($targetUrl);
    
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
    
    // public function start(Request $request, AuthenticationException $authException = null): Response
    // {
    //     return new RedirectResponse(
    //         '/connect/', // might be the site, where users choose their oauth provider
    //         Response::HTTP_TEMPORARY_REDIRECT
    //     );
    // }
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        // génére un state unique
        $state = bin2hex(random_bytes(16));
        
        // stocke le state dans la session
        $request->getSession()->set('oauth_state', $state);

        // url avec le state
        $authUrl = $this->router->generate('connect_oauth', [
            'service' => $this->serviceName,
            'state' => $state
        ]);

        return new RedirectResponse($authUrl, Response::HTTP_TEMPORARY_REDIRECT);
    }

    abstract protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $repository): ?User ;
    
}