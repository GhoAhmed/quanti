<?php

namespace App\Tests;

use App\Entity\Article;
use PHPUnit\Framework\TestCase;

class ArticleUnitTest extends TestCase
{
    public function TestIsTrue()
    {
        $article = new Article();
        $article->setNomProduit('testTrue')
                ->setDescription('this is a test')
                ->setPrix(100.00)
                ->setPrixTVA(120.00);
             
        $this->assertTrue($article->getNomProduit() === 'test');
        $this->assertTrue($article->getDescription() === 'this is a test');
        $this->assertTrue($article->getPrix() === 100.00);
        $this->assertTrue($article->getPrixTVA() === 120.00);
    }

    public function TestIsFalse()
    {
        $article = new Article();
        $article->setNomProduit('testFalse')
                ->setDescription('this is a test')
                ->setPrix(100.00)
                ->setPrixTVA(120.00);

        $this->assertFalse($article->getNomProduit() === 'testFalse');
        $this->assertFalse($article->getDescription() === 'this is a test');
        $this->assertFalse($article->getPrix() === '100.00');
        $this->assertFalse($article->getPrixTVA() === '120.00');
        
    }

    public function TestIsEmpty()
    {
        $article = new Article();
        
        $this->assertEmpty($article->getNomProduit());
        $this->assertEmpty($article->getDescription());
        $this->assertEmpty($article->getPrix());
        $this->assertEmpty($article->getPrixTVA());
        
    }
}