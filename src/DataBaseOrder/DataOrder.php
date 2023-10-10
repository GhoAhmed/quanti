<?php

namespace App\DataBaseOrder;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;

class DataOrder implements IDataOrder
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getProcessedOrders(): array
    {
        // Utilize Doctrine's EntityManager to fetch the processed orders
        $repository = $this->entityManager->getRepository(Commande::class);

        // Use Doctrine's QueryBuilder to construct a custom query
        $dateThreshold = new \DateTime('-7 days');
        $queryBuilder = $repository->createQueryBuilder('c')
            ->where('c.dateTraitement >= :dateThreshold')
            ->setParameter('dateThreshold', $dateThreshold)
            ->getQuery();

        // Execute the query to retrieve processed orders
        $processedOrders = $queryBuilder->getResult();

        return $processedOrders;
    }
}