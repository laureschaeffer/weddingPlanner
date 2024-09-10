<?php


namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ApiController extends AbstractController
{
    // if you're using service autowiring, the variable name must be:
    // "rate limiter name" (in camelCase) + "Limiter" suffix
    public function index(Request $request, RateLimiterFactory $anonymousApiLimiter)
    {
        // create a limiter based on a unique identifier of the client
        // (e.g. the client's IP address, a username/email, an API key, etc.)
        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        // the argument of consume() is the number of tokens to consume
        // and returns an object of type Limit
        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        // you can also use the ensureAccepted() method - which throws a
        // RateLimitExceededException if the limit has been reached
        // $limiter->consume(1)->ensureAccepted();

        // to reset the counter
        // $limiter->reset();

        // ...
    }

    //met à jour les rendez-vous dynamiquement avec fullcalendar
    #[Route('/api/edit/{id}', name: 'edit_event')] //modifie
    #[Route('/api/post', name: 'post_event')] //ajoute
    public function majEvent(?Appointment $appointment, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository){
        
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
                    $userSelected = $userRepository->findOneBy(['email' => $donnees->user]);
                    $appointment->setUser($userSelected);
                }

                $appointment->setTitle($donnees->title);
                $appointment->setDateStart(new \DateTime($donnees->start));
                $appointment->setDateEnd(new \DateTime($donnees->end));

                $entityManager->persist($appointment);
                $entityManager->flush();

                $this->addFlash('success', 'Rendez-vous mis à jour');
                return $this->redirectToRoute('app_rendezvous');
            } else {
                return new Response('Données incomplètes', 404);
            }

    }

    //supprime un rdv
    #[Route('/api/delete/{id}', name: 'delete_event')]
    public function deleteEvent(Appointment $appointment, EntityManagerInterface $entityManager){
        
        if($appointment){
            
            $entityManager->remove($appointment);
            $entityManager->flush();

            $this->addFlash('success', 'Rendez-vous supprimé');
            return $this->redirectToRoute('app_rendezvous');
        }

    }
}