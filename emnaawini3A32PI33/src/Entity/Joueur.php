<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\JoueurRepository;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
#[ORM\Table(name: 'joueur')]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_joueur = null;

    public function getId_joueur(): ?int
    {
        return $this->id_joueur;
    }

    public function setId_joueur(int $id_joueur): self
    {
        $this->id_joueur = $id_joueur;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nom_joueur = null;

    public function getNom_joueur(): ?string
    {
        return $this->nom_joueur;
    }

    public function setNom_joueur(string $nom_joueur): self
    {
        $this->nom_joueur = $nom_joueur;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Equipe::class, inversedBy: 'joueurs')]
    #[ORM\JoinColumn(name: 'id_equipe', referencedColumnName: 'id_equipe')]
    private ?Equipe $equipe = null;

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): self
    {
        $this->equipe = $equipe;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $cin = null;

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $url_photo = null;

    public function getUrl_photo(): ?string
    {
        return $this->url_photo;
    }

    public function setUrl_photo(string $url_photo): self
    {
        $this->url_photo = $url_photo;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id_user = null;

    public function getId_user(): ?int
    {
        return $this->id_user;
    }

    public function setId_user(int $id_user): self
    {
        $this->id_user = $id_user;
        return $this;
    }

    public function getIdJoueur(): ?int
    {
        return $this->id_joueur;
    }

    public function getNomJoueur(): ?string
    {
        return $this->nom_joueur;
    }

    public function setNomJoueur(string $nom_joueur): static
    {
        $this->nom_joueur = $nom_joueur;

        return $this;
    }

    public function getUrlPhoto(): ?string
    {
        return $this->url_photo;
    }

    public function setUrlPhoto(string $url_photo): static
    {
        $this->url_photo = $url_photo;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

}
