<?php
// --------------------controller qui gère les principales vues de l'application : accueil, equipe, portefolio, prestations

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Creation;
use App\Entity\Testimony;
use App\Form\ProjectType;
use App\Entity\Appointment;
use App\Form\TestimonyType;
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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    //mentions légales
    #[Route('/mentionsLegales', name: 'mentions_legales')]
    public function showMentionsLegales(){
        return $this->render('home/mentionsLegales.html.twig');
    }

    //politiques confidentialités
    #[Route('/politiqueConfidentialite', name: 'politique_confidentialite')]
    public function showPolitiqueConfidentialité(){
        return $this->render('home/politiqueConfidentialite.html.twig');
    }
    
    //CGV
    #[Route('/cgv', name: 'cgv')]
    public function showCgv(){
        return $this->render('home/cgv.html.twig');
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
    
    //affiche la page créer un nouveau rendez-vous
    #[Route('/rendez-vous', name: 'app_appointment')]
    public function showCalendarUser(AppointmentRepository $appointmentRepository): Response
    {
        //----conditions----
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('error', 'Veuillez vous connecter pour prendre un nouveau rendez-vous');
            return $this->redirectToRoute('app_login');
        }
 
        $eventsArray = $appointmentRepository->findAll();

        foreach($eventsArray as $event){
            $rdvs[] = [
                'start' => $event->getDateStart()->format('Y-m-d H:i:s'),
                'end' => $event->getDateEnd()->format('Y-m-d H:i:s'),
                'title' => "",
                'backgroundColor' => '#b3b9c1',
                'borderColor' => '#b3b9c1',
                'textColor' => '#000',
                'allDay' => false
            ];
        }

        $events = json_encode($rdvs);
        return $this->render('home/newAppointment.html.twig', [
            'events' => $events
        ]);  
        
    }
    
    //ajoute un nouveau rdv dans la bdd
    #[Route('/rendez-vous/new', name: 'new_appointment', methods: ['POST'])]
    public function createAppointment(Request $request, MailerInterface $mailer, ValidatorInterface $validator)
    {
        if (!$this->getUser()) {
            return new JsonResponse(['message' => 'Veuillez vous connecter'], 401);
        }

        $donnees = json_decode($request->getContent());
        if ($donnees === null) {
            return new JsonResponse(['message' => 'Invalid JSON'], 400);
        }

        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end)
        ) {
            $appointment = new Appointment();
            $appointment->setUser($this->getUser());
            $appointment->setTitle($donnees->title);
            $appointment->setDateStart(new \DateTime($donnees->start));
            $appointment->setDateEnd(new \DateTime($donnees->end));

            // Envoi de l'email
            $email = (new TemplatedEmail())
                ->from(new Address('admin-ceremonie-couture@exemple.fr', 'Ceremonie Couture Bot'))
                ->to($this->getUser()->getEmail())
                ->subject('Prise de rendez-vous')
                ->context([
                    'title' => $donnees->title,
                    'start' => $donnees->start,
                    'end' => $donnees->end,
                ])
                ->htmlTemplate('email/confirmationRdv.html.twig');

            try {
                $mailer->send($email);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Error sending email: ' . $e->getMessage()], 500);
            }

            try {
                $this->entityManager->persist($appointment);
                $this->entityManager->flush();
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Error saving appointment: ' . $e->getMessage()], 500);
            }

            return new JsonResponse([
                'message' => 'Enregistrement réussi',
                'status' => 'success',
                'data' => $donnees
            ], 200);
        } else {
            return new JsonResponse(['message' => 'Données incomplètes'], 400);
        }
    }

    
}
