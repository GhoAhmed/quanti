<?php

namespace App\Controller;

use App\Analytics\ICustomerData;
use App\Analytics\IQuantilesData;
use App\Analytics\ITopCustomer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Entity\Contact;

class AnalyticsController extends AbstractController
{
    private $customerData;
    private $topCustomers;
    private $quantilesData;

    public function __construct(ICustomerData $customerData, ITopCustomer $topCustomers, IQuantilesData $quantilesData)
    {
        $this->customerData = $customerData;
        $this->topCustomers = $topCustomers;
        $this->quantilesData = $quantilesData;
    }
    /**
     * @Route("/analytics", name="app_analytics")
     */
    public function index(): Response
    {
        // Calcule les données des clients
        $customerData = $this->customerData->calculateCustomerData();

        // Calcule le top_customer
        $topCustomers = $this->topCustomers->calculateTopCustomers($customerData);

        // Calcule les quantiles et autres données
        $quantilesData = $this->quantilesData->calculateQuantilesData($customerData);

        // Créez un tableau résultant
        $result = [
            'top_customer' => $topCustomers,
            'quantiles_data' => $quantilesData,
        ];

        // Retournez le résultat au format JSON
        return new JsonResponse($result);
    }

    
    

   

    

    

}