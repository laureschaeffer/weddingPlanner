<?php
// --------------------controller qui gère les principales vues de la page : accueil, equipe, portefolio

namespace App\Controller;

use App\Repository\WorkerRepository;
use App\Repository\CreationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    //-------------------------------------------------------------------------page d'accueil------------------------------------------------------------------------
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    //-------------------------------------------------------------------------partie EQUIPE------------------------------------------------------------------------

    //liste de l'équipe
    #[Route('/worker', name: 'app_worker')]
    public function listEquipe(WorkerRepository $workerRepository): Response
    {

        $workers = $workerRepository->findBy([]);
        return $this->render('home/listeEquipe.html.twig', [
            'workers' => $workers
        ]);
    }


    //-------------------------------------------------------------------------partie CREATION------------------------------------------------------------------------

    //liste des créations
    #[Route('/creation', name: 'app_creation')]
    public function listCreation(CreationRepository $creationRepository): Response
    {

        $creations = $creationRepository->findBy([]);
        return $this->render('home/listeCreations.html.twig', [
            'creations' => $creations
        ]);

    }
}
