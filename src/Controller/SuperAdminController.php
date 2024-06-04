<?php
//------------------------------------------------------------------------pannel pour le "superadmin"---------------------------------------------------------------------
namespace App\Controller;

use App\Entity\User;
use App\Entity\Project;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SuperAdminController extends AbstractController
{
    #[Route('/super-coiffe', name: 'app_super_admin')]
    public function index(): Response
    {
        return $this->render('super_admin/index.html.twig', [
            'controller_name' => 'SuperAdminController',
        ]);
    }

    //----------------------------------------------partie demandes de contact--------------------------------

    //change la demande de contact en finie, ou non finie
    #[Route('/super-coiffe/changeProjet/{id}', name: 'change_projet')]
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

    //----------------------------------------------partie utilisateurs--------------------------------
    
    //liste des utilisateurs sur le site
    #[Route('/super-coiffe/utilisateur', name: 'app_utilisateur')]
    public function listUser(UserRepository $userRepository): Response
    {
        $utilisateurs = $userRepository->findBy([]);

        return $this->render('admin/listeUser.html.twig', [
            'utilisateurs' => $utilisateurs
        ]);
    }

    //change le role d'un utilisateur
    #[Route('super-coiffe/upgrade/{id}', name: 'upgrade_role')]
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
}
