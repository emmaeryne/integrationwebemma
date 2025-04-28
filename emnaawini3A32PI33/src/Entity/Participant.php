<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    private string $Nom;

    #[ORM\Column(type: "string", length: 50)]
    private string $Prenom;

    #[ORM\Column(type: "integer")]
    private int $Age;

    #[ORM\Column(type: "string", length: 255)]
    private string $adresse;

    #[ORM\Column(type: "string", length: 15)]
    private string $num_telephone;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: "participants")]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Users $id_user = null;

    #[ORM\OneToMany(mappedBy: "id_participant", targetEntity: Cours_participant::class)]
    private Collection $cours_participants;

    public function __construct()
    {
        $this->cours_participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;
        return $this;
    }

    public function getPrenom(): string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;
        return $this;
    }

    public function getAge(): int
    {
        return $this->Age;
    }

    public function setAge(int $Age): self
    {
        $this->Age = $Age;
        return $this;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getNumTelephone(): string
    {
        return $this->num_telephone;
    }

    public function setNumTelephone(string $num_telephone): self
    {
        $this->num_telephone = $num_telephone;
        return $this;
    }

    public function getIdUser(): ?Users
    {
        return $this->id_user;
    }

    public function setIdUser(?Users $id_user): self
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
            $cours_participant->setId_participant($this); // Fixed: setIdParticipant -> setId_participant
        }

        return $this;
    }

    public function removeCoursParticipant(Cours_participant $cours_participant): self
    {
        if ($this->cours_participants->removeElement($cours_participant)) {
            if ($cours_participant->getId_participant() === $this) { // Fixed: getIdParticipant -> getId_participant
                $cours_participant->setId_participant(null);
            }
        }

        return $this;
    }
}