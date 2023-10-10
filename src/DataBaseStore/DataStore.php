<?php

namespace App\DataBaseStore;

use App\DataBaseStore\IDataStore;
use App\Entity\Commande;
use App\Entity\ArticleCommande; // Import the ArticleCommande entity
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

            // Assign properties to the Commande entity
            $commande->setNumCommande($orderData['order']);
            $commande->setNomDestinataire($orderData['delivery_name']);
            $commande->setAdresseLivraison($orderData['delivery_address']);
            $commande->setPaysLivraison($orderData['delivery_country']);
            $commande->setCodePostal($orderData['delivery_zipcode']);
            $commande->setVilleLivraison($orderData['delivery_city']);

            // Create and associate ArticleCommande entities
            foreach ($orderData['items'] as $itemData) {
                $articleCommande = new ArticleCommande();
                $articleCommande->setQuantite($itemData['item_quantity']);
                $articleCommande->setPrix($itemData['line_price_excl_vat']);
                $articleCommande->setPrixTVA($itemData['line_price_incl_vat']);
                
                // Fetch the Article entity based on item data (you need to implement this logic)
                $article = $this->findArticleByItemId($itemData['item_id']);
                $articleCommande->setArticle($article);

                // Set the ArticleCommande entity's relation to the Commande
                $articleCommande->setCommande($commande);

                // Add the ArticleCommande entity to the Commande
                $commande->addArticleCommande($articleCommande);
            }

            // Assurez-vous d'ajouter la commande à l'EntityManager
            $entityManager->persist($commande);
        }

        // Enfin, exécutez flush() pour sauvegarder les nouvelles commandes dans la base de données
        $entityManager->flush();
    }

    // Find Article by item ID
    private function findArticleByItemId($itemId)
    {
        // Get the Doctrine EntityManager
        $entityManager = $this->entityManager;

        // Replace 'Article' with the actual name of your Article entity class
        $articleRepository = $entityManager->getRepository(Article::class);

        // Query the Article entity by the item ID
        $article = $articleRepository->findOneBy(['itemId' => $itemId]);

        return $article;
    }




}