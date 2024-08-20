<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class SecurityController extends AbstractController
{

    //--------------------------------------------------propre à google authentification-------------------------------------------
    public const SCOPES = [
        'google' => []
    ];

    #[Route(path: '/connect/google', name: 'auth_oauth_connect')]
    public function connect(ClientRegistry $clientRegistry)
    {


        return $clientRegistry
            ->getClient('google')
            ->redirect([], []);
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


    //traite le formulaire
    #[Route('/modifieProfil', name: 'edit_profil')]
    public function editProfil(Request $request, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        //filtre
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_SPECIAL_CHARS); 
        $tokenInput = filter_input(INPUT_POST, '_csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);

        $csrfTokenId = 'authenticate';

        //si le token de la session et du formulaire n'est pas le meme, déconnecte
        if (!$csrfTokenManager->isTokenValid(new CsrfToken($csrfTokenId, $tokenInput))) {
            // throw new InvalidCsrfTokenException('Token CSRF invalide.');
            return $this->redirectToRoute('app_logout');
        }
    
        if(!$pseudo) {
            $this->addFlash('error', 'Pseudo invalide');
            return $this->redirectToRoute('edit_profil');
        } 

        $newPseudo = str_replace(" ", "", $pseudo); //supprime les espaces
        $this->getUser()->setPseudo($newPseudo);

        $entityManager->flush();
        $this->addFlash('success', 'Informations modifiées');
        return $this->redirectToRoute('app_profil');

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
