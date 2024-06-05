<?php

//----------------------controller pour la partie commerce

namespace App\Controller;

use DateTime;
use App\Entity\Batch;
use App\Entity\Booking;
use App\Entity\Product;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\BasketService;
use App\Repository\BatchRepository;
use Symfony\Component\Mime\Address;
use App\Repository\ProductRepository;
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
    public function showProduct(Product $product = null): Response 
    {

        //si le produit existe
        if($product){
            return $this->render('shop/product.html.twig', [
                'product' => $product
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

    //ajoute le panier en réservation
    #[Route('/shop/reservation', name: 'make_reservation')]
    public function makeReservation(EntityManagerInterface $entityManager, Request $request, UserInterface $user, BasketService $basketService, MailerInterface $mailer): Response
    {

        //la personne doit être connectée pour que la réservation soit associée à une entité
        if($user){
            

            $roles = $user->getRoles();
            array_push($roles, "ROLE_ACHETEUR"); //passe l'utilisateur en acheteur

            
            //---------------------------------------------------entité reservation------------------------------
            //crée la reservation
            $reservation = new Reservation();
    
            //crée le form
            $form = $this->createForm(ReservationType::class, $reservation);
    
            //prend en charge
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                //récupère les données du formulaire 
                $reservation = $form->getData();

                //crée un nombre unique aléatoire
                $referenceOrder = uniqid();

                //remplit à la main car pas demandé dans le formulaire
                $reservation->setUser($user); //remplit par l'utilisateur connecté
                $reservation->setReferenceOrder($referenceOrder);
                $reservation->setPrepared(false);
                $reservation->setPicked(false);

                
                $panier = $basketService->getBasket(); //recupere le panier en session
                $total = end($panier)["total"]; //recupere le total au dernier index du tableau

                $reservation->setTotalPrice($total);
                $ajd = new \DateTime();
                $reservation->setDateOrder($ajd); 
    
                $entityManager->persist($reservation); //prepare
                $entityManager->flush(); //execute

                //---------------------------------------------------entité booking------------------------------

                $panier = $basketService->getBasket();
                //il faut créer autant d'entité booking que de produit dans le panier
                foreach($panier as $p){
                    $booking = new Booking();
                    $booking->setProduct($p['produit']);
                    $booking->setReservation($reservation);
                    $booking->setQuantite($p['qtt']);

                    $entityManager->persist($booking); //prepare
                    $entityManager->flush(); //execute
                    
                }
                
                //-----------------------------------------envoie d'un email de confirmation
                $email = (new TemplatedEmail())
                ->from(new Address('admin-ceremonie-couture@exemple.fr', 'Ceremonie Couture Bot'))
                ->to($user->getEmail())
                ->subject('Confirmation de commande')
                // ->text('Sending emails is fun again!')
                // pass variables (name => value) to the template
                ->context([
                    'reservation' => $reservation
                ])
                // ->addPart((new DataPart(new File('/path/to/images/signature.gif'), 'image-produit', 'image'))->asInline())
                ->htmlTemplate('email/confirmationCommande.html.twig')
                ;

                $mailer->send($email);

                $this->addFlash('success', 'Réservation effectuée');
                return $this->redirectToRoute('app_profil');
            }

        }

        return $this->render('shop/reservation.html.twig', [
            'form' => $form
        ]);

    }
}
