<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\GameMatch;

use App\Repository\TerrainRepository;

#[ORM\Entity(repositoryClass: TerrainRepository::class)]
#[ORM\Table(name: 'terrain')]
class Terrain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_terrain = null;

    public function getId_terrain(): ?int
    {
        return $this->id_terrain;
    }

    public function getId(): ?int
    {
        return $this->id_terrain;
    }

    public function setId_terrain(int $id_terrain): self
    {
        $this->id_terrain = $id_terrain;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: 'App\Entity\GameMatch', mappedBy: 'terrain')]
    private Collection $matches;

    public function __construct()
    {
        $this->matches = new ArrayCollection();
    }

    /**
     * @return Collection<int, GameMatch>
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    public function addMatch(GameMatch $match): self
    {
        if (!$this->matches->contains($match)) {
            $this->matches->add($match);
            $match->setTerrain($this); // Ensure the inverse side is set
        }
        return $this;
    }

    public function removeMatch(GameMatch $match): self
    {
        if ($this->matches->removeElement($match)) {
            // Set the owning side to null (unless already changed)
            if ($match->getTerrain() === $this) {
                $match->setTerrain(null);
            }
        }
        return $this;
    }

    public function getIdTerrain(): ?int
    {
        return $this->id_terrain;
    }

}
