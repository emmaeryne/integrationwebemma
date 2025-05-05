<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private int $idPlanning;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le type d'activité est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s]+$/",
        message: "Le type d'activité ne doit contenir que des lettres et des espaces."
    )]
    private string $typeActivite;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "La date est obligatoire.")]
    #[Assert\GreaterThanOrEqual(
        value: "today",
        message: "La date doit être une date future."
    )]
    private \DateTimeInterface $datePlanning;

    #[ORM\Column(type: "string", length: 50)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    #[Assert\Choice(
        choices: ["actif", "inactif"],
        message: "Le statut doit être soit 'actif' soit 'inactif'."
    )]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Cours::class, inversedBy: "plannings")]
    #[ORM\JoinColumn(name: "cours", referencedColumnName: "id_cours", nullable: true, onDelete: "CASCADE")]
    private ?Cours $cours = null;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: "plannings")]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id", onDelete: "CASCADE")]
    private Users $user;

    // Getters and Setters

    public function getIdPlanning(): int
    {
        return $this->idPlanning;
    }

    public function setIdPlanning(int $idPlanning): self
    {
        $this->idPlanning = $idPlanning;
        return $this;
    }

    public function getTypeActivite(): string
    {
        return $this->typeActivite;
    }

    public function setTypeActivite(string $typeActivite): self
    {
        $this->typeActivite = $typeActivite;
        return $this;
    }

    public function getDatePlanning(): \DateTimeInterface
    {
        return $this->datePlanning;
    }

    public function setDatePlanning(\DateTimeInterface $datePlanning): self
    {
        $this->datePlanning = $datePlanning;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;
        return $this;
    }

    public function getUser(): Users
    {
        return $this->user;
    }

    public function setUser(Users $user): self
    {
        $this->user = $user;
        return $this;
    }
}