<?php

//----------------------controller pour la partie commerce

namespace App\Controller;

use App\Entity\Batch;
use App\Entity\Product;
use App\Repository\BatchRepository;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    //liste des collections avec quelques produits, "batch"= collection 
    #[Route('/shop', name: 'app_shop')]
    public function index(BatchRepository $batchRepository): Response
    {
        $collections = $batchRepository->findBy([]);

        return $this->render('shop/index.html.twig', [
            'collections' => $collections
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
    #[Route('/shop/ajoutePanier/{id}', name: 'add_basket')]
    public function addProduct(Product $product = null, SessionInterface $session)
    
    {



        // $newBasket = ['produit' =>$product,
        // 'qtt' => 1];

        // array_push($panier, $newBasket);

        // //met à jour les données dans la session
        // $session->set('panier', $panier);
        // $session->set('panier', [
        //     ['produit'=> $product,
        //     'qtt' =>1],
        //     ['produit' => $product,
        //     'qtt' => 2]
        // ]);
        
        // $qtt = $session->get("panier")["qtt"];
        
        // // //si dans la session qtt est different de 0, le mettre à jour
        

        return $this->redirectToRoute('app_basket');
        // the second argument is the value returned when the attribute doesn't exist
        // $filters = $session->get('filters', []);
        
    }

    //montre le panier
    #[Route('/shop/panier', name: 'app_basket')]
    public function showBasket(): Response 
    {

        return $this->render('shop/panier.html.twig', []);
    }
}
