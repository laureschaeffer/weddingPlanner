<?php

namespace App\Controller;

use App\Repository\BatchRepository;
use App\Repository\CreationRepository;
use App\Repository\PrestationRepository;
use App\Repository\ProductRepository;
use App\Repository\WorkerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{

    #[Route('/search/{word}', name: 'app_search')]
    //renvoie le resultat de la recherche en fonction d'un mot, fonction dans chaque repository ; mettre par défaut word à null pour ne pas avoir d'erreur
    public function index(BatchRepository $br, CreationRepository $cr, PrestationRepository $pr, ProductRepository $productRepository, WorkerRepository $wr, Request $request, $word = null): Response
    {
        //utilise la methode get pour récupérer le mot tapé dans la barre de recherche
        $word = $request->query->get('search');
        $cleanWord = htmlspecialchars(strip_tags($word), ENT_QUOTES, 'UTF-8');
        
        //honey pot field
        $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        
        //si je recois "firstname" c'est un robot, je redirige
        if($honeypot){
            return $this->redirectToRoute('app_home');
        } else {
            
            return $this->render('search/index.html.twig', [
                'word' => $cleanWord,
                'batchs' => $br->findByWord($word),
                'creations' => $cr->findByWord($word),
                'prestations' => $pr->findByWord($word),
                'products' => $productRepository->findByWord($word),
                'workers' => $wr->findByWord($word)
            ]);
        }
    }
}
