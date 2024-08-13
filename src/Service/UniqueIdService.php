<?php
//--------------------------------------------------crée un identifiant unique

namespace App\Service;

class UniqueIdService{

    function generateUniqueId() {

        $ajd = time(); // timestamp actuel
        $random = mt_rand(10, 100); // nb aléatoire entre 10 et 100
        
        // combine ces éléments
        $uniqueId = $ajd . $random;
        
        return $uniqueId;
    }
    

}