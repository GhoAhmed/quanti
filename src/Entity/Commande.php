<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numCommande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomDestinataire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseLivraison;

    /**
     * @ORM\Column(type="array")
     */
    private $items = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ArticleCommande::class, mappedBy="commande", orphanRemoval=true)
     */
    private $articleCommandes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paysLivraison;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $villeLivraison;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTraitement;

    /**
     * @ORM\ManyToOne(targetEntity=Contact::class, inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contact;

    public function __construct()
    {
        $this->articleCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCommande(): ?string
    {
        return $this->numCommande;
    }

    public function setNumCommande(string $numCommande): self
    {
        $this->numCommande = $numCommande;

        return $this;
    }

    public function getNomDestinataire(): ?string
    {
        return $this->nomDestinataire;
    }

    public function setNomDestinataire(string $nomDestinataire): self
    {
        $this->nomDestinataire = $nomDestinataire;

        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(string $adresseLivraison): self
    {
        $this->adresseLivraison = $adresseLivraison;

        return $this;
    }

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, ArticleCommande>
     */
    public function getArticleCommandes(): Collection
    {
        return $this->articleCommandes;
    }

    public function addArticleCommande(ArticleCommande $articleCommande): self
    {
        if (!$this->articleCommandes->contains($articleCommande)) {
            $this->articleCommandes[] = $articleCommande;
            $articleCommande->setCommande($this);
        }

        return $this;
    }

    public function removeArticleCommande(ArticleCommande $articleCommande): self
    {
        if ($this->articleCommandes->removeElement($articleCommande)) {
            // set the owning side to null (unless already changed)
            if ($articleCommande->getCommande() === $this) {
                $articleCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getPaysLivraison(): ?string
    {
        return $this->paysLivraison;
    }

    public function setPaysLivraison(string $paysLivraison): self
    {
        $this->paysLivraison = $paysLivraison;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVilleLivraison(): ?string
    {
        return $this->villeLivraison;
    }

    public function setVilleLivraison(string $villeLivraison): self
    {
        $this->villeLivraison = $villeLivraison;

        return $this;
    }

    public function getDateTraitement(): ?\DateTimeInterface
    {
        return $this->dateTraitement;
    }

    public function setDateTraitement(\DateTimeInterface $dateTraitement): self
    {
        $this->dateTraitement = $dateTraitement;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}