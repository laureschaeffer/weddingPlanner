<?php
// --------------------controller qui gère les principales vues de l'application : accueil, equipe, portefolio, prestations

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Creation;
use App\Entity\Testimony;
use App\Form\ProjectType;
use App\Form\TestimonyType;
use App\Repository\CategoryRepository;
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

        //liste des avis, seulement ceux qui ont été choisis par les admin
        $avis = $testimonyRepository->findBy(['isPublished' => 1]);

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

    //gère le formulaire d'un utilisateur qui donne son avis
    #[Route('/temoignage', name: 'app_temoignage')]
    public function newTestimony(TestimonyRepository $testimonyRepository, EntityManagerInterface $entityManager, Request $request){

        $testimony = new Testimony();

        //crée le formulaire
        $form = $this->createForm(TestimonyType::class, $testimony);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $testimony = $form->getData();

            $entityManager->persist($testimony); //prepare
            $entityManager->flush(); //execute

            //msg flash
            return $this->redirectToRoute('app_home');

        }

        //vue du formulaire
        return $this->render('home/temoignage.html.twig', [
            'form' => $form
        ]);

    }

    //-------------------------------------------------------------------------partie EQUIPE------------------------------------------------------------------------

    //liste de l'équipe
    #[Route('/equipe', name: 'app_worker')]
    public function listEquipe(WorkerRepository $workerRepository): Response
    {

        $workers = $workerRepository->findBy([]);
        return $this->render('home/listeEquipe.html.twig', [
            'workers' => $workers
        ]);
    }


    //-------------------------------------------------------------------------partie CREATION------------------------------------------------------------------------

    //liste des categories de creations, avec un aperçu 
    #[Route('/creation', name: 'app_creation')]
    public function listCategorieCreation(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findBy([]);
        return $this->render('home/listeCreation.html.twig', [
            'categories' => $categories
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

    //-------------------------------------------------------------------------partie PRESTATION------------------------------------------------------------------------
    
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
