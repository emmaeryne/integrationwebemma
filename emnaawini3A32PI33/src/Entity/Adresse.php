<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Un nom pour cette adresse est requis")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom doit faire au moins 2 caractères",
        maxMessage: "Le nom ne peut dépasser 255 caractères"
    )]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    #[Assert\Length(min: 2, max: 50)]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "Seules les lettres et certains caractères spéciaux sont autorisés"
    )]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le nom de famille est obligatoire")]
    #[Assert\Length(min: 2, max: 50)]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "Seules les lettres et certains caractères spéciaux sont autorisés"
    )]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private $company;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire")]
    #[Assert\Length(min: 5, max: 255)]
    private $adress;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le code postal est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[A-Za-z0-9\s\-]+$/",
        message: "Format de code postal invalide"
    )]
    private $postal;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "La ville est obligatoire")]
    #[Assert\Length(min: 2, max: 100)]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ\s\-']+$/",
        message: "Nom de ville invalide"
    )]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le pays est obligatoire")]
    private $country;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire")]
    #[Assert\Regex(
        pattern: "/^\+?[0-9\s\-\(\)]{8,20}$/",
        message: "Format international requis (ex: +33 6 12 34 56 78)"
    )]
    private $phone;

    public function __toString()
    {
        return $this->getAdress().' '.$this->getCity().' '.$this->getPostal().' '.$this->getCountry();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostal(): ?string
    {
        return $this->postal;
    }

    public function setPostal(string $postal): self
    {
        $this->postal = $postal;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
