<?php

namespace App\Entity;

use App\Entity\Avis;
use App\Entity\CategorieProduit;
use App\Entity\Commande_produits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[UniqueEntity(fields: ['NomProduit'], message: "Ce produit existe déjà.")]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer", name:"Id")]
    private int $id;

    #[ORM\Column(type:"string", length:50)]
    #[Assert\NotBlank(message: "Le nom du produit ne peut pas être vide.")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le nom du produit doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom du produit ne peut pas contenir plus de {{ limit }} caractères."
    )]
    private string $NomProduit;

    #[ORM\ManyToOne(targetEntity: CategorieProduit::class, inversedBy: "produits")]
    #[ORM\JoinColumn(name: 'Categorie', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Assert\NotNull(message: "La catégorie est obligatoire.")]
    private ?CategorieProduit $Categorie = null;

    #[ORM\Column(type:"float")]
    #[Assert\NotBlank(message: "Le prix est requis.")]
    #[Assert\Positive(message: "Le prix doit être positif.")]
    private float $Prix;

    #[ORM\Column(type:"integer")]
    #[Assert\NotNull(message: "Le stock disponible est requis.")]
    #[Assert\PositiveOrZero(message: "Le stock doit être positif ou nul.")]
    private int $StockDispo;

    #[ORM\Column(type:"datetime")]
    #[Assert\NotBlank(message: "La date est requise.")]
    #[Assert\Type("\DateTimeInterface", message: "La date doit être au format valide.")]
    private \DateTimeInterface $Date;

    #[ORM\Column(type:"string", length:255)]
    #[Assert\NotBlank(message: "Le fournisseur est obligatoire.")]
    private string $Fournisseur;

    #[ORM\OneToMany(mappedBy: "produit", targetEntity: Avis::class, cascade: ["persist", "remove"])]
    private Collection $aviss;

    #[ORM\OneToMany(mappedBy: "idProduit", targetEntity: Commande_produits::class)]
    private Collection $commande_produitss;

    public function __construct()
    {
        $this->aviss = new ArrayCollection();
        $this->commande_produitss = new ArrayCollection();

        // Initialisation de la date par défaut : date actuelle.
        $this->Date = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): self
    {
        $this->id = $value;
        return $this;
    }

    public function getNomProduit(): string
    {
        return $this->NomProduit;
    }

    public function setNomProduit(string $value): self
    {
        $this->NomProduit = $value;
        return $this;
    }

    public function getCategorie(): ?CategorieProduit
    {
        return $this->Categorie;
    }

    public function setCategorie(?CategorieProduit $value): self
    {
        $this->Categorie = $value;
        return $this;
    }

    public function getPrix(): float
    {
        return $this->Prix;
    }

    public function setPrix(float $value): self
    {
        $this->Prix = $value;
        return $this;
    }

    public function getStockDispo(): int
    {
        return $this->StockDispo;
    }

    public function setStockDispo(int $value): self
    {
        $this->StockDispo = $value;
        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $value): self
    {
        $this->Date = $value;
        return $this;
    }

    public function getFournisseur(): string
    {
        return $this->Fournisseur;
    }

    public function setFournisseur(string $value): self
    {
        $this->Fournisseur = $value;
        return $this;
    }

    /** @return Collection<int, Avis> */
    public function getAviss(): Collection
    {
        return $this->aviss;
    }

    public function addAvis(Avis $avis): self
    {
        if (!$this->aviss->contains($avis)) {
            $this->aviss[] = $avis;
            $avis->setProduit($this);
        }
        return $this;
    }

    public function removeAvis(Avis $avis): self
    {
        if ($this->aviss->removeElement($avis)) {
            if ($avis->getProduit() === $this) {
                $avis->setProduit(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Commande_produits> */
    public function getCommande_produitss(): Collection
    {
        return $this->commande_produitss;
    }

    public function addCommande_produits(Commande_produits $commande_produits): self
    {
        if (!$this->commande_produitss->contains($commande_produits)) {
            $this->commande_produitss[] = $commande_produits;
            $commande_produits->setIdProduit($this);
        }
        return $this;
    }

    public function removeCommande_produits(Commande_produits $commande_produits): self
    {
        if ($this->commande_produitss->removeElement($commande_produits)) {
            if ($commande_produits->getIdProduit() === $this) {
                $commande_produits->setIdProduit(null);
            }
        }
        return $this;
    }
}
