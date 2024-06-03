<?php
//------------------------------------------------------------------------pannel admin---------------------------------------------------------------------
namespace App\Controller;

use App\Entity\User;
use App\Entity\Project;
use App\Entity\Reservation;
use App\Entity\Testimony;
use App\Form\ReservationEditType;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use App\Repository\ReservationRepository;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
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
    public function changeTestimonyState(EntityManagerInterface $entityManager, Testimony $testimony = null, TestimonyRepository $testimonyRepository){

        //trouve le nb de témoignages publiés
        $nbAvisPublies = count($testimonyRepository->findBy(['isPublished' => 1]));

        //si isPublished est vrai, on le passe en faux
        if($testimony->isPublished()){
            $testimony->setPublished(false);
        } else {
            //verifie que le nb de témoignage publié ne depasse pas 5
            if($nbAvisPublies <= 5){
                
                $testimony->setPublished(true);
            } else {
                $this->addFlash('error', 'Le nombre limite autorisé de témoignages publiés est dépassé');
                return $this->redirectToRoute('app_avis');
            }
        }

        $entityManager->persist($testimony);
        $entityManager->flush();

        $this->addFlash('success', 'Statut de l\'avis du couple ' . $testimony->getCoupleName() . ' modifié');
        return $this->redirectToRoute('app_avis');

    }

    //supprimer un avis de la bdd (non publiés)
    #[Route('/coiffe/supprAvis/{id}', name: 'remove_avis')] 
    public function deleteProgramme(Testimony $testimony = null, EntityManagerInterface $entityManager){
        
        //si le temoignage existe et qu'il n'est pas publié
        if($testimony && $testimony->isPublished() == false){
            $entityManager->remove($testimony); //prepare
            $entityManager->flush(); //execute
            
            // notif et redirection
            $this->addFlash('success', 'Avis supprimé');
            return $this->redirectToRoute('app_avis');

        } else {
            $this->addFlash('error', 'Ce témoignage n\'existe pas, ou doit d\'abord être dépublié');
            return $this->redirectToRoute('app_avis');
        }
    }

    //----------------------------------------------partie utilisateurs--------------------------------
    
    //liste des utilisateurs sur le site
    #[Route('/coiffe/utilisateur', name: 'app_utilisateur')]
    public function listUser(UserRepository $userRepository): Response
    {
        $utilisateurs = $userRepository->findBy([]);

        return $this->render('admin/listeUser.html.twig', [
            'utilisateurs' => $utilisateurs
        ]);
    }

    //change le role d'un utilisateur
    #[Route('coiffe/upgrade/{id}', name: 'upgrade_role')]
    public function upgradeUser(User $user, EntityManagerInterface $entityManager, Request $request)
    {
        
        //utilise la methode post pour récupérer les elements cochés
        $roleAdmin = $request->request->get('role_a');
        
        $resultArray = [];
        //si role admin est coché
        if($roleAdmin){
            $resultArray[]= "ROLE_ADMIN";
        }
        
        $user->setRoles($resultArray); //setter dans la classe User attend un tableau json format ["ROLE_USER", "ROLE_ADMIN"]

        $entityManager->persist($user); //prepare
        $entityManager->flush(); //execute
        
        
        // redirection
        $this->addFlash('success', 'Role changé');
        return $this->redirectToRoute('app_utilisateur');
    }

    //----------------------------------------------partie réservation de commandes--------------------------------

    //liste des reservations de commandes
    #[Route('coiffe/commande', name: 'app_commande')]
    public function listCommande(ReservationRepository $reservationRepository): Response
    {
        
        $reservationsAPreparer = $reservationRepository->findBy(['isPrepared' => 0], ['datePicking' => 'ASC']);
        $reservationsPassees = $reservationRepository->findBy(['isPrepared' => 1], ['dateOrder' => 'ASC']);

        return $this->render('admin/listeCommande.html.twig', [
            'reservationsAPreparer' => $reservationsAPreparer,
            'reservationsPassees' => $reservationsPassees
        ]);
        
    }

    //modifie la commande
    #[Route('coiffe/editCommande/{id<\d+>}', name: 'edit_commande')]
    public function editCommande(Request $request, EntityManagerInterface $entityManager, Reservation $reservation): Response
    {

        //crée le formulaire
        $form = $this->createForm(ReservationEditType::class, $reservation);
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){
            $reservation=$form->getData();

            $entityManager->persist($reservation); //prepare
            $entityManager->flush(); //execute

            //msg flash
            return $this->redirectToRoute('app_home');
        }

        //vue du formulaire
        return $this->render('admin/editReservation.html.twig', [
            'form'=>$form
        ]);
    }

    //detail d'une reservation
    #[Route('coiffe/commande/{id<\d+>}', name: 'show_commande')]
    public function showCommande(Reservation $reservation = null): Response
    {

        //si l'id passé dans l'url existe
        if($reservation){
            return $this->render('admin/showReservation.html.twig', [
                'reservation' => $reservation
            ]);

        } else {
            $this->addFlash('error', 'Cette commande n\'existe pas');
            return $this->redirectToRoute('app_commande');
        }
    }

    //passer une commande en préparée ou non préparée
    #[Route('coiffe/changeCommandePrepared/{id<\d+>}', name: 'change_commande_prepared')]
    public function changeCommandePrepared(Reservation $reservation =null, EntityManagerInterface $entityManager){

        //si la commande existe
        if($reservation){

            //si la reservation est préparée, la passer en non préparée, ; sinon inverse
            if($reservation->isPrepared()){
                $reservation->setPrepared(false);
            } else {
                $reservation->setPrepared(true);
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Statut de la commande changé');
            return $this->redirectToRoute('show_commande', ['id' => $reservation->getId()]);

        } else {
            $this->addFlash('error', 'Cette commande n\'existe pas');
            return $this->redirectToRoute('app_commande');
        }

    }


    //passer une commande en récupérée ou non récupérée
    #[Route('coiffe/changeCommandePicked/{id<\d+>}', name: 'change_commande_picked')]
    public function changeCommandePicked(Reservation $reservation =null, EntityManagerInterface $entityManager){

        //si la commande existe
        if($reservation){

            //si la reservation est préparée, la passer en non préparée, ; sinon inverse
            if($reservation->isPicked()){
                $reservation->setPicked(false);
            } else {
                $reservation->setPicked(true);
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Statut de la commande changé');
            return $this->redirectToRoute('show_commande', ['id' => $reservation->getId()]);

        } else {
            $this->addFlash('error', 'Cette commande n\'existe pas');
            return $this->redirectToRoute('app_commande');
        }

    }
}
