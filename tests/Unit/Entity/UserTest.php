<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCanGetAndSetData(): void
    {
        $user = (new User())
        ->setEmail("testunit@test.fr")
        ->setPseudo("testunit")
        ->setRoles(['ROLE_USER'])
        ->setPassword("...")
        ->setVerified(false)
        ->setGoogleUser(false)
        ;

        //assertSame correspond à "==="
        self::assertSame("testunit@test.fr", $user->getEmail());
        self::assertSame("testunit", $user->getPseudo());
        self::assertSame(['ROLE_USER'], $user->getRoles());
        self::assertSame("...", $user->getPassword());
        self::assertSame(false, $user->isVerified());
        self::assertSame(false, $user->getGoogleUser());
    }

    
}