<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\GameMatchRepository;

#[ORM\Entity(repositoryClass: GameMatchRepository::class)]
#[ORM\Table(name: '`match`')] // Use backticks to escape the reserved keyword
class GameMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_match', type: 'integer')] // Explicitly map the primary key column
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: Tournoi::class, inversedBy: 'matchs')]
    #[ORM\JoinColumn(name: 'id_tournoi', referencedColumnName: 'id_tournoi')]
    private ?Tournoi $tournoi = null;

    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    public function setTournoi(?Tournoi $tournoi): self
    {
        $this->tournoi = $tournoi;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Equipe::class, inversedBy: 'matchs')]
    #[ORM\JoinColumn(name: 'id_equipe1', referencedColumnName: 'id_equipe', nullable: false)]
    private ?Equipe $equipe1 = null;

    public function getEquipe1(): ?Equipe
    {
        return $this->equipe1;
    }

    public function setEquipe1(?Equipe $equipe1): self
    {
        $this->equipe1 = $equipe1;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Equipe::class, inversedBy: 'matchs')]
    #[ORM\JoinColumn(name: 'id_equipe2', referencedColumnName: 'id_equipe', nullable: false)]
    private ?Equipe $equipe2 = null;

    public function getEquipe2(): ?Equipe
    {
        return $this->equipe2;
    }

    public function setEquipe2(?Equipe $equipe2): self
    {
        $this->equipe2 = $equipe2;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_match = null;

    public function getDate_match(): ?\DateTimeInterface
    {
        return $this->date_match;
    }

    public function setDate_match(\DateTimeInterface $date_match): self
    {
        $this->date_match = $date_match;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Terrain::class, inversedBy: 'matchs')]
    #[ORM\JoinColumn(name: 'id_terrain', referencedColumnName: 'id_terrain')]
    private ?Terrain $terrain = null;

    public function getTerrain(): ?Terrain
    {
        return $this->terrain;
    }

    public function setTerrain(?Terrain $terrain): self
    {
        $this->terrain = $terrain;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $score_equipe1 = null;

    public function getScore_equipe1(): ?int
    {
        return $this->score_equipe1;
    }

    public function setScore_equipe1(int $score_equipe1): self
    {
        $this->score_equipe1 = $score_equipe1;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $score_equipe2 = null;

    public function getScore_equipe2(): ?int
    {
        return $this->score_equipe2;
    }

    public function setScore_equipe2(int $score_equipe2): self
    {
        $this->score_equipe2 = $score_equipe2;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $statut_match = null;

    public function getStatut_match(): ?string
    {
        return $this->statut_match;
    }

    public function setStatut_match(string $statut_match): self
    {
        $this->statut_match = $statut_match;
        return $this;
    }

    public function getIdMatch(): ?int
    {
        return $this->id;
    }

    public function getDateMatch(): ?\DateTimeInterface
    {
        return $this->date_match;
    }

    public function setDateMatch(\DateTimeInterface $date_match): static
    {
        $this->date_match = $date_match;

        return $this;
    }

    public function getScoreEquipe1(): ?int
    {
        return $this->score_equipe1;
    }

    public function setScoreEquipe1(int $score_equipe1): static
    {
        $this->score_equipe1 = $score_equipe1;

        return $this;
    }

    public function getScoreEquipe2(): ?int
    {
        return $this->score_equipe2;
    }

    public function setScoreEquipe2(int $score_equipe2): static
    {
        $this->score_equipe2 = $score_equipe2;

        return $this;
    }

    public function getStatutMatch(): ?string
    {
        return $this->statut_match;
    }

    public function setStatutMatch(string $statut_match): static
    {
        $this->statut_match = $statut_match;

        return $this;
    }

}
