<?php

namespace App\Analytics;

use Doctrine\ORM\EntityManagerInterface;

class QuantilesData implements IQuantilesData
{
    public function calculateQuantilesData(array $customerData): array
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