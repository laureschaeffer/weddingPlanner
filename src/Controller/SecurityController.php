<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\User;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
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
        // return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
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
    //grâce à UserInterface l'appli récupère directement le profil de la personne connectée, ce qui permet de ne pas passer d'id dans l'url
    #[Route('/delete', name: 'delete_profil')]
    public function delete(UserInterface $user, EntityManagerInterface $entityManager){

        $entityManager->remove($user); //prepare
        $entityManager->flush(); //execute


        $this->addFlash('success', 'Profil supprimé');
        return $this->redirectToRoute('app_home');


    }
}
