<?php
namespace App\Entity;

use App\Entity\Equipe;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'statistiques_equipe')]
class StatistiquesEquipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Equipe::class)]
    #[ORM\JoinColumn(name: 'id_equipe', referencedColumnName: 'id_equipe', nullable: false)]
    private ?Equipe $equipe = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $matchs_joues = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $victoires = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $defaites = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $nuls = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $buts_marques = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $buts_encaisses = 0;

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(Equipe $equipe): self
    {
        $this->equipe = $equipe;
        return $this;
    }

    public function getMatchsJoues(): int
    {
        return $this->matchs_joues;
    }

    public function setMatchsJoues(int $matchs_joues): self
    {
        $this->matchs_joues = $matchs_joues;
        return $this;
    }

    public function getVictoires(): int
    {
        return $this->victoires;
    }

    public function setVictoires(int $victoires): self
    {
        $this->victoires = $victoires;
        return $this;
    }

    public function getDefaites(): int
    {
        return $this->defaites;
    }

    public function setDefaites(int $defaites): self
    {
        $this->defaites = $defaites;
        return $this;
    }

    public function getNuls(): int
    {
        return $this->nuls;
    }

    public function setNuls(int $nuls): self
    {
        $this->nuls = $nuls;
        return $this;
    }

    public function getButsMarques(): int
    {
        return $this->buts_marques;
    }

    public function setButsMarques(int $buts_marques): self
    {
        $this->buts_marques = $buts_marques;
        return $this;
    }

    public function getButsEncaisses(): int
    {
        return $this->buts_encaisses;
    }

    public function setButsEncaisses(int $buts_encaisses): self
    {
        $this->buts_encaisses = $buts_encaisses;
        return $this;
    }

    public function incrementMatchsJoues(): void
    {
        $this->matchs_joues++;
    }

    public function incrementVictoires(): void
    {
        $this->victoires++;
    }

    public function incrementDefaites(): void
    {
        $this->defaites++;
    }

    public function incrementMatchsNuls(): void
    {
        $this->nuls++;
    }
}
