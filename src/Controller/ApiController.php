<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/orders", name="api_orders", methods={"GET"})
     */
    public function getOrders(): JsonResponse
    {
        // Replace this with your logic to fetch and return orders
        $orders = [
            [
                'id' => 1, 
                'order' => 'order 1', 
                'delivery_name' => 'Jhon Doe',
                'delivery_address' => 'Tunisia',
                'delivery_country' => 'Tunisia',
                'delivery_zipcode' => '1200',
                'delivery_city' => 'Kasserine',
                'items_count' => 100,
                'item_index' => 1,
                'item_id' => 1234,
                'item_quantity' => 1,
                'line_price_excl_vat' => 400.00,
                'line_price_incl_vat' => 420.00
            ],
        ];

        return $this->json($orders);
        
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}