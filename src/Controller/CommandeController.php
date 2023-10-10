<?php

namespace App\Controller;

use App\ApiClient\ICommerceApiClient;
use App\CSV\ICsvData;
use App\DataBaseOrder\IDataOrder;
use App\DataBaseStore\IDataStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CommandeController extends AbstractController
{

    private $commerceApiClient;
    private $dataStore;
    private $csvResponse;
    private $dataOrder;

    public function __construct(ICommerceApiClient $commerceApiClient, IDataStore $dataStore, ICsvData $csvResponse, IDataOrder $dataOrder)
    {
        $this->commerceApiClient = $commerceApiClient;
        $this->dataStore = $dataStore;
        $this->csvResponse = $csvResponse;
        $this->dataOrder = $dataOrder;
    }
    
    /**
     * @Route("/flow/orders_to_csv", name="get_new_orders", methods={"GET"})
     */
    public function getNewOrders(HttpClientInterface $httpClient): Response
    {
        try {
            // Récupérer les nouvelles commandes depuis l'API e-commerce
            $newOrders = $this->commerceApiClient->getNewOrders($httpClient);

            // Stocker les nouvelles commandes en base de données
            $this->dataStore->storeNewOrdersInDatabase($newOrders);

            // Générer un fichier CSV avec les nouvelles commandes
            $csvData = $this->csvResponse->generateCSVFile($newOrders);

            // Répondre avec le fichier CSV en tant que réponse HTTP
            return $this->csvResponse->createCSVResponse($csvData);
        } catch (ClientException $e) {
            // Gérez l'erreur d'accès à l'API ici, par exemple, en affichant un message d'erreur
            return new Response('Erreur d\'accès à l\'API : ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/orders", name="list_orders", methods={"GET"})
     */
    public function listOrders(): Response
    {
        // Logique pour récupérer la liste des commandes déjà traitées depuis la base de données
        $processedOrders = $this->dataOrder->getProcessedOrders();

        // Afficher la liste des commandes dans une vue (HTML)
        return $this->render('commande/index.html.twig', [
            'processedOrders' => $processedOrders,
        ]);
    }

}