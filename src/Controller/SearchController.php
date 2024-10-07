<?php

namespace App\Controller;

use App\Repository\BillRepository;
use App\Repository\ProjectRepository;
use App\Repository\QuotationRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{

    #[Route('/search/{word}', name: 'app_search')]
    //renvoie le resultat de la recherche en fonction d'un mot, fonction dans chaque repository ; mettre par défaut word à null pour ne pas avoir d'erreur
    public function index(ProjectRepository $pr, ReservationRepository $rr, QuotationRepository $qr, $word = null): Response
    {
        //honey pot field
        $honeypot= filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        
        
        //si je recois "firstname" c'est un robot, je redirige
        if($honeypot){
            return $this->redirectToRoute('app_home');
        } 
        
        //filtre
        $word = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS);

        if($word && $word !== " "){

            return $this->render('admin/research.html.twig', [
                'word' => $word,
                'projects' => $pr->findByWord($word),
                'reservations' => $rr->findByWord($word),
                'bills' => $qr->findFileByName($word)
            ]);
        } else {
            return $this->redirectToRoute('app_admin');
        }
        
            
        
    }

    //télécharge le devis ou la facture associée à la recherche
    #[Route('/search/file/{fileName}', name: 'download_file')]
    public function downloadSearchedFile($fileName){

        $fileName = $fileName . ".pdf";

        //si le nom du fichier commence par 'FACT_' alors c'est une facture, sinon un devis
        $fileType = str_starts_with($fileName, 'FACT_') ? "facture/" : "devis/" ;

        // chemin complet du fichier à partir du nom fourni
        $filePath = $this->getParameter('kernel.project_dir') . '/public/upload/' . $fileType . $fileName;


        // si le fichier n'existe pas
        if (!file_exists($filePath)) {
            $this->addFlash('error', 'Ce fichier n\'existe pas');
            return $this->redirectToRoute('app_admin');
        }

        // retourne le fichier pour téléchargement
        return $this->file($filePath, $fileName, 'application/pdf');
    }


}
