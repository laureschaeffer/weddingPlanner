<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BasketService {

    private RequestStack $requestStack; 
    private EntityManagerInterface $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    //factorise la récupération de la session 
    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    //ajoute un produit à la session, appelé dans le controller
    public function addToBasket(int $id): void
    {
        $panier = $this->requestStack->getSession()->get('panier', []);

        
        //si dans le panier l'id est trouvé, augmente la quantité, sinon ajoute le
        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        
        //met à jour le tableau
        $this->getSession()->set('panier', $panier);
        
        
    }

    //supprime le panier
    public function removeBasket(){
        return $this->getSession()->remove('panier');
    }

    //recupère le panier
    public function getBasket(): array
    {
        $panier = $this->getSession()->get('panier');
        $panierData= [];
        $total=0;

        //si tu trouves un panier
        if($panier){
            //crée un tableau associatif plus propre et simple à manipuler
            foreach($panier as $id=> $qtt){
                //recupère l'objet produit
                $produit = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
                
                $panierData[]= [
                    'produit' => $produit,
                    'qtt' => $qtt,
                    //renvoie le sous-total du produit
                    'sousTotal' => $produit->getPrice() * $qtt
                ];
            }
        }

        return $panierData;
    }

}