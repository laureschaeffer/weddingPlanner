<?php

//----------------------controller pour la partie commerce

namespace App\Controller;

use App\Entity\Batch;
use App\Entity\Product;
use App\Repository\BatchRepository;
use App\Repository\ProductRepository;
use App\Service\BasketService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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

    //augmente la quantité d'un produit
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
        $basketService->removeProduct($id);

        $this->addFlash('success', 'Produit retiré');
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
}
