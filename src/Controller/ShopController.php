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

    //-------------------------------------------------------------------------interraction avec le panier-------------------------------------------------------

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
          
            return $this->redirectToRoute('app_basket');
        } else {
            $this->addFlash('error', 'Ce produit n\'existe pas');
            return $this->redirectToRoute('app_shop');
        }
        die;
    }

    //supprime le panier
    #[Route('/shop/supprimePanier', name: 'remove_basket')]
    public function removeBasket(BasketService $basketService){
        $basketService->removeBasket();

        $this->addFlash('succes', 'Panier supprimé');
        return $this->redirectToRoute('app_shop');
    }

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
