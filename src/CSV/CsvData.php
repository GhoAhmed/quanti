<?php

namespace App\CSV;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CsvData implements ICsvData
{
    public function generateCSVFile(array $newOrders): string
    {
        // Créez l'en-tête CSV
        $csvData = "order,delivery_name,delivery_address,delivery_country,delivery_zipcode,delivery_city,items_count,item_index,item_id,item_quantity,line_price_excl_vat,line_price_incl_vat\n";

        foreach ($newOrders as $order) {
            // Extraire les propriétés de l'ordre
            $orderNumber = $order['order'];
            $deliveryName = $order['delivery_name'];
            $deliveryAddress = $order['delivery_address'];
            $deliveryCountry = $order['delivery_country'];
            $deliveryZipcode = $order['delivery_zipcode'];
            $deliveryCity = $order['delivery_city'];
            $itemsCount = $order['items_count'];
            $itemIndex = $order['item_index'];
            $itemId = $order['item_id'];
            $itemQuantity = $order['item_quantity'];
            $linePriceExclVAT = $order['line_price_excl_vat'];
            $linePriceInclVAT = $order['line_price_incl_vat'];

            // Formatez les données dans une ligne CSV
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $orderNumber,
                $deliveryName,
                $deliveryAddress,
                $deliveryCountry,
                $deliveryZipcode,
                $deliveryCity,
                $itemsCount,
                $itemIndex,
                $itemId,
                $itemQuantity,
                $linePriceExclVAT,
                $linePriceInclVAT
            );
        }

        return $csvData;
    }

    public function createCSVResponse(string $csvData): Response
    {
        $response = new Response($csvData);

        // Définir les en-têtes de la réponse pour indiquer qu'il s'agit d'un fichier CSV à télécharger
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="new_orders.csv"');

        return $response;
    }

}