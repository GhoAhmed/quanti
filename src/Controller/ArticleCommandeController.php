<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleCommandeController extends AbstractController
{
    /**
     * @Route("/article/commande", name="app_article_commande")
     */
    public function index(): Response
    {
        return $this->render('article_commande/index.html.twig', [
            'controller_name' => 'ArticleCommandeController',
        ]);
    }
}
