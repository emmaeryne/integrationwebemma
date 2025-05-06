<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Produit;

#[ORM\Entity]
class Commande_produits
{

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: "commande_produitss")]
    #[ORM\JoinColumn(name: 'idCommande', referencedColumnName: 'idCommande', onDelete: 'CASCADE')]
    private Commande $idCommande;

    #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: "commande_produitss")]
    #[ORM\JoinColumn(name: 'idProduit', referencedColumnName: 'Id', onDelete: 'CASCADE')]
    private Produit $idProduit;

    #[ORM\Column(type: "integer")]
    private int $quantite;

    #[ORM\Column(type: "float")]
    private float $prixUnitaire;

    public function getIdCommande()
    {
        return $this->idCommande;
    }

    public function setIdCommande($value)
    {
        $this->idCommande = $value;
    }

    public function getIdProduit()
    {
        return $this->idProduit;
    }

    public function setIdProduit($value)
    {
        $this->idProduit = $value;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($value)
    {
        $this->quantite = $value;
    }

    public function getPrixUnitaire()
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire($value)
    {
        $this->prixUnitaire = $value;
    }
}
