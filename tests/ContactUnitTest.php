<?php

namespace App\Tests;

use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactUnitTest extends TestCase
{
    public function TestIsTrue()
    {
        $contact = new Contact();
        $contact->setNom('nom')
             ->setEmail('true@test.com')
             ->setTel('+21612345678');

        $this->assertTrue($contact->getNom() === 'nom');
        $this->assertTrue($contact->getEmail() === 'true@test.com');
        $this->assertTrue($contact->getTel() === '+21612345678');
    }

    public function TestIsFalse()
    {
        $contact = new Contact();
        $contact->setNom('nom')
             ->setEmail('false@test.com')
             ->setTel('+21612345678');

        $this->assertFalse($contact->getNom() === 'nom');
        $this->assertFalse($contact->getEmail() === 'false@test.com');
        $this->assertFalse($contact->getTel() === '+21612345678');
    }

    public function TestIsEmpty()
    {
        $contact = new Contact();
        
        $this->assertEmpty($contact->getNom());
        $this->assertEmpty($contact->getEmail());
        $this->assertEmpty($contact->getTel());
    }
}