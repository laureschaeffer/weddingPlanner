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

        $pdfOptions = new Options();

        // $pdfOptions->set('defaultFont', '');
        // $this->domPdf->setOptions();
    }

    //affiche le pdf
    public function showPdf($html){
        $this->domPdf->loadHtml($html); // use the dompdf class
        $this->domPdf->render(); // Render the HTML as PDF
        $this->domPdf->stream("details.pdf", [
            'Attachement' => false
        ]); // Output the generated PDF to Browser
    }

}