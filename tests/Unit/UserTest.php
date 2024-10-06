<?php

// self::bootKernel() comme on ne fait pas de requetes http, il faut le démarrer manuellement, pour accéder à des services, via un conteneur

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity() : User
    {
        return (new User)
        ->setPseudo("pseudo")
        ->setEmail("testunit@test.fr")
        ->setRoles(['ROLE_USER'])
        ->setPassword("...")
        ->setVerified(false)
        ->setGoogleUser(false);

    }

    //récupère correctement?
    public function testEntityIsValid() 
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();
        $errors = $container->get('validator')->validate($user);

        $this->assertCount(0, $errors);
    }

    //nous informe que le pseudo est invalide?
    public function testInvalidPseudo(){
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();
        $user->setPseudo("");

        $errors = $container->get('validator')->validate($user);

        //on s'attend à 2 erreurs puisque le pseudo ne peut ni etre blanc ni < 2 caractères
        $this->assertCount(2, $errors);
    }


}
