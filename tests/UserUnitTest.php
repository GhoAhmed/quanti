<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{

    public function TestIsTrue()
    {
        $user = new User();
        $user->setEmail('true@test.com')
             ->setPassword('password')
             ->setPrenom('prenom')
             ->setNom('nom');
             
        $this->assertTrue($user->getEmail() === 'true@test.com');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getPrenom() === 'prenom');
        $this->assertTrue($user->getNom() === 'nom');
    }

    public function TestIsFalse()
    {
        $user = new User();
        $user->setEmail('false@test.com')
             ->setPassword('password')
             ->setPrenom('prenom')
             ->setNom('nom');

        $this->assertFalse($user->getEmail() === 'false@test.com');
        $this->assertFalse($user->getPassword() === 'password');
        $this->assertFalse($user->getPrenom() === 'prenom');
        $this->assertFalse($user->getNom() === 'nom');
        
    }

    public function TestIsEmpty()
    {
        $user = new User();
        
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getPrenom());
        $this->assertEmpty($user->getNom());
        
    }
}