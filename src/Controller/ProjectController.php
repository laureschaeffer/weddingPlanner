<?php
//----------------------------------------- gere la partie suivi de projet----------------------------------------------------

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Comment;
use App\Entity\Project;
use App\Entity\Quotation;
use App\Form\CommentType;
use App\Service\PdfService;
use App\Service\UniqueIdService;
use App\Repository\BillRepository;
use App\Repository\StateRepository;
use Symfony\Component\Mime\Address;
use App\Repository\ProjectRepository;
use App\Repository\QuotationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
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
    public function showProject(Project $project = null, Request $request, EntityManagerInterface $entityManager, UserInterface $user, BillRepository $billRepository): Response
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
                'form' => $form
            ]);

        } else {
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }
    }

    //modifie un commentaire du suivi
    #[Route('/coiffe/editComment/{id}', name: 'edit_comment')]
    public function editComment(Comment $comment =null, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager){
        if($comment){

            $idProjet = $comment->getProject()->getId(); //recupere l'id du projet pour la redirection
            $tokenInput = filter_input(INPUT_POST, '_csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        
            $csrfTokenId = 'authenticate';

            //si le token de la session et du formulaire n'est pas le meme, redirige
            if (!$csrfTokenManager->isTokenValid(new CsrfToken($csrfTokenId, $tokenInput))) {
                $this->addFlash('error', 'Une erreur est apparue, veuillez réessayer');
                return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
            }

            //si le projet n'est pas modifiable
            if(!$comment->getProject()->isEditable()){
                $this->addFlash('error', 'L\'état du projet ne permet plus de le modifier');
                return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
            }

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

            //si le projet n'est pas modifiable
            if(!$comment->getProject()->isEditable()){
                $this->addFlash('error', 'L\'état du projet ne permet plus de le modifier');
                return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
            }

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
            //si le projet n'est pas modifiable
            if(!$project->isEditable()){
                $this->addFlash('error', 'L\'état du projet ne permet plus de le modifier');
                return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
            }

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

    //aperçu du futur devis en pdf (pour l'admin)
    #[Route('/coiffe/createDevisPdf/{id}', name: 'create_devis_pdf')]
    public function createDevisPdf(Project $project = null, PdfService $pdfService){
        if($project){
            //gere l'image
            $imagePath = $this->getParameter('kernel.project_dir') . '../public/img/logo/logo.png';
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

    //sur le profil utilisateur, montre le devis finalisé (PDF)
    #[Route('/devisFinal/{id}', name: 'show_devis')]
    public function showDevis(Quotation $quotation = null, PdfService $pdfService){
        if($quotation){

            //si l'utilisateur connecté n'est pas le propriétaire du projet/devis
            if($this->getUser()!== $quotation->getProject()->getUser()){
                $this->addFlash('error', 'Vous n\'avez pas le droit de voir ce devis');
                return $this->redirectToRoute('app_home');
            }

            //gere l'image
            $imagePath = $this->getParameter('kernel.project_dir') . '../public/img/logo/logo.png';
            $imageData = base64_encode(file_get_contents($imagePath)); //encode

            //projet associé au devis
            $project = $quotation->getProject();
            
            $html = $this->renderView('pdf/devisFinal.html.twig', [
                "project" => $project,
                'quotation' => $quotation,
                "imageData" => $imageData
            ]);
            
            $domPdf = $pdfService->showPdf($html);
            
            $domPdf->stream("devis.pdf", array('Attachment' => 0));
            return new Response('', 200, [
                    'Content-Type' => 'application/pdf',
            ]);
        } else {
            $this->addFlash('error', 'Ce devis n\'existe pas');
            return $this->redirectToRoute('app_projet');
        } 
    }
    
    //sur le profil utilisateur, montre le devis finalisé (HTML)
    #[Route('/devisFinalHtml/{id}', name: 'show_devis_html')]
    public function showDevisHtml(Quotation $quotation = null, PdfService $pdfService){
        if(!$quotation){
            $this->addFlash('error', 'Ce devis n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }
        
        //si l'utilisateur connecté n'est pas le propriétaire du projet/devis
        if($this->getUser()!== $quotation->getProject()->getUser()){
            $this->addFlash('error', 'Vous n\'avez pas le droit de voir ce devis');
            return $this->redirectToRoute('app_home');
        }

        //gere l'image
        $imagePath = $this->getParameter('kernel.project_dir') . '../public/img/logo/logo.png';
        $imageData = base64_encode(file_get_contents($imagePath)); //encode

        //projet associé au devis
        $project = $quotation->getProject();

        return $this->render('/pdf/devis.html.twig', [
            'quotation' => $quotation,
            'project' => $project,
            'imageData' => $imageData
        ]);

          
    }


    //enregistre le devis en base de donnée, factorisation de la fonction createDevis
    public function createQuotationBdd(Project $project, EntityManagerInterface $entityManager, $uniqueIdService){

        $quotation = new Quotation();
        $quotation->setProject($project);
        $quotationNumber = "DEV_" . $uniqueIdService->generateUniqueId(); //crée un nom unique aléatoire
        $quotation->setQuotationNumber($quotationNumber);

        $entityManager->persist($quotation); //prepare
        return $quotation;
    }

    //télécharge dans le dossier upload/devis le devis, factorisation de la fonction createDevis
    public function downloadDevis($quotation, $project, $pdfService, $uniqueIdService){
        // gère l'image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/logo/logo.png';
        $imageData = base64_encode(file_get_contents($imagePath));

        
        $html = $this->renderView('pdf/devisFinal.html.twig', [
            "project" => $project,
            'quotation' => $quotation,
            "imageData" => $imageData
        ]);
        
        $domPdf = $pdfService->showPdf($html);
        
        $uniqueId = $uniqueIdService->generateUniqueId();
        // génère un nom de fichier unique
        $filename = 'devis_' . $uniqueId . '.pdf';
        
        // chemin complet
        $filePath = $this->getParameter('kernel.project_dir') . '/public/upload/devis/' . $filename;
        
        // sauvegarde le pdf
        file_put_contents($filePath, $domPdf->output());
        
        // télécharge le fichier
        return new BinaryFileResponse($filePath);

    }


    //crée le devis: l'enregistre dans la bdd, télécharge le pdf, envoie un mail au client et l'affiche sur le profil utilisateur
    #[Route('/coiffe/createDevis/{id}', name: 'create_devis')]
    public function createDevis(Project $project = null, EntityManagerInterface $entityManager, StateRepository $stateRepository, MailerInterface $mailer, PdfService $pdfService, UniqueIdService $uniqueIdService){
        if(!$project){
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }
        
        //si le projet n'est pas modifiable
        if(!$project->isEditable()){
            $this->addFlash('error', 'L\'état du projet ne permet plus de le modifier');
            return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
        }
        //il faut absolument avoir établi un prix final
        if($project->getFinalPrice() == NULL){
            
            $this->addFlash('error', 'Veuillez fixer un prix final !');
            return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
        }
        //----enregistre dans la bdd
        $quotation = $this->createQuotationBdd($project, $entityManager, $uniqueIdService);

        //----change le statut du projet de "en cours" à "en attente" (d'une reponse du client)
        $stateEnAttente = $stateRepository->findOneBy(['id' => 2]);
        $project->setState($stateEnAttente);
        $entityManager->persist($project);

        //----envoie d'un mail
        $emailContact = $project->getEmail()==! NULL ? $project->getEmail() : $project->getUser()->getEmail();
        $email = (new TemplatedEmail())
        ->from(new Address('admin-ceremonie-couture@exemple.fr', 'Ceremonie Couture Bot'))
        ->to($emailContact)
        ->subject('Vous avez reçu un devis')

        ->context([
            'project' => $project
        ])
        ->htmlTemplate('email/informationDevis.html.twig')
        ;

        $mailer->send($email);

        //----télécharge en local le devis pdf
        $this->downloadDevis($quotation, $project, $pdfService, $uniqueIdService);

        $entityManager->flush(); //execute

        $this->addFlash('success', 'Devis créé et enregistré');
        return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
        
    }

    //-----------------------------------------------------FACTURE--------------------------------------
    //télécharge dans le dossier upload/facture le facture, factorisation de la fonction accepteDevis (qui crée la facture)
    public function downloadFacture($bill, $project, $pdfService, $uniqueIdService){
        // gère l'image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/logo/logo.png';
        $imageData = base64_encode(file_get_contents($imagePath));

        
        $html = $this->renderView('pdf/facture.html.twig', [
            "project" => $project,
            "bill" => $bill,
            "imageData" => $imageData
        ]);
        
        $domPdf = $pdfService->showPdf($html);
        
        $uniqueId = $uniqueIdService->generateUniqueId();
        // génère un nom de fichier unique
        $filename = 'facture_' . $uniqueId . '.pdf';
        
        // chemin complet
        $filePath = $this->getParameter('kernel.project_dir') . '/public/upload/facture/' . $filename;
        
        // sauvegarde le pdf
        file_put_contents($filePath, $domPdf->output());
        
        // télécharge le fichier
        return new BinaryFileResponse($filePath);

    }

    

    //accepte le devis (utilisateur)
    #[Route('/accepteDevis/{id}', name: 'accepte_devis')]
    public function accepteDevis(Quotation $quotation = null, EntityManagerInterface $entityManager, StateRepository $stateRepository, UniqueIdService $uniqueIdService, PdfService $pdfService){
        if(!$quotation){
            $this->addFlash('error', 'Ce devis n\'existe pas');
            return $this->redirectToRoute('app_projet');

        }
        
        $project = $quotation->getProject();
        //si l'utilisateur connecté n'est pas le propriétaire du projet/devis
        if($this->getUser()!== $project->getUser()){
            $this->addFlash('error', 'Vous n\'avez pas le droit de valider ce devis');
            return $this->redirectToRoute('app_home');
        }
        //si le projet n'est pas modifiable
        if($project->getState()->getId()!==2){
            $this->addFlash('error', 'L\'état du projet ne vous permet pas de faire cette action');
            return $this->redirectToRoute('app_profil');
        }
        //----change le statut du projet de "en attente" à "accepté"
        $stateAccepte = $stateRepository->findOneBy(['id' => 3]);
        $project->setState($stateAccepte);
        $entityManager->persist($project);

        //passe le statut du devis à accepté
        $quotation->setAccepted(true);

        //crée une facture
        $bill = new Bill();
        $bill->setQuotation($quotation);
        $billNumber = "FACT_" . $uniqueIdService->generateUniqueId(); //genere un nom unique
        $bill->setBillNumber($billNumber);
        $entityManager->persist($bill);

        $entityManager->flush(); //execute

        //télécharge la facture dans le dossier
        $this->downloadFacture($bill, $project, $pdfService, $uniqueIdService);

        $this->addFlash('success', 'Devis accepté!');
        return $this->redirectToRoute('app_profil');

    }

    //affiche la facture pdf: pour l'utilisateur ou l'admin
    #[Route('/facture/{id}', name: 'show_facture')]
    public function showFacture(Project $project = null, PdfService $pdfService, BillRepository $billRepository, QuotationRepository $quotationRepository){

        if(!$project){
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }

        //si l'utilisateur connecté n'est pas le propriétaire du projet/devis ou admin
        if($this->getUser()!== $project->getUser() && !$this->isGranted('ROLE_ADMIN')){
            $this->addFlash('error', 'Vous n\'avez pas le droit de voir cette facture');
            return $this->redirectToRoute('app_home');
        }

        $quotation = $quotationRepository->findOneBy(['project' => $project->getId()]); //devis associé au projet
        $bill = $billRepository->findOneBy(['quotation' => $quotation->getId()]); //facture associée au devis

        // gère l'image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/logo/logo.png';
        $imageData = base64_encode(file_get_contents($imagePath));

        
        $html = $this->renderView('pdf/facture.html.twig', [
            'bill' => $bill,
            "project" => $project,
            "imageData" => $imageData
        ]);
        
        $domPdf = $pdfService->showPdf($html);

        $domPdf->stream("facture.pdf", array('Attachment' => 0));
            return new Response('', 200, [
                    'Content-Type' => 'application/pdf',
            ]);

    }
    
    //affiche la facture en html: pour l'utilisateur ou l'admin
    #[Route('/factureHtml/{id}', name: 'show_facture_html')]
    public function showFactureHtml(Project $project = null, BillRepository $billRepository, QuotationRepository $quotationRepository){

        if(!$project){
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }
        
        //si l'utilisateur connecté n'est pas le propriétaire du projet/devis ou admin
        if($this->getUser()!== $project->getUser() && !$this->isGranted('ROLE_ADMIN')){
            $this->addFlash('error', 'Vous n\'avez pas le droit de voir cette facture');
            return $this->redirectToRoute('app_home');
        }
        $quotation = $quotationRepository->findOneBy(['project' => $project->getId()]); //devis associé au projet
        $bill = $billRepository->findOneBy(['quotation' => $quotation->getId()]); //facture associée au devis

        // gère l'image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/logo/logo.png';
        $imageData = base64_encode(file_get_contents($imagePath));

        
        return $this->render('pdf/facture.html.twig', [
            'bill' => $bill,
            "project" => $project,
            "imageData" => $imageData
        ]);
        
        

    }

}
