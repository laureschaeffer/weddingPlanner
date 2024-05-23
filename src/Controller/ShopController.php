<?php

//----------------------controller pour la partie commerce

namespace App\Controller;

use App\Repository\BatchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
