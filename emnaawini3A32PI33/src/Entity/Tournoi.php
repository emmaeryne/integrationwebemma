<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TournoiRepository;

#[ORM\Entity(repositoryClass: TournoiRepository::class)]
#[ORM\Table(name: 'tournoi')]
class Tournoi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idTournoi = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nomTournoi = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $typeTournoi = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateTournoi = null;

    #[ORM\Column(type: 'text')]
    private ?string $descriptionTournoi = null;

    public function getIdTournoi(): ?int
    {
        return $this->idTournoi;
    }

    public function getNomTournoi(): ?string
    {
        return $this->nomTournoi;
    }

    public function setNomTournoi(string $nomTournoi): static
    {
        $this->nomTournoi = $nomTournoi;

        return $this;
    }

    public function getTypeTournoi(): ?string
    {
        return $this->typeTournoi;
    }

    public function setTypeTournoi(string $typeTournoi): static
    {
        $this->typeTournoi = $typeTournoi;

        return $this;
    }

    public function getDateTournoi(): ?\DateTimeInterface
    {
        return $this->dateTournoi;
    }

    public function setDateTournoi(\DateTimeInterface $dateTournoi): static
    {
        $this->dateTournoi = $dateTournoi;

        return $this;
    }

    public function getDescriptionTournoi(): ?string
    {
        return $this->descriptionTournoi;
    }

    public function setDescriptionTournoi(string $descriptionTournoi): static
    {
        $this->descriptionTournoi = $descriptionTournoi;

        return $this;
    }

    #[ORM\OneToMany(targetEntity: 'App\Entity\GameMatch', mappedBy: 'tournoi')]
    private Collection $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
    }

    /**
     * @return Collection<int, Match>
     */
    public function getMatchs(): Collection
    {
        if (!$this->matchs instanceof Collection) {
            $this->matchs = new ArrayCollection();
        }
        return $this->matchs;
    }

    public function addMatch(GameMatch $match): self
    {
        if (!$this->getMatchs()->contains($match)) {
            $this->getMatchs()->add($match);
        }
        return $this;
    }

    public function removeMatch(GameMatch $match): self
    {
        $this->getMatchs()->removeElement($match);
        return $this;
    }

}
