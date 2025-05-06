<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Paiement;

#[ORM\Entity]
class Commande
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idCommande;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $dateDeCommande;

    #[ORM\Column(type: "integer")]
    private int $idUtilisateur;

    #[ORM\Column(type: "string")]
    private string $status;

    public function getIdCommande()
    {
        return $this->idCommande;
    }

    public function setIdCommande($value)
    {
        $this->idCommande = $value;
    }

    public function getDateDeCommande()
    {
        return $this->dateDeCommande;
    }

    public function setDateDeCommande($value)
    {
        $this->dateDeCommande = $value;
    }

    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur($value)
    {
        $this->idUtilisateur = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    #[ORM\OneToMany(mappedBy: "idCommande", targetEntity: Commande_produits::class)]
    private Collection $commande_produitss;

        public function getCommande_produitss(): Collection
        {
            return $this->commande_produitss;
        }
    
        public function addCommande_produits(Commande_produits $commande_produits): self
        {
            if (!$this->commande_produitss->contains($commande_produits)) {
                $this->commande_produitss[] = $commande_produits;
                $commande_produits->setIdCommande($this);
            }
    
            return $this;
        }
    
        public function removeCommande_produits(Commande_produits $commande_produits): self
        {
            if ($this->commande_produitss->removeElement($commande_produits)) {
                // set the owning side to null (unless already changed)
                if ($commande_produits->getIdCommande() === $this) {
                    $commande_produits->setIdCommande(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "idCommande", targetEntity: Paiement::class)]
    private Collection $paiements;
}
