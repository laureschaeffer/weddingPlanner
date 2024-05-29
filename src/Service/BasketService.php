<?php
//---------------------------------------------------------------------gere les fonctionnalités liées au panier

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

    //ajoute un produit à la session
    public function addToBasket(int $id): void
    {
        $panier = $this->getSession()->get('panier', []);

        
        //si dans le panier l'id est trouvé, augmente la quantité, sinon ajoute le
        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        
        //met à jour le tableau
        $this->getSession()->set('panier', $panier);
        
        
    }

    //-----------------------------------------------------------------------------------------quantités---------------------------------------------------------------

    //augmente la quantité d'un produit
    public function increaseProduct(int $id){

        $panier = $this->getSession()->get('panier');
        $text= "";
        // vérifier que l'id existe dans $panier, l'index correspond à l'id du produit
        if(array_key_exists($id, $panier)) {
            //maximum de 20 produits
            if($panier[$id] < 20){
                $panier[$id]+=1;
                $text= "Quantité augmentée";
            } else {
                $text= "Quantité maximale atteinte";
            }
        } else {
            $text = "Ce produit n'est pas dans le panier";
        }

        //met à jour le tableau
        $this->getSession()->set('panier', $panier);
        return $text;
    }
    
    
    //diminue la quantité d'un produit
    public function decreaseProduct(int $id){

        $panier = $this->getSession()->get('panier');
        $text= "";

        // vérifier que l'id existe dans $panier, l'index correspond à l'id du produit
        if(array_key_exists($id, $panier)) {
            
            //pour ne pas avoir de panier négatif
            if($panier[$id] <= 1){
                unset($panier[$id]);
                $text = "Produit supprimé"; //msg de notif
            } else {
                $panier[$id]--;
                $text = "Quantité diminuée";
            }

        } else {
            $text = "Ce produit n'est pas dans le panier";
        }


        //met à jour le tableau
        $this->getSession()->set('panier', $panier);
        return $text;
    }
    

    //-----------------------------------------------------------------------------------------suppression---------------------------------------------------------------

    //supprime un produit du panier
    public function removeProduct(int $id){
        $panier = $this->getSession()->get('panier');

        // vérifier que l'id existe dans $panier, l'index correspond à l'id du produit
        if(array_key_exists($id, $panier)) {

            //retire dans le panier la variable contenant cet id
            unset($panier[$id]);
            $text= "Produit retiré";
        } else {
            $text = "Ce produit n'est pas dans le panier";
        }
        $this->getSession()->set('panier', $panier);
        return $text;
    }

    //supprime le panier
    public function deleteBasket(){
        return $this->getSession()->remove('panier');
    }

    //-----------------------------------------------------------------------------------------affiche---------------------------------------------------------------

    //recupère le panier
    public function getBasket(): array
    {
        $panier = $this->getSession()->get('panier');
        $panierData= [];

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