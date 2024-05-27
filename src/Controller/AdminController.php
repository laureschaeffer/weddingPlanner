<?php
//------------------------------------------------------------------------pannel admin---------------------------------------------------------------------
namespace App\Controller;

use App\Entity\Project;
use App\Entity\Testimony;
use App\Repository\ProjectRepository;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/coiffe', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }

    //----------------------------------------------partie demandes de contact--------------------------------

    //liste des demandes reçues
    #[Route('/coiffe/projet', name: 'app_projet')]
    public function listProject(ProjectRepository $projectRepository): Response
    {
        $projetNonTraites = $projectRepository->findBy(['isContacted' => 0], ['dateEvent' => 'ASC']);
        $projetTraites = $projectRepository->findBy(['isContacted' => 1], ['dateEvent' => 'ASC']);

        return $this->render('admin/listeProject.html.twig', [
            'projetNonTraites' => $projetNonTraites,
            'projetTraites' => $projetTraites
        ]);
    }

    //detail d'une demande
    #[Route('/coiffe/projet/{id}', name: 'show_projet')]
    public function showProject(Project $project = null): Response
    {
        //si l'id passé dans l'url existe; possible comme je mets project en null par defaut en argument, sinon erreur
         if($project){
            return $this->render('admin/showProject.html.twig', [
                'project' => $project
            ]);

        } else {
            //msg d'erreur
            return $this->redirectToRoute('app_creation');
        }
    }

    //change la demande de contact en traitée ou repasse en à traiter
    #[Route('/coiffe/changeProjet/{id}', name: 'change_projet')]
    public function changeProjectState(EntityManagerInterface $entityManager, Project $project = null){

        //si isContacted est vraie, on le passe en faux
        if($project->isContacted()){
            $project->setContacted(false);
        } else {
            $project->setContacted(true);
        }

        $entityManager->persist($project);
        $entityManager->flush();

        $this->addFlash('success', 'Statut de la demande changé');
        return $this->redirectToRoute('show_projet', ['id'=>$project->getId()]);

    }


    //----------------------------------------------partie témoignages--------------------------------

    //affiche la page pour verifier les avis
    #[Route('/coiffe/avis', name: 'app_avis')]
    public function listTestimony(TestimonyRepository $testimonyRepository): Response
    {
        //liste des avis publiés ou non publiés
        $avisPublies = $testimonyRepository->findBy(['isPublished' => 1]);
        $avisNonPublies = $testimonyRepository->findBy(['isPublished' => 0]);

        return $this->render('admin/listeTemoignage.html.twig', [
            'avisPublies' => $avisPublies,
            'avisNonPublies' => $avisNonPublies
        ]);
    }

    //change l'avis en publié ou non publié
    #[Route('/coiffe/changeAvis/{id}', name: 'change_avis')]
    public function changeTestimonyState(EntityManagerInterface $entityManager, Testimony $testimony = null){

        //si isPublished est vraie, on le passe en faux
        if($testimony->isPublished()){
            $testimony->setPublished(false);
        } else {
            $testimony->setPublished(true);
        }

        $entityManager->persist($testimony);
        $entityManager->flush();

        $this->addFlash('success', 'Statut de l\'avis du couple ' . $testimony->getCoupleName() . ' modifié');
        return $this->redirectToRoute('app_avis');

    }
}
