<?php
// --------------------controller qui gère les principales vues de la page : accueil, equipe, portefolio, prestations

namespace App\Controller;

use App\Entity\Creation;
use App\Repository\WorkerRepository;
use App\Repository\CreationRepository;
use App\Repository\PrestationRepository;
use App\Repository\TestimonyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    //-------------------------------------------------------------------------page d'accueil------------------------------------------------------------------------

    //accueil
    #[Route('/', name: 'app_home')]
    public function index(TestimonyRepository $testimonyRepository): Response
    {

        //liste des avis
        $avis = $testimonyRepository->findBy([]);

        return $this->render('home/index.html.twig', [
            'avis' => $avis
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

        // $creations = $creationRepository->findBy(['category'=> 1]);
        $creations = $creationRepository->findCreationByCategory();
        return $this->render('home/listeCreation.html.twig', [
            'creations' => $creations
        ]);

    }

    //detail d'une creation choisie
    #[Route('/creation/{id}', name: 'show_creation')]
    public function showCreation(Creation $creation = null, CreationRepository $creationRepository): Response
    {

        //si l'id passé dans l'url existe; possible comme je mets creation en null par defaut en argument, sinon erreur
        if($creation){
            return $this->render('home/showCreation.html.twig', [
                'creation' => $creation
            ]);

        } else {
            //msg d'erreur
            return $this->redirectToRoute('app_creation');
        }
    }

    //-------------------------------------------------------------------------partie CREATION------------------------------------------------------------------------
    
    //liste des prestations
    #[Route('/prestation', name: 'app_prestation')]
    public function listPrestation(PrestationRepository $prestationRepository) : Response 
    {

        $prestations = $prestationRepository->findBy([]);
        return $this->render('home/listePrestation.html.twig', [
            'prestations' => $prestations
        ]);
    }
    
}
