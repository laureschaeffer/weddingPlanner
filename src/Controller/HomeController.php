<?php
// --------------------controller qui gère les principales vues de l'application : accueil, equipe, portefolio, prestations

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Creation;
use App\Entity\Testimony;
use App\Form\ProjectType;
use App\Entity\Appointment;
use App\Form\TestimonyType;
use App\Form\AppointmentType;
use App\Repository\StateRepository;
use Symfony\Component\Mime\Address;
use App\Repository\WorkerRepository;
use App\Repository\CategoryRepository;
use App\Repository\TestimonyRepository;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class HomeController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager) {
    }
    //--------------------------------------------------------page d'accueil------------------------------------------------------------

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
    public function newProject(Request $request, StateRepository $stateRepository)
    {
        $user = $this->getUser();
                
        $project = new Project();
        

        //crée le formulaire
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request); 
    
        if($form->isSubmitted() && $form->isValid()){

            //si l'utilisateur est connecté
            if($user){
                $project=$form->getData();
                
                //initialise
                $project->setUser($user);
                $stateEnCours = $stateRepository->findOneBy(['id' => 1]);
                $project->setState($stateEnCours);
    
                //si la date de l'evenement n'est pas dépassée
                if($project->isDateValid()){
                    $this->entityManager->persist($project); //prepare
                    $this->entityManager->flush(); //execute
        
                    $this->addFlash('success', 'Demande envoyée');
                    return $this->redirectToRoute('app_profil');
                    
                } else {
                    $this->addFlash('error', 'La date de l\'évenement est dépassée!');
                    return $this->redirectToRoute('app_contact');
                }
    
            } else {
                $this->addFlash('error', 'Veuillez vous connecter');
                return $this->redirectToRoute('app_contact');
            }

        }

        //vue du formulaire
        return $this->render('home/contact.html.twig', [
            'form'=>$form
        ]);
    }

    //gère le formulaire d'un utilisateur qui donne son avis
    #[Route('/temoignage', name: 'app_temoignage')]
    public function newTestimony(Request $request){

        $testimony = new Testimony();

        //crée le formulaire
        $form = $this->createForm(TestimonyType::class, $testimony);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $testimony = $form->getData();

            $this->entityManager->persist($testimony); //prepare
            $this->entityManager->flush(); //execute

            $this->addFlash('success', 'Témoignage envoyé');
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
            $this->addFlash('error', 'Cet album n\existe pas');
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
    
    //-------------------------------------------------------------------------partie RENDEZ-VOUS------------------------------------------------------------------------
    
    //nouveau le rendez-vous
    #[Route('/rendez-vous/new', name: 'create_appointment')]
    public function newAppointment(Request $request, AppointmentRepository $appointmentRepository, MailerInterface $mailer): Response
    {
        //----conditions----
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Veuillez vous connecter pour prendre un nouveau rendez-vous');
            return $this->redirectToRoute('app_login');
        }
        //filtre
        $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS); //honey pot field
    
        //si ce champ est rempli c'est un robot
        if($honeypot){
            return $this->redirectToRoute('app_home');
        } 

        //--------
        $appointment = new Appointment();
        
        //crée le formulaire
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request); 
    
        if($form->isSubmitted() && $form->isValid()){

            $appointment = $form->getData();
            
            //crée une copie indépendante de dateStart pour ne pas la modifier directement, partant du principe qu'un rdv dure une heure
            $dateEnd = clone $appointment->getDateStart();
            $dateEnd = \DateTime::createFromInterface($dateEnd); //modifie en DateTime pour avoir accès à la methode modify
            $dateEnd->modify('+1 hour');

            //si la date est déjà prise
            if($appointmentRepository->isDateTaken($appointment->getDateStart(), $dateEnd)){
                $this->addFlash('error', 'Le créneau est déjà pris');
                return $this->redirectToRoute('create_appointment');
            }

            $appointment->setUser($user); //user en session
            $appointment->setDateEnd($dateEnd);

            //envoie email confirmation
            $email = (new TemplatedEmail())
                    ->from(new Address('admin-ceremonie-couture@exemple.fr', 'Ceremonie Couture Bot'))
                    ->to($appointment->getUser()->getEmail())
                    ->subject('Prise de rendez-vous')

                    ->context([
                        'title' => $appointment->getTitle(),
                        'start' => $appointment->getDateStart(),
                        'end' => $appointment->getDateEnd(),
                    ])
                    ->htmlTemplate('email/confirmationRdv.html.twig')
                    ;

                $mailer->send($email);


            $this->entityManager->persist($appointment); //prepare
            $this->entityManager->flush(); //execute

            $this->addFlash('success', 'Le rendez-vous a été confirmé');
            return $this->redirectToRoute('app_profil');
        }
        

        return $this->render('home/newAppointment.html.twig', [
            'form' => $form
        ]);
       
        
    }
}
