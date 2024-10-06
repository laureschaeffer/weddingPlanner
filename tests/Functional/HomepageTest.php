<?php

// test fonctionnel: requete + interagir + tester réponse
namespace App\Tests\Tests\functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient(); //boot kernel
        $crawler = $client->request('GET', '/'); //crée une requete

        // composants de la page 
        $serviceCards = $crawler->filter('.service-card'); //equivalent du querySelector js

        // -----test assert-----
        $this->assertResponseIsSuccessful(); 
        $this->assertEquals(5, count($serviceCards));
        $this->assertSelectorTextContains("h1", "Cérémonie Couture, organisation de mariages d'exception");
    }
}
