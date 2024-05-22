<?php
// --------------------controller qui gère à la fois l'equipe et le portfolio (créations)

namespace App\Controller;

use App\Repository\WorkerRepository;
use App\Repository\CreationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkerCreationController extends AbstractController
{
    //-------------------------------------------------------------------------partie EQUIPE------------------------------------------------------------------------

    //liste de l'équipe
    #[Route('/worker', name: 'app_worker')]
    public function index(WorkerRepository $workerRepository): Response
    {

        $workers = $workerRepository->findBy([]);
        return $this->render('worker_creation/index.html.twig', [
            'workers' => $workers
        ]);
    }


    //-------------------------------------------------------------------------partie CREATION------------------------------------------------------------------------

    //liste des créations
    #[Route('/creation', name: 'app_creation')]
    public function listCreation(CreationRepository $creationRepository): Response
    {

        $creations = $creationRepository->findBy([]);
        return $this->render('worker_creation/listeCreations.html.twig', [
            'creations' => $creations
        ]);

    }
}
