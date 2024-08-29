<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\ProjectRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{

    #[Route('/search/{word}', name: 'app_search')]
    //renvoie le resultat de la recherche en fonction d'un mot, fonction dans chaque repository ; mettre par défaut word à null pour ne pas avoir d'erreur
    public function index(ProjectRepository $pr, ReservationRepository $rr, CsrfTokenManagerInterface $csrfTokenManager, $word = null): Response
    {
        //honey pot field
        $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        $tokenInput = filter_input(INPUT_POST, '_csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        
        $csrfTokenId = 'authenticate';

        //si le token de la session et du formulaire n'est pas le meme, redirige
        if (!$csrfTokenManager->isTokenValid(new CsrfToken($csrfTokenId, $tokenInput))) {
            $this->addFlash('error', 'Une erreur est apparue, veuillez réessayer');
            return $this->redirectToRoute('app_admin');
        }
        
        //si je recois "firstname" c'est un robot, je redirige
        if($honeypot){
            return $this->redirectToRoute('app_home');
        } 
        
        //utilise la methode get pour récupérer le mot tapé dans la barre de recherche
        //filtre
        $word = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS);

        if($word && $word !== " "){

            return $this->render('admin/research.html.twig', [
                'word' => $word,
                'projects' => $pr->findByWord($word),
                'reservations' => $rr->findByWord($word)
            ]);
        } else {
            return $this->redirectToRoute('app_admin');
        }
        
            
        
    }
}
