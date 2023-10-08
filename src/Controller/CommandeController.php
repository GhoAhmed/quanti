<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CommandeController extends AbstractController
{
    /**
     * @Route("/flow/orders_to_csv", name="get_new_orders", methods={"GET"})
     */
    public function getNewOrders(HttpClientInterface $httpClient): Response
    {
        try {
            // Récupérer les nouvelles commandes depuis l'API e-commerce
            $newOrders = $this->getNewOrdersFromCommerceAPI($httpClient);

            // Stocker les nouvelles commandes en base de données
            $this->storeNewOrdersInDatabase($newOrders);

            // Générer un fichier CSV avec les nouvelles commandes
            $csvData = $this->generateCSVFile($newOrders);

            // Répondre avec le fichier CSV en tant que réponse HTTP
            return $this->createCSVResponse($csvData);
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
        $processedOrders = $this->getProcessedOrdersFromDatabase();

        // Afficher la liste des commandes dans une vue (HTML)
        return $this->render('commande/index.html.twig', [
            'processedOrders' => $processedOrders,
        ]);
    }


    private function getNewOrdersFromCommerceAPI(HttpClientInterface $httpClient): array
    {
        // Utiliser HttpClient pour faire la requête à l'API e-commerce
        $response = $httpClient->request('GET', 'https://internshipapi-pylfsebcoa-ew.a.run.app', [
            'headers' => [
                'x-api-key' => 'PMAK-62642462da39cd50e9ab4ea7-815e244f4fdea2d2075d8966cac3b7f10b',
            ],
        ]);

        // Traiter la réponse de l'API et extraire les nouvelles commandes
        $data = $response->toArray();
        $newOrders = [];

        foreach ($data['results'] as $orderData) {
            $newOrder = [
                'Amount' => $orderData['Amount'],
                'Currency' => $orderData['Currency'],
                'OrderID' => $orderData['OrderID'],
                'OrderNumber' => $orderData['OrderNumber'],
                'DeliverTo' => $orderData['DeliverTo'],
                'SalesOrderLines' => [],
            ];

            foreach ($orderData['SalesOrderLines']['results'] as $salesOrderLineData) {
                $newOrderLine = [
                    'Amount' => $salesOrderLineData['Amount'],
                    'Description' => $salesOrderLineData['Description'],
                    'Discount' => $salesOrderLineData['Discount'],
                    'Item' => $salesOrderLineData['Item'],
                    'ItemDescription' => $salesOrderLineData['ItemDescription'],
                    'Quantity' => $salesOrderLineData['Quantity'],
                    'UnitCode' => $salesOrderLineData['UnitCode'],
                    'UnitDescription' => $salesOrderLineData['UnitDescription'],
                    'UnitPrice' => $salesOrderLineData['UnitPrice'],
                    'VATAmount' => $salesOrderLineData['VATAmount'],
                    'VATPercentage' => $salesOrderLineData['VATPercentage'],
                ];

                $newOrder['SalesOrderLines'][] = $newOrderLine;
            }

            $newOrders[] = $newOrder;
        }

        return $newOrders;
    }


    private function storeNewOrdersInDatabase(array $newOrders): void
    {
        // Logique pour insérer les nouvelles commandes dans la base de données (utilisez Doctrine)
        // Exemple fictif :
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($newOrders as $orderData) {
            $commande = new Commande();
        
            // Assigner les propriétés de la commande à partir des données de l'API
            $commande->setNumCommande($orderData['order']);
            $commande->setNomDestinataire($orderData['delivery_name']);
            $commande->setAdresseLivraison($orderData['delivery_address']);
            $commande->setPaysLivraison($orderData['delivery_country']);
            $commande->setCodePostal($orderData['delivery_zipcode']);
            $commande->setVilleLivraison($orderData['delivery_city']);
            //$commande->setNombreArticles($orderData['items_count']);
            //$commande->setIndexArticle($orderData['item_index']);
            //$commande->setUniqueIDArticle($orderData['item_id']);
            //$commande->setPrixLigneHorsTVA($orderData['line_price_excl_vat']);
            //$commande->setPrixLigneAvecTVA($orderData['line_price_incl_vat']);
        
            // Assurez-vous d'ajouter la commande à l'EntityManager
            $entityManager->persist($commande);
        }
        
        // Enfin, exécutez flush() pour sauvegarder les nouvelles commandes dans la base de données
        $entityManager->flush();
    }

    private function generateCSVFile(array $newOrders): string
    {
        // Créez l'en-tête CSV
        $csvData = "champ,description,order,delivery_name,delivery_address,delivery_country,delivery_zipcode,delivery_city,items_count,item_index,item_id,item_quantity,line_price_excl_vat,line_price_incl_vat\n";

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


    private function createCSVResponse(string $csvData): Response
    {
        $response = new Response($csvData);

        // Définir les en-têtes de la réponse pour indiquer qu'il s'agit d'un fichier CSV à télécharger
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="new_orders.csv"');

        return $response;
    }

    private function getProcessedOrdersFromDatabase(): array
    {
        // Utiliser Doctrine pour récupérer les commandes déjà traitées
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Commande::class);

        // Exemple : récupérer les commandes traitées au cours des 7 derniers jours
        $dateThreshold = new \DateTime('-7 days');
        $processedOrders = $repository->findBy([
            'dateTraitement' => $dateThreshold,
        ]);

        return $processedOrders;
    }
}