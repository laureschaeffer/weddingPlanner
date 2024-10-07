<?php

// test fonctionnel: requete + interagir + tester réponse

/*
- static::createClient -> crée un client HTTP simulé, permet d'effectuer des requêtes HTTP à l'application sans avoir besoin d'un serveur web réel. Il démarre automatiquement le kernel de Symfony afin que l'application soit prête à recevoir des requêtes.
- $client->request('GET', '/') -> simule requete http get vers le chemin / (page d'accueil)
----assertion----
- $this->assertResponseIsSuccessful() -> verifie que la requete get a abouté une réponse type 200
- $this->assertEquals(5, count($serviceCards)) -> vérifie que le nb d'éléments avec la classe service-card sur la page est égal à 5
- $this->assertSelectorTextContains("h1", "...") -> vérifie que le texte du h1 est bien celui-ci

*/
namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    //vérifie 3 comportements de la page d'accueil
    public function testGetHomePage(): void
    {
        $client = static::createClient(); //boot kernel
        $crawler = $client->request('GET', '/'); //crée une requete

        // composants de la page 
        $serviceCards = $crawler->filter('.service-card'); //equivalent du querySelector js

        // -----assertions-----
        $this->assertResponseIsSuccessful(); 
        $this->assertEquals(5, count($serviceCards));
        $this->assertSelectorTextContains("h1", "Cérémonie Couture, organisation de mariages d'exception");
    }
}
