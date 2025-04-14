<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private int $id_cours;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le nom du cours est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ0-9\s]+$/",
        message: "Le nom du cours ne doit contenir que des lettres, des chiffres et des espaces."
    )]
    private string $Nom_Cours;

    #[ORM\Column(type: "string", length: 50)]
    #[Assert\NotBlank(message: "L'état du cours est obligatoire.")]
    #[Assert\Choice(
        choices: ["actif", "inactif"],
        message: "L'état du cours doit être soit 'actif' soit 'inactif'."
    )]
    private string $Etat_Cours;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(message: "La durée du cours est obligatoire.")]
    #[Assert\Positive(message: "La durée doit être un nombre positif.")]
    private int $Duree;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: "courss")]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Users $id_user;

    #[ORM\OneToMany(mappedBy: "id_cours", targetEntity: Cours_participant::class)]
    private Collection $cours_participants;

    #[ORM\OneToMany(mappedBy: "cours", targetEntity: Planning::class)]
    private Collection $plannings;

    public function __construct()
    {
        $this->cours_participants = new ArrayCollection();
        $this->plannings = new ArrayCollection();
    }

    public function getIdCours(): int
    {
        return $this->id_cours;
    }

    public function setIdCours(int $id_cours): self
    {
        $this->id_cours = $id_cours;
        return $this;
    }

    public function getNomCours(): string
    {
        return $this->Nom_Cours;
    }

    public function setNomCours(string $Nom_Cours): self
    {
        $this->Nom_Cours = $Nom_Cours;
        return $this;
    }

    public function getEtatCours(): string
    {
        return $this->Etat_Cours;
    }

    public function setEtatCours(string $Etat_Cours): self
    {
        $this->Etat_Cours = $Etat_Cours;
        return $this;
    }

    public function getDuree(): int
    {
        return $this->Duree;
    }

    public function setDuree(int $Duree): self
    {
        $this->Duree = $Duree;
        return $this;
    }

    public function getIdUser(): Users
    {
        return $this->id_user;
    }

    public function setIdUser(Users $id_user): self
    {
        $this->id_user = $id_user;
        return $this;
    }

    public function getCoursParticipants(): Collection
    {
        return $this->cours_participants;
    }

    public function addCoursParticipant(Cours_participant $cours_participant): self
    {
        if (!$this->cours_participants->contains($cours_participant)) {
            $this->cours_participants[] = $cours_participant;
            $cours_participant->setCours($this);
        }

        return $this;
    }

    public function removeCoursParticipant(Cours_participant $cours_participant): self
    {
        if ($this->cours_participants->removeElement($cours_participant)) {
            if ($cours_participant->getCours() === $this) {
                $cours_participant->setCours(null);
            }
        }

        return $this;
    }

    public function getPlannings(): Collection
    {
        return $this->plannings;
    }

    public function addPlanning(Planning $planning): self
    {
        if (!$this->plannings->contains($planning)) {
            $this->plannings[] = $planning;
            $planning->setCours($this);
        }

        return $this;
    }

    public function removePlanning(Planning $planning): self
    {
        if ($this->plannings->removeElement($planning)) {
            if ($planning->getCours() === $this) {
                $planning->setCours(null);
            }
        }

        return $this;
    }
}