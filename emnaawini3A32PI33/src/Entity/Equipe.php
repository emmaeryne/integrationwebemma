<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\EquipeRepository;
use App\Entity\GameMatch;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[ORM\Table(name: 'equipe')]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_equipe = null;

    public function getId_equipe(): ?int
    {
        return $this->id_equipe;
    }

    public function setId_equipe(int $id_equipe): self
    {
        $this->id_equipe = $id_equipe;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom_equipe = null;

    public function getNom_equipe(): ?string
    {
        return $this->nom_equipe;
    }

    public function setNom_equipe(string $nom_equipe): self
    {
        $this->nom_equipe = $nom_equipe;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->nom_equipe;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type_equipe = null;

    public function getType_equipe(): ?string
    {
        return $this->type_equipe;
    }

    public function setType_equipe(string $type_equipe): self
    {
        $this->type_equipe = $type_equipe;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Joueur::class, mappedBy: 'equipe')]
    private Collection $joueurs;

    /**
     * @return Collection<int, Joueur>
     */
    public function getJoueurs(): Collection
    {
        if (!$this->joueurs instanceof Collection) {
            $this->joueurs = new ArrayCollection();
        }
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): self
    {
        if (!$this->getJoueurs()->contains($joueur)) {
            $this->getJoueurs()->add($joueur);
        }
        return $this;
    }

    public function removeJoueur(Joueur $joueur): self
    {
        $this->getJoueurs()->removeElement($joueur);
        return $this;
    }
    
    
    #[ORM\OneToMany(targetEntity: GameMatch::class, mappedBy: 'equipe')]
    private Collection $matchs;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
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

    public function addGameMatch(GameMatch $gameMatch): self
    {
        if (!$this->getMatchs()->contains($gameMatch)) {
            $this->getMatchs()->add($gameMatch);
        }
        return $this;
    }

    public function removeGameMatch(GameMatch $gameMatch): self
    {
        $this->getMatchs()->removeElement($gameMatch);
        return $this;
    }

    public function getIdEquipe(): ?int
    {
        return $this->id_equipe;
    }

    public function getId(): ?int
    {
        return $this->id_equipe;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nom_equipe;
    }

    public function setNomEquipe(string $nom_equipe): static
    {
        $this->nom_equipe = $nom_equipe;

        return $this;
    }

    public function getTypeEquipe(): ?string
    {
        return $this->type_equipe;
    }

    public function setTypeEquipe(string $type_equipe): static
    {
        $this->type_equipe = $type_equipe;

        return $this;
    }

    public function addMatch(GameMatch $match): static
    {
        if (!$this->matchs->contains($match)) {
            $this->matchs->add($match);
            $match->setEquipe($this);
        }

        return $this;
    }

    public function removeMatch(GameMatch $match): static
    {
        if ($this->matchs->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getEquipe() === $this) {
                $match->setEquipe(null);
            }
        }

        return $this;
    }

}
