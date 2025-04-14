<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Cours_participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: "cours_participants")]
    #[ORM\JoinColumn(name: 'id_participant', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Participant $id_participant = null;

    #[ORM\ManyToOne(targetEntity: Cours::class, inversedBy: "cours_participants")]
    #[ORM\JoinColumn(name: 'id_cours', referencedColumnName: 'id_cours', onDelete: 'CASCADE')]
    private ?Cours $id_cours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getId_participant(): ?Participant
    {
        return $this->id_participant;
    }

    public function setId_participant(?Participant $id_participant): self
    {
        $this->id_participant = $id_participant;
        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->id_cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->id_cours = $cours;
        return $this;
    }
}