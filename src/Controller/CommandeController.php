<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        // Récupérer les nouvelles commandes depuis l'API e-commerce
        $newOrders = $this->getNewOrdersFromCommerceAPI($httpClient);

        // Stocker les nouvelles commandes en base de données
        $this->storeNewOrdersInDatabase($newOrders);

        // Générer un fichier CSV avec les nouvelles commandes
        $csvData = $this->generateCSVFile($newOrders);

        // Répondre avec le fichier CSV en tant que réponse HTTP
        return $this->createCSVResponse($csvData);
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
        $response = $httpClient->request('GET', 'https://4ebb0152-1174-42f0-ba9b-4d6a69cf93be.mock.pstmn.io/orders', [
            'headers' => [
                'x-api-key' => 'PMAK-62642462da39cd50e9ab4ea7-815e244f4fdea2d2075d8966cac3b7f10b',
            ],
        ]);

        // Traiter la réponse de l'API et extraire les nouvelles commandes
        $data = $response->toArray();
        $newOrders = [];

        foreach ($data['SalesOrders'] as $salesOrder) {
            $newOrder = [
                'Amount' => $salesOrder['Amount'],
                'Currency' => $salesOrder['Currency'],
                'OrderID' => $salesOrder['OrderID'],
                'OrderNumber' => $salesOrder['OrderNumber'],
                'DeliverTo' => [
                    'ContactName' => $salesOrder['DeliverTo']['ContactName'],
                    'DeliveryAddress' => $salesOrder['DeliverTo']['DeliveryAddress'],
                    // ... Ajoutez d'autres champs de livraison si nécessaire
                ],
                'SalesOrderLines' => [],
            ];

            foreach ($salesOrder['SalesOrderLines'] as $salesOrderLine) {
                $newOrderLine = [
                    'Amount' => $salesOrderLine['Amount'],
                    'Discount' => $salesOrderLine['Discount'],
                    'Item' => $salesOrderLine['Item'],
                    'ItemDescription' => $salesOrderLine['ItemDescription'],
                    'Quantity' => $salesOrderLine['Quantity'],
                    'UnitCode' => $salesOrderLine['UnitCode'],
                    'UnitDescription' => $salesOrderLine['UnitDescription'],
                    'UnitPrice' => $salesOrderLine['UnitPrice'],
                    'VATAmount' => $salesOrderLine['VATAmount'],
                    'VATPercentage' => $salesOrderLine['VATPercentage'],
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