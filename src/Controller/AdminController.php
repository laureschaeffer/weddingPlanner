<?php
//------------------------------------------------------------------------pannel admin---------------------------------------------------------------------
namespace App\Controller;

use App\Entity\Testimony;
use App\Entity\Appointment;
use App\Entity\Reservation;
use App\Form\ReservationEditType;
use App\Repository\AppointmentRepository;
use Symfony\Component\Mime\Address;
use App\Repository\ProjectRepository;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/coiffe')]
class AdminController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {
    }

    #[Route('/', name: 'app_admin')]
    public function index(ProjectRepository $projectRepository): Response
    {
        //tableau des differents etats de projets pour le diagramme
        $nbProjectEnCours = count($projectRepository->findBy(['state' => 1]));
        $nbProjectEnAttente = count($projectRepository->findBy(['state' => 2]));
        $nbProjectAccepte = count($projectRepository->findBy(['state' => 3]));
        $nbProjectRefuse = count($projectRepository->findBy(['state' => 4]));

        $etatProjets = [$nbProjectEnCours, $nbProjectEnAttente, $nbProjectAccepte, $nbProjectRefuse];

        //tableau du nb de projets par mois
        $projetMois = $projectRepository->findProjectByMonth();

        //tableau de la moyenne du chiffre d'affaire par mois
        $caMois = $projectRepository->findMonthlyRevenue();


        return $this->render('admin/index.html.twig', [
            'etatProjets' => json_encode($etatProjets), 
            'projetMois' => json_encode($projetMois),
            'caMois' => json_encode($caMois)
        ]);
    }


    //----------------------------------------------partie témoignages--------------------------------

    //affiche la page pour verifier les avis
    #[Route('/avis', name: 'app_avis')]
    public function listTestimony(TestimonyRepository $testimonyRepository): Response
    {
        //liste des avis publiés ou non publiés
        $avisPublies = $testimonyRepository->findBy(['isPublished' => 1], ['dateReceipt' => 'DESC']);
        $avisNonPublies = $testimonyRepository->findBy(['isPublished' => 0], ['dateReceipt' => 'DESC']);

        return $this->render('admin/listeTemoignage.html.twig', [
            'avisPublies' => $avisPublies,
            'avisNonPublies' => $avisNonPublies
        ]);
    }

    //change l'avis en publié ou non publié
    #[Route('/changeAvis/{id}', name: 'change_avis')]
    public function changeTestimonyState(Testimony $testimony = null, TestimonyRepository $testimonyRepository){

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

        $this->entityManager->persist($testimony);
        $this->entityManager->flush();

        $this->addFlash('success', 'Statut de l\'avis du couple ' . $testimony->getCoupleName() . ' modifié');
        return $this->redirectToRoute('app_avis');

    }

    //supprimer un avis de la bdd (non publiés)
    #[Route('/supprAvis/{id}', name: 'remove_avis')] 
    public function deleteTestimony(Testimony $testimony = null){
        
        //si le temoignage existe et qu'il n'est pas publié
        if($testimony && $testimony->isPublished() == false){
            $this->entityManager->remove($testimony); //prepare
            $this->entityManager->flush(); //execute
            
            // notif et redirection
            $this->addFlash('success', 'Avis supprimé');
            return $this->redirectToRoute('app_avis');

        } else {
            $this->addFlash('error', 'Ce témoignage n\'existe pas, ou doit d\'abord être dépublié');
            return $this->redirectToRoute('app_avis');
        }
    }



    //----------------------------------------------partie réservation de commandes--------------------------------

    //liste des reservations de commandes
    #[Route('/commande', name: 'app_commande')]
    public function listCommande(ReservationRepository $reservationRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $reservationsAPreparer = $reservationRepository->paginateReservations($page);
        
        // $reservationsAPreparer = $reservationRepository->findBy(['isPrepared' => 0], ['datePicking' => 'ASC']);
        // $reservationsPassees = $reservationRepository->findBy(['isPrepared' => 1], ['dateOrder' => 'ASC']);

        return $this->render('admin/listeReservation.html.twig', [
            'reservationsAPreparer' => $reservationsAPreparer,
            // 'reservationsPassees' => $reservationsPassees
        ]);
        
    }

    //modifie la commande
    #[Route('/editCommande/{id<\d+>}', name: 'edit_commande')]
    public function editCommande(Request $request, Reservation $reservation): Response
    {
        if($reservation){

            //crée le formulaire
            $form = $this->createForm(ReservationEditType::class, $reservation);
            $form->handleRequest($request); 
    
            if($form->isSubmitted() && $form->isValid()){
                $reservation=$form->getData();
    
                $this->entityManager->persist($reservation); //prepare
                $this->entityManager->flush(); //execute
    
                $this->addFlash('success', 'Commande modifiée');
                return $this->redirectToRoute('show_commande', ['id' => $reservation->getId()]);
            }
    
            //vue du formulaire
            return $this->render('admin/editReservation.html.twig', [
                'form'=>$form
            ]);

        } else {
            $this->addFlash('error', 'Cette commande n\'existe pas');
            return $this->redirectToRoute('app_commande');
        }
    }

    //detail d'une reservation
    #[Route('/commande/{id<\d+>}', name: 'show_commande')]
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
    #[Route('/changeCommandePrepared/{id<\d+>}', name: 'change_commande_prepared')]
    public function changeCommandePrepared(Reservation $reservation =null, MailerInterface $mailer){

        //si la commande existe
        if($reservation){

            //si la reservation est préparée, la passer en non préparée, ; sinon inverse
            if($reservation->isPrepared()){
                $reservation->setPrepared(false);
            } else {
                $reservation->setPrepared(true);

                //envoie d'un mail
                $email = (new TemplatedEmail())
                ->from(new Address('admin-ceremonie-couture@exemple.fr', 'Ceremonie Couture Bot'))
                ->to($reservation->getUser()->getEmail())
                ->subject('Votre commande est prête')
                // pass variables (name => value) to the template
                ->context([
                    'reservation' => $reservation
                ])
                ->htmlTemplate('email/commandePrete.html.twig')
                ;

                $mailer->send($email);
            }

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            $this->addFlash('success', 'Statut de la commande changé');
            return $this->redirectToRoute('show_commande', ['id' => $reservation->getId()]);

        } else {
            $this->addFlash('error', 'Cette commande n\'existe pas');
            return $this->redirectToRoute('app_commande');
        }

    }


    //passer une commande en récupérée ou non récupérée
    #[Route('/changeCommandePicked/{id<\d+>}', name: 'change_commande_picked')]
    public function changeCommandePicked(Reservation $reservation =null){

        //si la commande existe
        if($reservation){

            //si la reservation est préparée, la passer en non préparée, ; sinon inverse
            if($reservation->isPicked()){
                $reservation->setPicked(false);
            } else {
                $reservation->setPicked(true);
            }

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            $this->addFlash('success', 'Statut de la commande changé');
            return $this->redirectToRoute('show_commande', ['id' => $reservation->getId()]);

        } else {
            $this->addFlash('error', 'Cette commande n\'existe pas');
            return $this->redirectToRoute('app_commande');
        }

    }

    //-------------------------------------------------------------------------partie RENDEZ-VOUS ------------------------------------------------------------------------

    // affiche les rendez-vous
    #[Route('/rendez-vous', name: 'app_rendezvous')]
    public function showAppointment(AppointmentRepository $appointmentRepository, UserRepository $userRepository){
        $appointments = $appointmentRepository->findAll();
        $activeUsers = $userRepository->findAllExceptRoleSupprime();

        foreach($appointments as $appointment){
            $rdvs[] = [
                'id' => $appointment->getId(),
                'start' => $appointment->getDateStart()->format('Y-m-d H:i:s'),
                'end' => $appointment->getDateEnd()->format('Y-m-d H:i:s'),
                'title' => $appointment->getTitle(),
                'user' => $appointment->getUser()->getEmail(),
                'backgroundColor' => '#2c3e50',
                'borderColor' => '#ffffff',
                'textColor' => '#ffffff',
                'allDay' => false
            ];
        }

        $data = json_encode($rdvs);
        return $this->render('admin/appointment.html.twig', [
            'data' => $data,
            'activeUsers' => $activeUsers
        ]);
    }

    //met à jour les rendez-vous dynamiquement avec fullcalendar
    #[Route('/rendez-vousEdit/{id}', name: 'edit_event')] //modifie
    #[Route('/rendez-vousPost', name: 'post_event')] //ajoute
    public function majEvent(?Appointment $appointment, Request $request, UserRepository $userRepository, MailerInterface $mailer){
        
        $donnees = json_decode($request->getContent()); //tableau renvoye dans la requete xml
        // si le tableau a bien tous les éléments dont l'objet Appointment a besoin

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end)
            ){
                //si le rdv n'existait pas on le crée
                if(!$appointment){
                    $appointment = new Appointment;
                    $emailContact = $donnees->user;
                    $userSelected = $userRepository->findOneBy(['email' => $donnees->user]);
                    $appointment->setUser($userSelected);

                    $text = 'Rendez-vous créé';
                }

                $appointment->setTitle($donnees->title);
                $appointment->setDateStart(new \DateTime($donnees->start));
                $appointment->setDateEnd(new \DateTime($donnees->end));
                $text = 'Rendez-vous mis à jour';

                $emailContact = $appointment->getUser()->getEmail();

                $this->entityManager->persist($appointment);
                $this->entityManager->flush();

                $email = (new TemplatedEmail())
                    ->from(new Address('admin-ceremonie-couture@exemple.fr', 'Ceremonie Couture Bot'))
                    ->to($emailContact)
                    ->subject('Prise de rendez-vous')

                    ->context([
                        'title' => $donnees->title,
                        'start' => $donnees->start,
                        'end' => $donnees->end,
                    ])
                    ->htmlTemplate('email/confirmationRdv.html.twig')
                    ;

                $mailer->send($email);

                $this->addFlash('success', $text);
                return $this->redirectToRoute('app_rendezvous');
            } else {
                return new Response('Données incomplètes', 404);
            }

    }

    //supprime un rdv
    #[Route('/rendez-vousDelete/{id}', name: 'delete_event')]
    public function deleteEvent(Appointment $appointment){
        
        if($appointment){
            
            $this->entityManager->remove($appointment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Rendez-vous supprimé');
            return $this->redirectToRoute('app_rendezvous');
        }

    }

    
}
