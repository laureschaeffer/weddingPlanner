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
    // public function showPdf(){

    //     $html =  $this->domPdf->renderView('pdf/pdf.html.twig', ["titre" => "Mon titre"]);
    //     $this->domPdf->loadHtml($html);
    //     $this->domPdf->setPaper('A4', 'landscape');
    //     // Rendre le document PDF
    //     $this->domPdf->render();

    //     return $this->domPdf;
    // }






}