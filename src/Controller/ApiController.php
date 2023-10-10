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
            ['id' => 1, 'customer' => 'John Doe', 'total' => 100.0],
            ['id' => 2, 'customer' => 'Jane Smith', 'total' => 75.0],
            // Add more orders here
        ];

        return $this->json($orders);
        
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}