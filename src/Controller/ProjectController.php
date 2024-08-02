<?php
//----------------------------------------------------pannel admin qui gere exclusivement la partie suivi de projet-------------------------------------------------------

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Project;
use App\Entity\Quotation;
use App\Form\CommentType;
use App\Service\PdfService;
use App\Repository\StateRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{

    //liste des demandes reçues
    #[Route('/coiffe/projet', name: 'app_projet')]
    public function index(ProjectRepository $projectRepository): Response
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
    public function showProject(Project $project = null, Request $request, EntityManagerInterface $entityManager, UserInterface $user, StateRepository $stateRepository): Response
    {
        //si l'id passé dans l'url existe; possible comme je mets project en null par defaut en argument, sinon erreur
        if($project){

            //formulaire ajout d'un commentaire au suivi du projet

            $comment = new Comment();

            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request); 
    
            if($form->isSubmitted() && $form->isValid()){
                $comment=$form->getData();

                $comment->setProject($project); //remplit par le projet où se trouve l'utilisateur
                $comment->setUser($user); //remplit par l'utilisateur connecté
    
                $entityManager->persist($comment); //prepare
                $entityManager->flush(); //execute

                $this->addFlash('success', 'Commentaire ajouté');
                return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
            }
            
            return $this->render('admin/showProject.html.twig', [
                'project' => $project,
                'states' => $stateRepository->findBy([]),
                'form' => $form
            ]);

        } else {
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }
    }

    //modifie un commentaire du suivi
    #[Route('/coiffe/editComment/{id}', name: 'edit_comment')]
    public function editComment(Comment $comment =null, EntityManagerInterface $entityManager, Request $request){
        if($comment){

            $idProjet = $comment->getProject()->getId(); //recupere l'id du projet pour la redirection

            //recupere le commentaire dans l'input
            $content = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);
            //honey pot field
            $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        
            //si je recois "firstname" c'est un robot, je redirige
            if($honeypot){
                return $this->redirectToRoute('app_home');
            } else {
                $comment->setContent($content);
                $entityManager->flush();
    
                $this->addFlash('success', 'Commentaire modifié');
                return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
                
            }

        }
    }

    //supprime un commentaire du suivi
    #[Route('/coiffe/deleteComment/{id}', name: 'delete_comment')]
    public function deleteComment(Comment $comment = null, EntityManagerInterface $entityManager){
        //si le temoignage existe et qu'il n'est pas publié
        if($comment){

            $idProjet = $comment->getProject()->getId(); //recupere l'id du projet pour la redirection

            $entityManager->remove($comment); //prepare
            $entityManager->flush(); //execute
            
            // notif et redirection
            $this->addFlash('success', 'Commentaire supprimé');
            return $this->redirectToRoute('show_projet', ['id' => $idProjet]);

        } else {
            $this->addFlash('error', 'Ce commentaire n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }
    }

    //passe le projet en "a été contacté" ou "à contacter"
    #[Route('/coiffe/changeContactedProjet/{id}', name: 'change_contacted_projet')]
    public function changeContactedProjet(EntityManagerInterface $entityManager, Project $project = null){

        if($project){

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
        } else {
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }

    }

    //ajoute/modifie le prix final
    #[Route('/coiffe/fixePrix/{id}', name: 'fixe_prix')]
    public function setPrice(Project $project = null, EntityManagerInterface $entityManager, Request $request){

        if($project){

            //recupere le prix dans le formulaire
            // $price = $request->request->get('price');
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);

            $project->setFinalPrice($price);

            $entityManager->persist($project); //prepare
            $entityManager->flush(); //execute

            $this->addFlash('success', 'Prix final fixé');
            return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
        } else {
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }
    }


    // --------------------------------------------------------------------------DEVIS-------------------------------------------------------------------

    //crée un pdf devis (pour l'admin)
    #[Route('/coiffe/createDevisPdf/{id}', name: 'create_devis_pdf')]
    public function createDevisPdf(Project $project = null, PdfService $pdfService){
        if($project){
            //gere l'image
            $imagePath = $this->getParameter('kernel.project_dir') . '../public/img/logo/logo-noncropped.png';
            $imageData = base64_encode(file_get_contents($imagePath)); //encode
            
            $html = $this->renderView('pdf/devis.html.twig', [
                "project" => $project,
                "imageData" => $imageData
            ]);
            
            $domPdf = $pdfService->showPdf($html);
            
            $domPdf->stream("devis.pdf", array('Attachment' => 0));
            return new Response('', 200, [
                    'Content-Type' => 'application/pdf',
            ]);
        }  else {
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }
    }


    //enregistre le devis en base de donnée, factorisation de la fonction CreateDevis
    public function createQuotationBdd(Project $project, EntityManagerInterface $entityManager){

        $quotation = new Quotation();
        $quotation->setQuotationNumber(10);
        $quotation->setProject($project);

        $entityManager->persist($quotation); //prepare
        $entityManager->flush(); //execute
    }

    //crée le devis: l'enregistre dans la bdd, télécharge le pdf, envoie un mail au client et l'affiche sur le profil utilisateur
    #[Route('/coiffe/createDevis/{id}', name: 'create_devis')]
    public function createDevis(Project $project = null, EntityManagerInterface $entityManager){
        if($project){
            //enregistre dans la bdd
            $this->createQuotationBdd($project, $entityManager);

            $this->addFlash('success', 'Devis enregistré');
            return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
        }


    }

}
