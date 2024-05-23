<?php
// --------------------controller qui gère les principales vues de l'application : accueil, equipe, portefolio, prestations

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Creation;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\WorkerRepository;
use App\Repository\ProjectRepository;
use App\Repository\CreationRepository;
use App\Repository\TestimonyRepository;
use App\Repository\PrestationRepository;
use Symfony\Component\HttpFoundation\Request;
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

    //gère le formulaire de contact pour parler d'un projet
    #[Route('/contact', name: 'app_contact')]
    public function newProject(ProjectRepository $projectRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $project = new Project();

        //crée le formulaire
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){
            $project=$form->getData();

            $entityManager->persist($project); //prepare
            $entityManager->flush(); //execute

            //msg flash
            return $this->redirectToRoute('app_home');
        }

        //vue du formulaire
        return $this->render('home/contact.html.twig', [
            'form'=>$form
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
    public function showCreation(Creation $creation = null): Response
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
