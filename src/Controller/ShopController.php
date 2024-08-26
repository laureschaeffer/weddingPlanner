<?php

//----------------------controller pour la partie commerce

namespace App\Controller;

use App\Entity\Batch;
use App\Entity\Booking;
use App\Entity\Product;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\BasketService;
use App\Repository\BatchRepository;
use Symfony\Component\Mime\Address;
use App\Repository\ProductRepository;
use App\Service\UniqueIdService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    //liste des collections avec quelques produits, "batch"= collection 
    #[Route('/shop', name: 'app_shop')]
    public function index(BatchRepository $batchRepository): Response
    {
        $collections = $batchRepository->findBy([]);
        $troisProduits = $batchRepository->find3Product();        
        return $this->render('shop/index.html.twig', [
            'collections' => $collections,
            'troisProduits' => $troisProduits
        ]);
    }

    //detail d'une collection avec ses produits
    #[Route('/shop/collection/{id}', name: 'show_batch')]
    public function showCollection(Batch $batch=null): Response 
    {
        //si la collection existe
        if($batch){
            return $this->render('shop/batch.html.twig', [
                'batch' => $batch
            ]);

        } else {
            //msg d'erreur
            return $this->redirectToRoute('app_shop');
        }
    }

    //detail d'un produit
    #[Route('/shop/produit/{id}', name: 'show_product')]
    public function showProduct(Product $product = null, ProductRepository $productRepository): Response 
    {

        //si le produit existe
        if($product){
            //propose 6 produits de la meme collection
            $propositions = $productRepository->findBy(['batch' => $product->getBatch()->getId()], [], 6 );
            return $this->render('shop/product.html.twig', [
                'product' => $product,
                'propositions' => $propositions
            ]);
        }
    }

    //=========================================================================INTERRACTION AVEC LE PANIER=======================================================

    //ajoute un produit au panier, à la session
    // {id<\d+} est une expression régulière qui force à ce que le paramètre soit un id
    // au lieu d'envoyer une erreur, il explique que cet url n'existe simplement pas
    #[Route('/shop/ajoutePanier/{id<\d+>}', name: 'add_basket')]
    public function addProduct(BasketService $basketService, int $id, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(['id' => $id]);
        
        //si le produit existe
        if($product){
            $basketService->addToBasket($id);
          
            $this->addFlash('success', 'Produit ajouté');
            return $this->redirectToRoute('show_product', ['id' => $id]);
        } else {
            $this->addFlash('error', 'Ce produit n\'existe pas');
            return $this->redirectToRoute('app_shop');
        }
    }

    //-----------------------------------------------------------------------------------------quantités---------------------------------------------------------------


    //augmente la quantité d'un produit
    #[Route('/shop/increaseProduct/{id<\d+>}', name: 'increase_product')]
    public function increaseProduct(BasketService $basketService, int $id){
        $text = $basketService->increaseProduct($id);

        $this->addFlash('success', $text);
        return $this->redirectToRoute('app_basket');
    }

    //diminue la quantité d'un produit
    #[Route('/shop/decreaseProduct/{id<\d+>}', name: 'decrease_product')]
    public function decreaseProduct(BasketService $basketService, int $id){
        $text = $basketService->decreaseProduct($id);

        $this->addFlash('success', $text);
        return $this->redirectToRoute('app_basket');
    }

    //-----------------------------------------------------------------------------------------suppression---------------------------------------------------------------

    //retire un produit du panier
    #[Route('/shop/retirePanier/{id<\d+>}', name: 'remove_product')]
    public function removeProduct(BasketService $basketService, int $id){
        $text = $basketService->removeProduct($id);

        $this->addFlash('success', $text);
        return $this->redirectToRoute('app_basket');
    }


    //supprime le panier
    #[Route('/shop/supprimePanier', name: 'delete_basket')]
    public function deleteBasket(BasketService $basketService){
        $basketService->deleteBasket();

        $this->addFlash('succes', 'Panier supprimé');
        return $this->redirectToRoute('app_shop');
    }

    //-----------------------------------------------------------------------------------------affiche---------------------------------------------------------------

    //montre le panier, appelle la methode dans BasketService
    #[Route('/shop/panier', name: 'app_basket')]
    public function showBasket(BasketService $basketService): Response 
    {
        $panier = $basketService->getBasket();

        return $this->render('shop/panier.html.twig', [
            'panier' => $panier
        ]);
    }

    
    //=========================================================================================RESERVATION=================================================================
    
    //traite la création de réservation avec le formulaire, factorisation de la fonction makeReservation
    public function createReservation($basketService, $uniqueIdService, $user, $reservation){
        
        $roles = $user->getRoles();
        array_push($roles, "ROLE_ACHETEUR"); //passe l'utilisateur en acheteur

        //remplit à la main car pas demandé dans le formulaire
        $reservation->setUser($user); //utilisateur connecté
        $referenceOrder = $uniqueIdService->generateUniqueId(); //id unique
        $reservation->setReferenceOrder($referenceOrder);

        $panier = $basketService->getBasket(); //recupere le panier en session
        $total = end($panier)["total"]; //recupere le total au dernier index du tableau

        $reservation->setTotalPrice($total);

        // return $reservation;
        
    }

    //traite la création booking avec le formulaire, factorisation de la fonction makeReservation
    public function createBooking($panier, $reservation, $entityManager){

        //il faut créer autant d'entité booking que de produit dans le panier
        foreach($panier as $p){
            $booking = new Booking();
            $booking->setProduct($p['produit']);
            $booking->setReservation($reservation);
            $booking->setQuantite($p['qtt']);

            //hydrate l'objet reservation pour pouvoir accèder plus tard aux réservations
            $reservation->addBooking($booking);

            $entityManager->persist($booking); //prepare
            $entityManager->flush(); //execute
            
        }
    }

    //envoie un mail, factorisation de la fonction makeReservation
    public function sendConfirmationMail($user, $reservation, $mailer){
        $email = (new TemplatedEmail())
            ->from(new Address('admin-ceremonie-couture@exemple.fr', 'Ceremonie Couture Bot'))
            ->to($user->getEmail())
            ->subject('Confirmation de commande')
            // ->text('Sending emails is fun again!')
            // pass variables (name => value) to the template
            ->context([
                'reservation' => $reservation
            ])
            ->htmlTemplate('email/confirmationCommande.html.twig')
            // ->addPart((new DataPart(new File('public/img/logo/logo-noncropped.png'), 'footer-signature', 'image/gif'))->asInline())
            ;

        $mailer->send($email);
    }

    //ajoute le panier en réservation
    #[Route('/shop/reservation', name: 'make_reservation')]
    public function makeReservation(EntityManagerInterface $entityManager, Request $request, UserInterface $user, BasketService $basketService, UniqueIdService $uniqueIdService, MailerInterface $mailer): Response
    {

        //la personne doit être connectée pour que la réservation soit associée à une entité
        if($user){

            //---------------------------------------------------entité reservation------------------------------
            //crée la reservation
            $reservation = new Reservation();           
    
            //crée le form
            $form = $this->createForm(ReservationType::class, $reservation);
    
            // prend en charge
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){

                //récupère les données du formulaire 
                $reservation = $form->getData();

                //si la date est valide
                if($reservation->getValidDate()){

                    //traite la création de reservation
                    $this->createReservation($basketService, $uniqueIdService, $user, $reservation);
        
                    $entityManager->persist($reservation); //prepare
                    $entityManager->flush(); //execute
    
                    //---------------------------------------------------entité booking------------------------------
    
                    $panier = $basketService->getBasket();
                    //traite la création de booking
                    $this->createBooking($panier, $reservation, $entityManager);
    
                    
                    //-----------------------------------------envoie d'un email de confirmation
                    $this->sendConfirmationMail($user, $reservation, $mailer);

                    $basketService->deleteBasket(); //supprime le panier en session
        
                    $this->addFlash('success', 'Réservation effectuée');
                    return $this->redirectToRoute('app_home');
                    // return $this->redirectToRoute('app_profil');
                } else {
                    $this->addFlash('error', 'Date invalide');
                    return $this->redirectToRoute('make_reservation');
                }
            }

        }

        return $this->render('shop/reservation.html.twig', [
            'form' => $form
        ]);

    }
}
