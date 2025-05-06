<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Utilisateur;

#[ORM\Entity]
class Paiement
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idPaiement;

        #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: "paiements")]
    #[ORM\JoinColumn(name: 'idCommande', referencedColumnName: 'idCommande', onDelete: 'CASCADE')]
    private Commande $idCommande;

        #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: "paiements")]
    #[ORM\JoinColumn(name: 'idUtilisateur', referencedColumnName: 'idUtilisateur', onDelete: 'CASCADE')]
    private Users $idUtilisateur;

    #[ORM\Column(type: "float")]
    private float $montant;

    #[ORM\Column(type: "string")]
    private string $modeDePaiement;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $dateDePaiement;

    #[ORM\Column(type: "string")]
    private string $status;

    public function getIdPaiement()
    {
        return $this->idPaiement;
    }

    public function setIdPaiement($value)
    {
        $this->idPaiement = $value;
    }

    public function getIdCommande()
    {
        return $this->idCommande;
    }

    public function setIdCommande($value)
    {
        $this->idCommande = $value;
    }

    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur($value)
    {
        $this->idUtilisateur = $value;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant($value)
    {
        $this->montant = $value;
    }

    public function getModeDePaiement()
    {
        return $this->modeDePaiement;
    }

    public function setModeDePaiement($value)
    {
        $this->modeDePaiement = $value;
    }

    public function getDateDePaiement()
    {
        return $this->dateDePaiement;
    }

    public function setDateDePaiement($value)
    {
        $this->dateDePaiement = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }
}
