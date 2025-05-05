<?php

namespace App\Entity;

use App\Entity\Produit;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]//auto-incrémenté
    #[ORM\Column(type:"integer")]
    private int $id;

    // La relation vers Produit :
    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: "aviss")]// Chaque Avis est relié à un seul Produit (relation ManyToOne)
    #[ORM\JoinColumn(name: "produit_id", referencedColumnName: "Id", onDelete: "CASCADE")]
    #[Assert\NotNull(message: "Le produit est obligatoire.")]//le produit ne peut pas être nul
    private ?Produit $produit = null;

    // Le champ auteur, non vide et de longueur maximale 100
    #[ORM\Column(type:"string", length:100)]
    #[Assert\NotBlank(message: "L'auteur ne peut pas être vide.")]
    #[Assert\Length(
        max: 100,
        maxMessage: "L'auteur ne peut pas dépasser {{ limit }} caractères."
    )]//Doit être rempli, et ne pas dépasser 100 caractères.
    private string $auteur;

    // Le commentaire est requis
    #[ORM\Column(type:"text")]
    #[Assert\NotBlank(message: "Le commentaire ne peut pas être vide.")]
    private string $commentaire;

    // La note doit être comprise entre 1 et 5
    #[ORM\Column(type:"integer")]
    #[Assert\NotNull(message: "La note est obligatoire.")]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: "La note doit être entre {{ min }} et {{ max }}."// "La note doit être comprise entre {{ min }} et {{ max }}.")]
    )]
    private int $note;

    // La date de création, obligatoirement un objet DateTime
    #[ORM\Column(type:"datetime")]
    #[Assert\NotNull(message: "La date de création est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "La date doit être au format valide.")]
    private \DateTimeInterface $created_at;

    // --- Getters and Setters ---

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;
        return $this;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;
        return $this;
    }

    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getNote(): int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }
}
