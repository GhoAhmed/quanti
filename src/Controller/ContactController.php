<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function contacts(HttpClientInterface $httpClient): Response
    {
        // Récupérer les contacts depuis l'API e-commerce
        $contacts = $this->getContactsFromCommerceAPI($httpClient);

        // Vous pouvez ensuite utiliser les contacts comme vous le souhaitez dans votre vue
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contacts' => $contacts, // Transmettre les contacts à la vue
        ]);
    }

    private function getContactsFromCommerceAPI(HttpClientInterface $httpClient): array
    {
        // Utiliser HttpClient pour faire la requête à l'API e-commerce
        $response = $httpClient->request('GET', 'https://4ebb0152-1174-42f0-ba9b-4d6a69cf93be.mock.pstmn.io/contacts', [
            'headers' => [
                'x-api-key' => 'PMAK-62642462da39cd50e9ab4ea7-815e244f4fdea2d2075d8966cac3b7f10b',
            ],
        ]);

        // Traiter la réponse de l'API et extraire les contacts
        $data = $response->toArray();
        $contacts = [];

        foreach ($data as $contactData) {
            $contact = [
                'ID' => $contactData['ID'],
                'AccountName' => $contactData['AccountName'],
                'AddressLine1' => $contactData['AddressLine1'],
                'AddressLine2' => $contactData['AddressLine2'],
                'City' => $contactData['City'],
                'ContactName' => $contactData['ContactName'],
                'Country' => $contactData['Country'],
                'ZipCode' => $contactData['ZipCode'],
            ];

            $contacts[] = $contact;
        }

        return $contacts;
    }

}