<?php
//---------------------------------------------------------------------gere la création de PDF

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService {

    private $domPdf;

    public function __construct()
    { 
        $this->domPdf = new Dompdf();

        // $pdfOptions = new Options();

        // $pdfOptions->set('defaultFont', '');
        // $this->domPdf->setOptions();
    }

    //affiche le pdf
    public function showPdf(){

        // Charger le contenu HTML à partir d'un fichier ou d'une chaîne
        $html = '<h1>Mon premier document PDF avec Dompdf</h1>';
        $this->domPdf->loadHtml($html);

        $this->domPdf->setPaper('A4', 'landscape');

        // Rendre le document PDF
        $this->domPdf->render();
        
        // Envoyer le document PDF à la sortie
        return $this->domPdf->stream('document.pdf');
        // $this->domPdf->stream("details.pdf", [
        //     'Attachement' => false
        // ]); // Output the generated PDF to Browser
    }






}