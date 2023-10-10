<?php

namespace App\Analytics;

use Doctrine\ORM\EntityManagerInterface;

class TopCustomer implements ITopCustomer
{
    public function calculateTopCustomers(array $customerData): array
    {
        // Triez le tableau $customerData par valeur (chiffre d'affaires) de manière décroissante
        usort($customerData, function ($a, $b) {
            return $b['total_revenue'] - $a['total_revenue'];
        });

        // Prenez les 10 meilleurs clients
        $topCustomers = array_slice($customerData, 0, 10);

        return $topCustomers;
    }
}