<?php

namespace App\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CommerceApiClient implements ICommerceApiClient
{
    private $httpClient;
    private $apiUrl;
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiUrl, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function getNewOrders(): array
    {
        try {
            // Make a request to your e-commerce API to fetch new orders
            $response = $this->httpClient->request('GET', $this->apiUrl, [
                'headers' => [
                    'x-api-key' => $this->apiKey,
                ],
            ]);

            // Process the response and map it to the required format
            $data = $response->toArray();
            $newOrders = [];

            foreach ($data['results'] as $orderData) {
                $newOrder = [
                    'order' => $orderData['OrderID'],
                    'delivery_name' => $orderData['DeliverTo'],
                    'delivery_address' => $orderData['DeliveryAddress'],
                    'delivery_country' => $orderData['DeliveryCountry'],
                    'delivery_zipcode' => $orderData['DeliveryZipCode'],
                    'delivery_city' => $orderData['DeliveryCity'],
                    'items_count' => count($orderData['SalesOrderLines']['results']),
                    'item_index' => 0, // Replace with actual item index
                    'item_id' => 0, // Replace with actual item ID
                    'item_quantity' => 0, // Replace with actual item quantity
                    'line_price_excl_vat' => 0.0, // Replace with actual price
                    'line_price_incl_vat' => 0.0, // Replace with actual price
                ];

                $newOrders[] = $newOrder;
            }

            return $newOrders;
        } catch (\Exception $e) {
            // Handle API errors here and return an empty array or log the error
            return [];
        }
    }
}