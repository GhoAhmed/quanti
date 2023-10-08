<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Entity\Contact;

class AnalyticsController extends AbstractController
{
    /**
     * @Route("/analytics", name="app_analytics")
     */
    public function index(): Response
    {
        // Calcule les données des clients
        $customerData = $this->calculateCustomerData();

        // Calcule le top_customer
        $topCustomers = $this->calculateTopCustomers($customerData);

        // Calcule les quantiles et autres données
        $quantilesData = $this->calculateQuantilesData($customerData);

        // Créez un tableau résultant
        $result = [
            'top_customer' => $topCustomers,
            'quantiles_data' => $quantilesData,
        ];

        // Retournez le résultat au format JSON
        return new JsonResponse($result);
    }

    private function calculateCustomerData(): array
    {
        $entityManager = $this->getDoctrine()->getManager();
        $contactRepository = $entityManager->getRepository(Contact::class);

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

    private function calculateTopCustomers(array $customerData): array
    {
        // Triez le tableau $customerData par valeur (chiffre d'affaires) de manière décroissante
        usort($customerData, function ($a, $b) {
            return $b['total_revenue'] - $a['total_revenue'];
        });

        // Prenez les 10 meilleurs clients
        $topCustomers = array_slice($customerData, 0, 10);

        return $topCustomers;
    }

    private function calculateQuantilesData(array $customerData): array
    {
        // Tri des clients par chiffre d'affaires
        usort($customerData, function ($a, $b) {
            return $a['total_revenue'] - $b['total_revenue'];
        });

        $totalCustomers = count($customerData);

        // Nombre de quantiles que vous souhaitez calculer
        $quantileCount = 10;

        // Tableau pour stocker les résultats
        $quantilesData = [];

        for ($i = 0; $i < $quantileCount; $i++) {
            // Calculez les limites inférieures et supérieures du quantile
            $lowerIndex = floor(($i / $quantileCount) * $totalCustomers);
            $upperIndex = floor((($i + 1) / $quantileCount) * $totalCustomers) - 1;

            $quantileCustomers = array_slice($customerData, $lowerIndex, $upperIndex - $lowerIndex + 1);

            // Calculez le nombre de clients dans ce quantile
            $quantileCustomerCount = count($quantileCustomers);

            // Calculez le chiffre d'affaires maximum de ce quantile
            $maxRevenue = 0;
            foreach ($quantileCustomers as $customer) {
                if ($customer['total_revenue'] > $maxRevenue) {
                    $maxRevenue = $customer['total_revenue'];
                }
            }

            // Ajoutez les données du quantile au tableau de résultats
            $quantilesData[] = [
                'quantile' => ($i + 1) * 10, // Par exemple, pour 10 quantiles, cela donnerait 10, 20, 30, ..., 100
                'customer_count' => $quantileCustomerCount,
                'max_revenue' => $maxRevenue,
            ];
        }

        return $quantilesData;
    }

}