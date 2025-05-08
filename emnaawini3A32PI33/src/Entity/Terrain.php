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

    public function setId_terrain(int $id_terrain): self
    {
        $this->id_terrain = $id_terrain;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: 'App\Entity\GameMatch', mappedBy: 'terrain')]
    private Collection $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
    }

    /**
     * @return Collection<int, GameMatch>
     */
    public function getMatchs(): Collection
    {
        if (!$this->matchs instanceof Collection) {
            $this->matchs = new ArrayCollection();
        }
        return $this->matchs;
    }

    public function addMatch(GameMatch $matchEntity): self
    {
        if (!$this->getMatchs()->contains($matchEntity)) {
            $this->getMatchs()->add($matchEntity);
        }
        return $this;
    }

    public function removeMatch(GameMatch $matchEntity): self
    {
        $this->getMatchs()->removeElement($matchEntity);
        return $this;
    }

    public function getIdTerrain(): ?int
    {
        return $this->id_terrain;
    }

}
