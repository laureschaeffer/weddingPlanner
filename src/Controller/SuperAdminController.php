<?php
//------------------------------------------------------------------------pannel pour le "superadmin"---------------------------------------------------------------------
namespace App\Controller;

use App\Entity\User;
use App\Entity\Project;
use App\Form\EditProjectType;
use App\Repository\UserRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SuperAdminController extends AbstractController
{


    //----------------------------------------------partie utilisateurs--------------------------------
    
    //liste des utilisateurs sur le site
    #[Route('/super-coiffe/utilisateur', name: 'app_utilisateur')]
    public function index(UserRepository $userRepository): Response
    {
        $utilisateurs = $userRepository->findBy([]);

        return $this->render('super_admin/index.html.twig', [
            'utilisateurs' => $utilisateurs
        ]);
    }

    //change le role d'un utilisateur
    #[Route('super-coiffe/upgrade/{id}', name: 'upgrade_role')]
    public function upgradeUser(User $user, EntityManagerInterface $entityManager, Request $request, CsrfTokenManagerInterface $csrfTokenManager)
    {
        
        //utilise la methode post pour récupérer les elements cochés
        //filtre ce que je recupere
        $roleAdmin = filter_input(INPUT_POST, 'role_a', FILTER_SANITIZE_SPECIAL_CHARS);
        $roleSuperAdmin = filter_input(INPUT_POST, 'role_supera', FILTER_SANITIZE_SPECIAL_CHARS);
        //honey pot field
        $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        $tokenInput = filter_input(INPUT_POST, '_csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        
        $csrfTokenId = 'authenticate';

        //si le token de la session et du formulaire n'est pas le meme, redirige
        if (!$csrfTokenManager->isTokenValid(new CsrfToken($csrfTokenId, $tokenInput))) {
            $this->addFlash('error', 'Une erreur est apparue, veuillez réessayer');
            return $this->redirectToRoute('app_profil');
        }
        
        //si je recois "firstname" c'est un robot, je redirige
        if($honeypot){
            return $this->redirectToRoute('app_home');
        } else {
            $resultArray = [];
            //si role admin est coché
            if($roleAdmin){
                $resultArray[]= "ROLE_ADMIN";
            }
    
            //si role superadmin est coché
            if($roleSuperAdmin){
                $resultArray[]= "ROLE_SUPERADMIN";
            }
    
            //verifie si role acheteur est attribué à l'utilisateur ; comme ce n'est pas possible de le cocher il ne faut pas l'écraser
            if(in_array("ROLE_ACHETEUR", $user->getRoles())){
                $resultArray[]= "ROLE_ACHETEUR";
            }
            
            $user->setRoles($resultArray); //setter dans la classe User attend un tableau json format ["ROLE_USER", "ROLE_ADMIN"]
    
            $entityManager->persist($user); //prepare
            $entityManager->flush(); //execute
            
            
            // redirection
            $this->addFlash('success', 'Role changé');
            return $this->redirectToRoute('app_utilisateur');

        }
    }
    //----------------------------------------------partie projet--------------------------------
    //modifie un projet
    #[Route('/super-coiffe/editProjet/{id}', name: 'edit_projet')]
    public function editProjet(Project $project = null, EntityManagerInterface $entityManager, Request $request){

        //si le projet n'existe pas ou qu'il ne peut plus etre modifié
        if(!$project || !$project->isEditable()){
            $this->addFlash('error', 'Le projet n\'existe pas ou ne peut plus être modifié');
            return $this->redirectToRoute('app_projet');
        }

        //crée le formulaire
        $form = $this->createForm(EditProjectType::class, $project);
        $form->handleRequest($request); 
    
        if($form->isSubmitted() && $form->isValid()){

            $project=$form->getData();
            
            $entityManager->persist($project); //prepare
            $entityManager->flush(); //execute

            $this->addFlash('success', 'Projet modifié');
            return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);

        }
        
        //vue du formulaire
        return $this->render('super_admin/editProject.html.twig', [
            'form'=>$form
        ]);

        
    }
}
