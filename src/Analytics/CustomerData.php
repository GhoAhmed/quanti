<?php

namespace App\Analytics;

use App\Entity\Commande;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;

class CustomerData implements ICustomerData
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function calculateCustomerData(): array
    {
        $contactRepository = $this->entityManager->getRepository(Contact::class);

        // Récupérer tous les contacts
        $contacts = $contactRepository->findAll();

        $customerData = [];

        // Parcourir les contacts et calculer le chiffre d'affaires par client
        foreach ($contacts as $contact) {
            $customerId = $contact->getId();
            $totalRevenue = $this->calculateTotalRevenueByContact($contact); // Utilisez une fonction pour calculer le chiffre d'affaires total

            // Ajouter le chiffre d'affaires total du client
            $customerData[] = [
                'customer_id' => $customerId,
                'total_revenue' => $totalRevenue,
            ];
        }

        return $customerData;
    }

    private function calculateTotalRevenueByContact(Contact $contact): float
    {
        // Calculez le chiffre d'affaires total pour le contact donné en fonction de ses commandes
        $totalRevenue = 0;

        foreach ($contact->getCommandes() as $commande) {
            $totalRevenue += $this->calculateOrderTotal($commande);
        }

        return $totalRevenue;
    }

    private function calculateOrderTotal(Commande $commande): float
    {
        // Calculez le montant total de la commande en fonction des articles
        $total = 0;

        foreach ($commande->getArticleCommandes() as $articleCommande) {
            $total += $articleCommande->getQuantite() * $articleCommande->getPrix();
        }

        return $total;
    }

}