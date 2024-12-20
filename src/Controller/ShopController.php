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

#[Route('/shop')]
class ShopController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private BasketService $basketService) {
    }
    //liste des collections avec quelques produits, "batch"= collection 
    #[Route('/', name: 'app_shop')]
    public function index(BatchRepository $batchRepository): Response
    {
        $collections = $batchRepository->findBy([]);        
        return $this->render('shop/index.html.twig', [
            'collections' => $collections,
            'panier' => $this->basketService->getBasket()
        ]);
    }

    //detail d'une collection avec ses produits
    #[Route('/collection/{id}', name: 'show_batch')]
    public function showCollection(Batch $batch=null, Request $request, ProductRepository $productRepository): Response 
    {
        //si la collection existe
        if($batch){

            //paginator des produits associés à une collection
            $page = $request->query->getInt('page', 1);
            $products = $productRepository->paginateProduct($batch->getId(), $page);
            
            return $this->render('shop/batch.html.twig', [
                'batch' => $batch,
                'products' => $products,
                'panier' => $this->basketService->getBasket()
            ]);

        } else {
            $this->addFlash('error', 'Cette collection n\'existe pas');
            return $this->redirectToRoute('app_shop');
        }
    }

    //detail d'un produit
    #[Route('/produit/{id}', name: 'show_product')]
    public function showProduct(Product $product = null, ProductRepository $productRepository): Response 
    {

        //si le produit existe
        if($product){
            //propose 6 produits de la meme collection
            $propositions = $productRepository->findBy(['batch' => $product->getBatch()->getId()], [], 6 );
            return $this->render('shop/product.html.twig', [
                'product' => $product,
                'propositions' => $propositions,
                'panier' => $this->basketService->getBasket()
            ]);
        }
    }

    //=========================================================================INTERRACTION AVEC LE PANIER=======================================================

    //ajoute un produit au panier, à la session
    // {id<\d+} est une expression régulière qui force à ce que le paramètre soit un id
    // au lieu d'envoyer une erreur, il explique que cet url n'existe simplement pas
    #[Route('/ajoutePanier/{id<\d+>}', name: 'add_basket')]
    public function addProduct(int $id, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(['id' => $id]);
        
        //si le produit existe
        if($product){
            $this->basketService->addToBasket($id);
          
            $this->addFlash('success', 'Produit ajouté');
            return $this->redirectToRoute('show_product', ['id' => $id]);
        } else {
            $this->addFlash('error', 'Ce produit n\'existe pas');
            return $this->redirectToRoute('app_shop');
        }
    }

    //-----------------------------------------------------------------------------------------quantités---------------------------------------------------------------


    //augmente la quantité d'un produit
    #[Route('/increaseProduct/{id<\d+>}', name: 'increase_product')]
    public function increaseProduct(int $id){
        $text = $this->basketService->increaseProduct($id);

        $this->addFlash('success', $text);
        return $this->redirectToRoute('app_basket');
    }

    //diminue la quantité d'un produit
    #[Route('/decreaseProduct/{id<\d+>}', name: 'decrease_product')]
    public function decreaseProduct(int $id){
        $text = $this->basketService->decreaseProduct($id);

        $this->addFlash('success', $text);
        return $this->redirectToRoute('app_basket');
    }

    //-----------------------------------------------------------------------------------------suppression---------------------------------------------------------------

    //retire un produit du panier
    #[Route('/retirePanier/{id<\d+>}', name: 'remove_product')]
    public function removeProduct(int $id){
        $text = $this->basketService->removeProduct($id);

        $this->addFlash('success', $text);
        return $this->redirectToRoute('app_basket');
    }


    //supprime le panier
    #[Route('/supprimePanier', name: 'delete_basket')]
    public function deleteBasket(){
        $this->basketService->deleteBasket();

        $this->addFlash('succes', 'Panier supprimé');
        return $this->redirectToRoute('app_shop');
    }

    //-----------------------------------------------------------------------------------------affiche---------------------------------------------------------------

    //montre le panier, appelle la methode dans BasketService
    #[Route('/panier', name: 'app_basket')]
    public function showBasket(): Response 
    {
        $panier = $this->basketService->getBasket();

        return $this->render('shop/panier.html.twig', [
            'panier' => $panier
        ]);
    }

    
    //=========================================================================================RESERVATION=================================================================
    
    //traite la création de réservation avec le formulaire, factorisation de la fonction makeReservation
    public function createReservation($uniqueIdService, $user, $reservation){
        
        $roles = $user->getRoles();
        array_push($roles, "ROLE_ACHETEUR"); //passe l'utilisateur en acheteur

        //remplit à la main car pas demandé dans le formulaire
        $reservation->setUser($user); //utilisateur connecté
        $referenceOrder = $uniqueIdService->generateUniqueId(); //id unique
        $reservation->setReferenceOrder($referenceOrder);

        $panier = $this->basketService->getBasket(); //recupere le panier en session
        $total = end($panier)["total"]; //recupere le total au dernier index du tableau

        $reservation->setTotalPrice($total);

        // return $reservation;
        
    }

    //traite la création booking avec le formulaire, factorisation de la fonction makeReservation
    public function createBooking($panier, $reservation){

        //il faut créer autant d'entité booking que de produit dans le panier
        foreach($panier as $p){
            $booking = new Booking();
            $booking->setProduct($p['produit']);
            $booking->setReservation($reservation);
            $booking->setQuantite($p['qtt']);

            //hydrate l'objet reservation pour pouvoir accèder plus tard aux réservations
            $reservation->addBooking($booking);

            $this->entityManager->persist($booking); //prepare
            $this->entityManager->flush(); //execute
            
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
            // ->addPart((new DataPart(new File('public/img/logo/logo.png'), 'footer-signature', 'image/gif'))->asInline())
            ;

        $mailer->send($email);
    }

    //ajoute le panier en réservation
    #[Route('/reservation', name: 'make_reservation')]
    public function makeReservation(Request $request, UserInterface $user, UniqueIdService $uniqueIdService, MailerInterface $mailer): Response
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

                //traite la création de reservation
                $this->createReservation($uniqueIdService, $user, $reservation);
    
                $this->entityManager->persist($reservation); //prepare
                $this->entityManager->flush(); //execute

                //---------------------------------------------------entité booking------------------------------

                $panier = $this->basketService->getBasket();
                //traite la création de booking
                $this->createBooking($panier, $reservation);

                
                //-----------------------------------------envoie d'un email de confirmation
                $this->sendConfirmationMail($user, $reservation, $mailer);

                $this->basketService->deleteBasket(); //supprime le panier en session
    
                $this->addFlash('success', 'Réservation effectuée');
                return $this->redirectToRoute('app_home');

            }

        }

        return $this->render('shop/reservation.html.twig', [
            'form' => $form
        ]);

    }
}
