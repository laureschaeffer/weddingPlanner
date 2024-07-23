<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    //--------------------------------------------------propre à google authentification-------------------------------------------
    public const SCOPES = [
        'google' => []
    ];

    // #[Route(path: '/oauth/connect/{service}', name: 'auth_oauth_connect')]
    // public function connect(string $service, ClientRegistry $clientRegistry): RedirectResponse
    // {

    //     //si le service auquel l'utilisateur essaye de se connecter existe
    //     //condition indispensable, sinon la méthode redirect envoie une exception qui empeche définitivement de se reconnecter
    //     if( ! in_array($service, array_keys(array: self::SCOPES), strict: true)){
    //         throw $this->createNotFoundException();
    //     }

    //     return $clientRegistry
    //     ->getClient($service)
    //     ->redirect(self::SCOPES[$service], []);
        
    // }

    #[Route(path: '/oauth/connect/{service}', name: 'auth_oauth_connect')]
    public function connect(string $service, ClientRegistry $clientRegistry, SessionInterface $session ): RedirectResponse
    {
        // si le service est valide
        if (!in_array($service, array_keys(self::SCOPES), strict: true)) {
            throw $this->createNotFoundException();
        }

        // génère un state unique
        $state = bin2hex(random_bytes(16));
        
        // stock le state dans la session
        $session->set('oauth_state', $state);

        return $clientRegistry
            ->getClient($service)
            ->redirect(self::SCOPES[$service], ['state' => $state]);
    }
    

    #[Route('/oauth/check/{service}', name: 'auth_oauth_check')]
    public function check(): Response
    {
        return new Response(status: 200);
    }

    //-------------------------------------------------------------general-------------------------------------------------------------

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('error', 'Vous êtes déjà connecté');
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', ['error' => $error]);
    }

    

    //profil utilisateur
    #[Route('/profil', name: 'app_profil')]
    public function profil() : Response
    {
        return $this->render('security/profil.html.twig');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');

        $this->addFlash('success', 'Vous êtes déconnecté');
        $this->redirectToRoute("app_login"); 
    }

    //anonymisation des données du profil
    #[Route('/delete', name: 'delete_profil')]
    public function delete(UserInterface $user, EntityManagerInterface $entityManager){

        $entityManager->remove($user); //prepare
        $entityManager->flush(); //execute


        $this->addFlash('success', 'Profil supprimé');
        return $this->redirectToRoute('app_home');


    }
}
