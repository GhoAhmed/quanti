<?php

namespace App\DataBaseStore;

use App\DataBaseStore\IDataStore;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DataStore implements IDataStore
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function storeNewOrdersInDatabase(array $newOrders): void
    {
        $entityManager = $this->entityManager;
        
        foreach ($newOrders as $orderData) {
            $commande = new Commande();
        
            // Assigner les propriétés de la commande à partir des données de l'API
            $commande->setNumCommande($orderData['order']);
            $commande->setNomDestinataire($orderData['delivery_name']);
            $commande->setAdresseLivraison($orderData['delivery_address']);
            $commande->setPaysLivraison($orderData['delivery_country']);
            $commande->setCodePostal($orderData['delivery_zipcode']);
            $commande->setVilleLivraison($orderData['delivery_city']);
            //$commande->setNombreArticles($orderData['items_count']);
            //$commande->setIndexArticle($orderData['item_index']);
            //$commande->setUniqueIDArticle($orderData['item_id']);
            //$commande->setPrixLigneHorsTVA($orderData['line_price_excl_vat']);
            //$commande->setPrixLigneAvecTVA($orderData['line_price_incl_vat']);
        
            // Assurez-vous d'ajouter la commande à l'EntityManager
            $entityManager->persist($commande);
        }
        
        // Enfin, exécutez flush() pour sauvegarder les nouvelles commandes dans la base de données
        $entityManager->flush();
    }

}