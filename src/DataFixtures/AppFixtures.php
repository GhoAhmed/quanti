<?php

namespace App\DataFixtures;

use App\Entity\Commande;
use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $contact = new Contact();
            $contact->setNom('Nom du contact ' . $i);
            $contact->setEmail('contact' . $i . '@example.com');
            $contact->setTel('123456789' . $i);
            $contact->setAccountName('Compte du contact ' . $i);
            $contact->setAddressLine1('Adresse 1 du contact ' . $i);
            $contact->setAddressLine2('Adresse 2 du contact ' . $i);
            $contact->setCity('Ville du contact ' . $i);
            $contact->setContactName('Nom du contact ' . $i);
            $contact->setZip('CodePostal' . $i);

            $manager->persist($contact);
        }

        for ($i = 0; $i < 10; $i++) {
            $commande = new Commande();
            $commande->setNumCommande('CMD' . $i);
            $commande->setNomDestinataire('Destinataire ' . $i);
            $commande->setAdresseLivraison('Adresse ' . $i);
            
            // Create an array of items (in JSON format)
            $items = [
                'item1' => 'Description de l\'item 1',
                'item2' => 'Description de l\'item 2',
                // Add other elements as needed
            ];
            $commande->setItems($items);
            
            $commande->setPaysLivraison('Pays ' . $i);
            $commande->setCodePostal('CodePostal ' . $i);
            $commande->setVilleLivraison('Ville ' . $i);
            $commande->setDateTraitement(new \DateTime());
            
            // Associate the Commande with a Contact (use the appropriate Contact ID)
            $contact = $manager->getRepository(Contact::class)->find($i + 1); // Use $i + 1 as the Contact ID
            $commande->setContact($contact);

            $manager->persist($commande);
        }

        $manager->flush();
    }
}