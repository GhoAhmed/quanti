<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

class ContactController extends AbstractController
{
   /**
     * @Route("/contacts", name="list_contacts", methods={"GET"})
     */
    public function listContacts(HttpClientInterface $httpClient): Response
    {
        try {
            // Récupérer les contacts depuis l'API e-commerce
            $contacts = $this->getContactsFromCommerceAPI($httpClient);

            // Stocker les contacts en base de données
            $this->storeContactsInDatabase($contacts);

            // Afficher la liste des contacts dans une vue (HTML)
            return $this->render('contact/index.html.twig', [
                'contacts' => $contacts,
            ]);
        } catch (ClientException $e) {
            // Gérez l'erreur d'accès à l'API en renvoyant une réponse d'erreur
            return new Response('Erreur d\'accès à l\'API : ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Récupère les contacts depuis l'API e-commerce.
     *
     * @param HttpClientInterface $httpClient
     * @return array
     */
    private function getContactsFromCommerceAPI(HttpClientInterface $httpClient): array
    {
        // Utiliser HttpClient pour faire la requête à l'API e-commerce
        $response = $httpClient->request('GET', 'https://internshipapi-pylfsebcoa-ew.a.run.app', [
            'headers' => [
                'x-api-key' => 'PMAK-62642462da39cd50e9ab4ea7-815e244f4fdea2d2075d8966cac3b7f10b',
            ],
        ]);

        // Traiter la réponse de l'API et extraire les contacts
        $data = $response->toArray();
        $contacts = [];

        foreach ($data['results'] as $contactData) {
            $contact = new Contact();
            $contact->setAccountName($contactData['AccountName']);
            $contact->setAddressLine1($contactData['AddressLine1']);
            $contact->setAddressLine2($contactData['AddressLine2']);
            $contact->setCity($contactData['City']);
            $contact->setContactName($contactData['ContactName']);
            $contact->setCountry($contactData['Country']);
            $contact->setZipCode($contactData['ZipCode']);
            $contacts[] = $contact;
        }

        return $contacts;
    }


    /**
     * Stocke les contacts en base de données.
     *
     * @param array $contacts
     */
    private function storeContactsInDatabase(array $contacts): void
    {
        // Logique pour insérer les contacts dans la base de données (utilisez Doctrine)
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($contacts as $contactData) {
            $contact = new Contact();

            // Assigner les propriétés du contact à partir des données de l'API
            $contact->setAccountName($contactData['AccountName']);
            $contact->setAddressLine1($contactData['AddressLine1']);
            $contact->setAddressLine2($contactData['AddressLine2']);
            $contact->setCity($contactData['City']);
            $contact->setContactName($contactData['ContactName']);
            $contact->setCountry($contactData['Country']);
            $contact->setZipCode($contactData['ZipCode']);

            // Assurez-vous d'ajouter le contact à l'EntityManager
            $entityManager->persist($contact);
        }

        // Enfin, exécutez flush() pour sauvegarder les contacts dans la base de données
        $entityManager->flush();
    }
}