<?php

namespace App\Tests;

use App\Entity\Commande;
use PHPUnit\Framework\TestCase;

class CommandeUnitTest extends TestCase
{
    public function TestIsTrue()
    {
        $commande = new Commande();
        $commande->setNumCommande('1234')
             ->setNomDestinataire('testTrue')
             ->setAdresseLivraison('Tunis Kasserine 1200')
             ->setItems([1,'produit_num1']);
             
        $this->assertTrue($commande->getNumCommande() === 'true@test.com');
        $this->assertTrue($commande->getNomDestinataire() === 'testTrue');
        $this->assertTrue($commande->getAdresseLivraison() === 'Tunis Kasserine 1200');
        $this->assertTrue($commande->getItems() === [1,'produit_num1']);
    }

    public function TestIsFalse()
    {
        $commande = new Commande();
        $commande->setNumCommande('1234')
                 ->setNomDestinataire('testFalse')
                 ->setAdresseLivraison('Tunis Kasserine 1200')
                 ->setItems([1,'produit_num1']);

        $this->assertFalse($commande->getNumCommande() === 'false@test.com');
        $this->assertFalse($commande->getNomDestinataire() === 'password');
        $this->assertFalse($commande->getAdresseLivraison() === 'prenom');
        $this->assertFalse($commande->getItems() === 'nom');
        
    }

    public function TestIsEmpty()
    {
        $commande = new Commande();
        
        $this->assertEmpty($commande->getNumCommande());
        $this->assertEmpty($commande->getNomDestinataire());
        $this->assertEmpty($commande->getAdresseLivraison());
        $this->assertEmpty($commande->getItems());
        
    }
}