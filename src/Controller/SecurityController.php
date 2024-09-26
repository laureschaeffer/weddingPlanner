<?php

namespace App\Controller;

use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager) {
    }
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
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
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
    public function editProfil(CsrfTokenManagerInterface $csrfTokenManager)
    {
        if(!$this->getUser()){
            $this->addFlash('error', 'Veuillez vous connecter');
            return $this->redirectToRoute('app_login');
        }
        //filtre
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_SPECIAL_CHARS); 
        $tokenInput = filter_input(INPUT_POST, '_csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        //honey pot field
        $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        
        //si je recois "firstname" c'est un robot, je redirige
        if($honeypot){
            return $this->redirectToRoute('app_home');
        } 

        $csrfTokenId = 'authenticate';

        //si le token de la session et du formulaire n'est pas le meme, redirige
        if (!$csrfTokenManager->isTokenValid(new CsrfToken($csrfTokenId, $tokenInput))) {
            $this->addFlash('error', 'Une erreur est apparue, veuillez réessayer');
            return $this->redirectToRoute('app_profil');
        }
    
        if(!$pseudo) {
            $this->addFlash('error', 'Pseudo invalide');
            return $this->redirectToRoute('edit_profil');
        } 

        $newPseudo = str_replace(" ", "", $pseudo); //supprime les espaces
        $this->getUser()->setPseudo($newPseudo);

        $this->entityManager->flush();
        $this->addFlash('success', 'Informations modifiées');
        return $this->redirectToRoute('app_profil');

    }

    //anonymisation des données du profil avec l'algorithme sha256
    #[Route('/delete', name: 'delete_profil')]
    public function delete(StateRepository $stateRepository, CsrfTokenManagerInterface $csrfTokenManager){
        //-----securité honeypot et faille csrf-----
        //honey pot field
        $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        $tokenInput = filter_input(INPUT_POST, '_csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        
        $csrfTokenId = 'authenticate';

        //si le token de la session et du formulaire n'est pas le meme, redirige
        if (!$csrfTokenManager->isTokenValid(new CsrfToken($csrfTokenId, $tokenInput))) {
            $this->addFlash('error', 'Une erreur est apparue, veuillez réessayer');
            return $this->redirectToRoute('app_home');
        }
        
        //si je recois "firstname" c'est un robot, je redirige
        if($honeypot){
            return $this->redirectToRoute('app_home');
        }
        
        $user = $this->getUser();

        //----------------entité USER----------------
        $user->setPseudo(hash('sha224', $user->getPseudo()));
        $user->setEmail(hash('sha224', $user->getEmail()));
        $user->setRoles(["ROLE_SUPPRIME"]);
        $user->setGoogleUser(false);

        $this->entityManager->persist($user);

        //----------------entité PROJECT----------------
        $userProjects = $user->getProjects();

        
        if($userProjects){
            foreach($userProjects as $project){
                $project->setUser(NULL);
                $project->setFirstname(hash('sha224', $project->getFirstname()));
                $project->setSurname(hash('sha224', $project->getSurname()));
                //l'email est optionnel dans l'entité Project
                $projectEmail = $project->getEmail() ? $project->getEmail() : "";
                $project->setEmail(hash('sha224', $projectEmail));
                $project->setTelephone(hash('sha224', $project->getTelephone()));

                $stateRefuse = $stateRepository->findOneBy(['id' => 4]);
                $project->setState($stateRefuse);

                $project->setContacted(true);
                
                $this->entityManager->persist($project);
            }
        }


        //----------------entité RESERVATION----------------
        $userReservations = $user->getReservations();

        if($userReservations){
            foreach($userReservations as $reservation){
                $reservation->setUser(NULL);
                $reservation->setFirstname(hash('sha224', $reservation->getFirstname()));
                $reservation->setSurname(hash('sha224', $reservation->getSurname()));
                $reservation->setTelephone(hash('sha224', $reservation->getTelephone()));
                $reservation->setPrepared(true);
                $reservation->setPicked(true);
                
                $this->entityManager->persist($reservation);
            }
        }
        

        $this->entityManager->flush(); //execute

        $this->addFlash('success', 'Profil supprimé');
        return $this->redirectToRoute('app_home');


    }
}
