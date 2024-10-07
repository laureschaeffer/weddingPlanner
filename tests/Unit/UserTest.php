<?php

/*
 - self::bootKernel() comme on ne fait pas de requetes http, il faut le démarrer manuellement, pour accéder à des services, via un conteneur, pour charger les services de l'application pour les tests
 $container = static::getContainer(); -> conteneur de services de Symfony est récupéré. Le conteneur contient tous les services de l'application, tels que le validateur, les gestionnaires de bases de données, etc. Ce conteneur est nécessaire pour accéder aux services durant le test.
- $errors = $container->get('validator')->validate($user); -> utilise le service de validation de Symfony (récupéré via le conteneur de services) pour valider l'objet User. La méthode validate() renvoie un objet ConstraintViolationList, qui contient toutes les violations des règles de validation pour cet objet utilisateur. Dans ce cas, il vérifiera que le champ pseudo de l'utilisateur n'est pas vide et qu'il respecte les contraintes définies dans sa classe. 
- $this->assertCount(2, $errors); -> Cette ligne est une assertion : elle vérifie que le nombre d'erreurs retournées par le validateur est exactement de 2
*/
namespace App\Tests\Unit;

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

    //nous informe-t'il bien que le pseudo est invalide?
    public function testInvalidPseudo(){
        self::bootKernel(); //démarre manuellement le kernel
        $container = static::getContainer(); //accède à des services

        $user = $this->getEntity(); //retourne objet user hydraté
        $user->setPseudo("");

        
        $errors = $container->get('validator')->validate($user);

        //on s'attend à 2 erreurs puisque le pseudo ne peut ni etre blanc ni < 2 caractères
        $this->assertCount(2, $errors);
    }


}
