<?php
//----------------------------------------- gere la partie suivi de projet----------------------------------------------------

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Comment;
use App\Entity\Note;
use App\Entity\Project;
use App\Entity\Quotation;
use App\Form\CommentType;
use App\Form\NoteType;
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


    public function __construct(private UniqueIdService $uniqueIdService, private PdfService $pdfService, private EntityManagerInterface $entityManager) 
    {    }

    //liste des demandes reçues    
    #[Route('/coiffe/projet/{contactState}', name: 'app_projet', defaults: ['contactState' => 'toBeContacted'])] //permet d'afficher en premier les personnes à contacter
    public function index(Request $request, ProjectRepository $projectRepository, string $contactState = 'toBeContacted') {
        $page = $request->query->getInt('page', 1);

        // si la page est 'toBeContacted', alors passe isContacted à false, sinon à true
        $isContacted = ($contactState == 'toBeContacted') ? false : true;
        $projets = $projectRepository->paginateProject($isContacted, $page);

        return $this->render('admin/listeProject.html.twig', [
            'projets' => $projets,
            'contactState' => $contactState
        ]);
    }

    //factorise showProject, gère le formulaire nouveau commentaire
    public function newComment($project, $form){
        $comment=$form->getData();

        $comment->setProject($project); //remplit par le projet où se trouve l'utilisateur
        $comment->setUser($this->getUser()); //remplit par l'utilisateur connecté

        $this->entityManager->persist($comment); //prepare
        $this->entityManager->flush(); //execute

    }
    
    //factorise showProject, gère le formulaire nouvelle note
    public function newNote($project, $formNote){
        $note=$formNote->getData();

        $note->setProject($project); //remplit par le projet où se trouve l'utilisateur
        $note->setUser($this->getUser()); //remplit par l'utilisateur connecté

        $this->entityManager->persist($note); //prepare
        $this->entityManager->flush(); //execute

    }

    //detail d'une demande
    #[Route('/coiffe/{id}/projet', name: 'show_projet')]
    public function showProject(?Project $project, Request $request): Response
    {
        if(!$project){

            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }

        //formulaire ajout d'un commentaire au suivi du projet
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){
            $this->newComment($project, $form);

            $this->addFlash('success', 'Commentaire ajouté');
            return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
        }

        //formulaire ajout d'une note
        $note = new Note();
        $formNote = $this->createForm(NoteType::class, $note);
        $formNote->handleRequest($request);
        
        if($formNote->isSubmitted() && $formNote->isValid()){
            $this->newNote($project, $formNote);

            $this->addFlash('success', 'Note ajoutée');
            return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
        }
        
        return $this->render('admin/showProject.html.twig', [
            'project' => $project,
            'form' => $form,
            'formNote' => $formNote
        ]);

    }

    // ************************************************COMMENTAIRE************************************************
    
    //modifie un commentaire du suivi
    #[Route('/coiffe/editComment/{id}', name: 'edit_comment')]
    public function editComment(Comment $comment =null, CsrfTokenManagerInterface $csrfTokenManager){
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
                $this->entityManager->flush();
    
                $this->addFlash('success', 'Commentaire modifié');
                return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
                
            }

        }
    }

    //supprime un commentaire du suivi
    #[Route('/coiffe/deleteComment/{id}', name: 'delete_comment')]
    public function deleteComment(Comment $comment = null){
        //si le commentaire existe
        if($comment){
            $idProjet = $comment->getProject()->getId(); //recupere l'id du projet pour la redirection

            //si le projet n'est pas modifiable
            if(!$comment->getProject()->isEditable()){
                $this->addFlash('error', 'L\'état du projet ne permet plus de le modifier');
                return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
            }

            $this->entityManager->remove($comment); //prepare
            $this->entityManager->flush(); //execute
            
            // notif et redirection
            $this->addFlash('success', 'Commentaire supprimé');
            return $this->redirectToRoute('show_projet', ['id' => $idProjet]);

        } else {
            $this->addFlash('error', 'Ce commentaire n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }
    }
    
    // ************************************************NOTE************************************************

    //modifie une note
    #[Route('/coiffe/editNote/{id}', name: 'edit_note')]
    public function editNote(?Note $note, CsrfTokenManagerInterface $csrfTokenManager){
        if($note){

            $idProjet = $note->getProject()->getId(); //recupere l'id du projet pour la redirection
            $tokenInput = filter_input(INPUT_POST, '_csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        
            $csrfTokenId = 'authenticate';

            //si le token de la session et du formulaire n'est pas le meme, redirige
            if (!$csrfTokenManager->isTokenValid(new CsrfToken($csrfTokenId, $tokenInput))) {
                $this->addFlash('error', 'Une erreur est apparue, veuillez réessayer');
                return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
            }

            //recupere la note dans l'input
            $content = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_SPECIAL_CHARS);
            //honey pot field
            $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        
            //si je recois "firstname" c'est un robot, je redirige
            if($honeypot){
                return $this->redirectToRoute('app_home');
            } else {
                $note->setContent($content);
                $this->entityManager->flush();
    
                $this->addFlash('success', 'Note modifiée');
                return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
                
            }

        }
    }

    //supprime un commentaire du suivi
    #[Route('/coiffe/deleteNote/{id}', name: 'delete_note')]
    public function deleteNote(?Note $note){
        //si la note existe
        if(!$note){
            $this->addFlash('error', 'Ce commentaire n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }

        $idProjet = $note->getProject()->getId(); //recupere l'id du projet pour la redirection

        //si le projet n'est pas modifiable
        if(!$note->getProject()->isEditable()){
            $this->addFlash('error', 'L\'état du projet ne permet plus de le modifier');
            return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
        }

        $this->entityManager->remove($note); //prepare
        $this->entityManager->flush(); //execute
        
        // notif et redirection
        $this->addFlash('success', 'Note supprimée');
        return $this->redirectToRoute('show_projet', ['id' => $idProjet]);
        
        
    }

    //passe le projet en "a été contacté" ou "à contacter"
    #[Route('/coiffe/changeContactedProjet/{id}', name: 'change_contacted_projet')]
    public function changeContactedProjet(Project $project = null){

        if($project){

            //si isContacted est vraie, on le passe en faux
            if($project->isContacted()){
                $project->setContacted(false);
            } else {
                $project->setContacted(true);
            }
    
            $this->entityManager->persist($project);
            $this->entityManager->flush();
    
            $this->addFlash('success', 'Statut de la demande changé');
            return $this->redirectToRoute('show_projet', ['id'=>$project->getId()]);
        } else {
            $this->addFlash('error', 'Ce projet n\'existe pas');
            return $this->redirectToRoute('app_projet');
        }

    }

    //ajoute/modifie le prix final
    #[Route('/coiffe/fixePrix/{id}', name: 'fixe_prix')]
    public function setPrice(Project $project = null){

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

            $this->entityManager->persist($project); //prepare
            $this->entityManager->flush(); //execute

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
    public function createDevisPdf(Project $project = null){
        if($project){
            //gere l'image
            $imagePath = $this->getParameter('kernel.project_dir') . '../public/img/logo/logo.png';
            $imageData = base64_encode(file_get_contents($imagePath)); //encode
            
            $html = $this->renderView('pdf/devis.html.twig', [
                "project" => $project,
                "imageData" => $imageData
            ]);
            
            $domPdf = $this->pdfService->showPdf($html);
            
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
    public function showDevis(Quotation $quotation = null){
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
            
            $domPdf = $this->pdfService->showPdf($html);
            
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
    public function showDevisHtml(Quotation $quotation = null){
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

    // ***************************************fonction create devis***************************************

    //enregistre le devis en base de donnée, factorisation de la fonction createDevis
    public function createQuotationBdd(Project $project){

        $quotation = new Quotation();
        $quotation->setProject($project);
        $quotationNumber = "DEV_" . $this->uniqueIdService->generateUniqueId(); //crée un nom unique aléatoire
        $quotation->setQuotationNumber($quotationNumber);

        $this->entityManager->persist($quotation); //prepare
        return $quotation;
    }

    //télécharge dans le dossier upload/devis le devis, factorisation de la fonction createDevis
    public function downloadDevis($quotation, $project){
        // gère l'image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/logo/logo.png';
        $imageData = base64_encode(file_get_contents($imagePath));

        
        $html = $this->renderView('pdf/devisFinal.html.twig', [
            "project" => $project,
            'quotation' => $quotation,
            "imageData" => $imageData
        ]);
        
        $domPdf = $this->pdfService->showPdf($html);
        
        $uniqueId = $this->uniqueIdService->generateUniqueId();
        // génère un nom de fichier unique
        $filename = 'devis_' . $uniqueId . '.pdf';
        
        // chemin complet
        $filePath = $this->getParameter('kernel.project_dir') . '/public/upload/devis/' . $filename;
        
        // sauvegarde le pdf
        file_put_contents($filePath, $domPdf->output());
        
        // télécharge le fichier
        return new BinaryFileResponse($filePath);

    }

    public function sendMailToClient($emailContact, $project, $mailer){
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
    }


    //crée le devis: l'enregistre dans la bdd, change le statut du projet, envoie un mail au client et télécharge le pdf
    #[Route('/coiffe/createDevis/{id}', name: 'create_devis')]
    public function createDevis(Project $project = null, StateRepository $stateRepository, MailerInterface $mailer){
        //--conditions--
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
        //----enregistre le devis (quotation) dans la bdd
        $quotation = $this->createQuotationBdd($project, $this->entityManager);

        //----change le statut du projet de "en cours" à "en attente" (d'une reponse du client)
        $stateEnAttente = $stateRepository->findOneBy(['id' => 2]);
        $project->setState($stateEnAttente);
        $this->entityManager->persist($project);

        //----envoie d'un mail
        $emailContact = $project->getEmail()==! NULL ? $project->getEmail() : $project->getUser()->getEmail();
        $this->sendMailToClient($emailContact, $project, $mailer);

        //----télécharge en local le devis pdf
        $this->downloadDevis($quotation, $project);

        $this->entityManager->flush(); //execute

        $this->addFlash('success', 'Devis créé et enregistré');
        return $this->redirectToRoute('show_projet', ['id' => $project->getId()]);
        
    }

    //refuse le devis (utilisateur)
    #[Route('/refuseDevis/{id}', name: 'refuse_devis')]
    public function refuseDevis(Quotation $quotation = null, StateRepository $stateRepository, CsrfTokenManagerInterface $csrfTokenManager){
        //-----securité honeypot et faille csrf-----
        //honey pot field
        $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        $tokenInput = filter_input(INPUT_POST, '_csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        
        $csrfTokenId = 'authenticate';

        //si le token de la session et du formulaire n'est pas le meme, redirige
        if (!$csrfTokenManager->isTokenValid(new CsrfToken($csrfTokenId, $tokenInput))) {
            $this->addFlash('error', 'Une erreur est apparue, veuillez réessayer');
            return $this->redirectToRoute('app_home');
        }
        
        //si je recois "firstname" c'est un robot, je redirige
        if($honeypot){
            return $this->redirectToRoute('app_home');
        }

        // ----conditions----
        if(!$quotation){
            $this->addFlash('error', 'Ce devis n\'existe pas');
            return $this->redirectToRoute('app_home');
        }

        $project = $quotation->getProject();

        //si l'utilisateur connecté n'est pas le propriétaire du projet/devis
        if($this->getUser()!== $project->getUser()){
            $this->addFlash('error', 'Vous n\'avez pas le droit de refuser ce devis');
            return $this->redirectToRoute('app_home');
        }

        //si le projet n'est pas "en attente"
        if($project->getState()->getId()!==2){
            $this->addFlash('error', 'L\'état du projet ne vous permet pas de faire cette action');
            return $this->redirectToRoute('app_home');
        }

        // passe le devis et le projet en refusé
        $quotation->setAccepted(false);
        $this->entityManager->persist($quotation);

        $stateRefuse = $stateRepository->findOneBy(['id' => 4]);
        $project->setState($stateRefuse);
        $this->entityManager->flush();

        $this->addFlash('success', 'Devis refusé, le projet est cloturé');
        return $this->redirectToRoute('app_profil');
    }

    //-----------------------------------------------------FACTURE--------------------------------------
    //télécharge dans le dossier upload/facture le facture, factorisation de la fonction accepteDevis (qui crée la facture)
    public function downloadFacture($bill, $project){
        // gère l'image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/logo/logo.png';
        $imageData = base64_encode(file_get_contents($imagePath));

        
        $html = $this->renderView('pdf/facture.html.twig', [
            "project" => $project,
            "bill" => $bill,
            "imageData" => $imageData
        ]);
        
        $domPdf = $this->pdfService->showPdf($html);
        
        $uniqueId = $this->uniqueIdService->generateUniqueId();
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
    public function accepteDevis(Quotation $quotation = null, StateRepository $stateRepository, CsrfTokenManagerInterface $csrfTokenManager){
        //-----securité honeypot et faille csrf-----
        //honey pot field
        $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        $tokenInput = filter_input(INPUT_POST, '_csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        
        $csrfTokenId = 'authenticate';

        //si le token de la session et du formulaire n'est pas le meme, redirige
        if (!$csrfTokenManager->isTokenValid(new CsrfToken($csrfTokenId, $tokenInput))) {
            $this->addFlash('error', 'Une erreur est apparue, veuillez réessayer');
            return $this->redirectToRoute('app_home');
        }
        
        //si je recois "firstname" c'est un robot, je redirige
        if($honeypot){
            return $this->redirectToRoute('app_home');
        }

        //-----conditions-----
        if(!$quotation){
            $this->addFlash('error', 'Ce devis n\'existe pas');
            return $this->redirectToRoute('app_home');

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
        //-----fin conditions-----

        //----change le statut du projet de "en attente" à "accepté"
        $stateAccepte = $stateRepository->findOneBy(['id' => 3]);
        $project->setState($stateAccepte);
        $this->entityManager->persist($project);

        //passe le statut du devis à accepté
        $quotation->setAccepted(true);

        //crée une facture
        $bill = new Bill();
        $bill->setQuotation($quotation);
        $billNumber = "FACT_" . $this->uniqueIdService->generateUniqueId(); //genere un nom unique
        $bill->setBillNumber($billNumber);
        $this->entityManager->persist($bill);

        $this->entityManager->flush(); //execute

        //télécharge la facture dans le dossier
        $this->downloadFacture($bill, $project);

        $this->addFlash('success', 'Devis accepté!');
        return $this->redirectToRoute('app_profil');

    }

    //affiche la facture pdf: pour l'utilisateur ou l'admin
    #[Route('/facture/{id}', name: 'show_facture')]
    public function showFacture(Project $project = null, BillRepository $billRepository, QuotationRepository $quotationRepository){

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
        
        if(!$quotation){
            $this->addFlash('error', 'Aucun devis associé à ce projet');
            return $this->redirectToRoute('app_home');
        }

        $bill = $billRepository->findOneBy(['quotation' => $quotation->getId()]); //facture associée au devis

        // gère l'image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/logo/logo.png';
        $imageData = base64_encode(file_get_contents($imagePath));

        
        $html = $this->renderView('pdf/facture.html.twig', [
            'bill' => $bill,
            "project" => $project,
            "imageData" => $imageData
        ]);
        
        $domPdf = $this->pdfService->showPdf($html);

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
            return $this->redirectToRoute('app_home');
        }
        
        //si l'utilisateur connecté n'est pas le propriétaire du projet/devis ou admin
        if($this->getUser()!== $project->getUser() && !$this->isGranted('ROLE_ADMIN')){
            $this->addFlash('error', 'Vous n\'avez pas le droit de voir cette facture');
            return $this->redirectToRoute('app_home');
        }
        $quotation = $quotationRepository->findOneBy(['project' => $project->getId()]); //devis associé au projet

        if(!$quotation){
            $this->addFlash('error', 'Aucun devis associé à ce projet');
            return $this->redirectToRoute('app_home');
        }

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
