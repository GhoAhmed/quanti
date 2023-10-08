<?php

namespace App\Entity;

use App\Repository\ArticleCommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleCommandeRepository::class)
 */
class ArticleCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="float")
     */
    private $prixTVA;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="articleCommandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commade;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="articleCommande", orphanRemoval=true)
     */
    private $article;

    public function __construct()
    {
        $this->article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPrixTVA(): ?float
    {
        return $this->prixTVA;
    }

    public function setPrixTVA(float $prixTVA): self
    {
        $this->prixTVA = $prixTVA;

        return $this;
    }

    public function getCommade(): ?Commande
    {
        return $this->commade;
    }

    public function setCommade(?Commande $commade): self
    {
        $this->commade = $commade;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
            $article->setArticleCommande($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getArticleCommande() === $this) {
                $article->setArticleCommande(null);
            }
        }

        return $this;
    }
}