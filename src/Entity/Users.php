<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Entity\Planning;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity]

class Users implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]

    private int $id;
    
    #[ORM\Column(type: "string", length: 50, nullable: true)]
   
    private string $username;
    
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    #[Assert\NotBlank(message: "L'adresse email est obligatoire.")]
    #[Assert\Email(message: "L'adresse email n'est pas valide.")]
    private string $email;
    
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    #[Assert\Length(
        min: 8,
        minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères."
    )]
    private ?string $password_hash = null;
    
    // Change the property declaration to allow null
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $is_active = false;  // Set default value to false

    // Update the getter to handle nullable value
    public function getIsActive(): bool
    {
        return $this->is_active ?? false;  // Return false if null
    }

    // Update the setter to handle nullable value
    public function setIsActive(?bool $is_active): self
    {
        $this->is_active = $is_active;
        return $this;
    }
    
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $role = 'USER';
    
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private string $service_name;
    
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private string $service_type;
    
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private string $official_id;
    
    #[ORM\Column(type: "text", nullable: true)]
    private string $documents;
    
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ? string $specialty=null    ;
    
    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $experience_years=null;
    
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private string $certifications;
    
    #[ORM\Column(type: "integer", nullable: true)]
    private int $security_question_id;
    
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private string $security_answer;
    
    #[ORM\Column(type: "string", length: 20, nullable: true)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^\+?[0-9]{7,20}$/",
        message: "Le numéro de téléphone n'est pas valide. Il doit contenir uniquement des chiffres (et éventuellement un + au début)."
    )]
    private ? string $tel=null;
    
    #[ORM\Column(type: "string", length: 150, nullable: true)]
    private ? string $image=null;
    
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    #[Assert\NotBlank(message: "La localisation est requise.")]
    private ? string $localisation=null;
    
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    #[Assert\NotBlank(message: "Le nom est requis.")]
    private string $nom;
    
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    #[Assert\NotBlank(message: "Le prénom est requis.")]
    private string $prenom;
// src/Entity/Users.php
#[ORM\Column(type: 'string', length: 255, nullable: true)]
private ?string $imageUrl = null; // Stocke l'URL Cloudinary

// Non mappé en BDD, utilisé pour le formulaire
private ?File $imageFile = null;

// Getters/Setters
public function getImageUrl(): ?string
{
    return $this->imageUrl;
}

public function setImageUrl(?string $imageUrl): self
{
    $this->imageUrl = $imageUrl;
    return $this;
}

public function getImageFile(): ?File
{
    return $this->imageFile;
}

public function setImageFile(?File $imageFile): self
{
    $this->imageFile = $imageFile;
    return $this;
}
#[ORM\Column(type: 'string', length: 6, nullable: true)]
private ?string $verificationCode = null;

public function getVerificationCode(): ?string
{
    return $this->verificationCode;
}

public function setVerificationCode(?string $verificationCode): self
{
    $this->verificationCode = $verificationCode;
    return $this;
}

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): self
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    // Remove or comment out these incorrect methods
    /*
    public function getPassword_hash()
    {
        return $this->password_hash;
    }

    public function setPassword_hash($value)
    {
        $this->password_hash = $value;
    }
    */


    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $value): self
    {
        $this->role = $value;
        return $this;
    }

    public function getServicename() :string
    {
        return $this->service_name;
    }

    public function setServicename($value) :self
    {
        $this->service_name = $value;
        return $this;
    }

    public function getServicetype()
    {
        return $this->service_type;
    }

    public function setServicetype($value)
    {
        $this->service_type = $value;
    }

    public function getOfficialid()
    {
        return $this->official_id;
    }

    public function setOfficialid($value)
    {
        $this->official_id = $value;
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function setDocuments($value)
    {
        $this->documents = $value;
    }

    public function getSpecialty()
    {
        return $this->specialty;
    }

    public function setSpecialty($value)
    {
        $this->specialty = $value;
    }

    public function getExperienceyears()
    {
        return $this->experience_years;
    }

    public function setExperienceyears($value)
    {
        $this->experience_years = $value;
    }

    public function getCertifications()
    {
        return $this->certifications;
    }

    public function setCertifications($value)
    {
        $this->certifications = $value;
    }

    public function getSecurityquestionid()
    {
        return $this->security_question_id;
    }

    public function setSecurityquestionid($value)
    {
        $this->security_question_id = $value;
    }

    public function getSecurityanswer()
    {
        return $this->security_answer;
    }

    public function setSecurityanswer($value)
    {
        $this->security_answer = $value;
    }

    public function getTel()
    {
        return $this->tel;
    }

    public function setTel($value)
    {
        $this->tel = $value;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($value)
    {
        $this->image = $value;
    }

    public function getLocalisation()
    {
        return $this->localisation;
    }

    public function setLocalisation($value)
    {
        $this->localisation = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($value)
    {
        $this->prenom = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_user", targetEntity: Cours::class)]
    private Collection $courss;

        public function getCourss(): Collection
        {
            return $this->courss;
        }
    

    #[ORM\OneToMany(mappedBy: "id_user", targetEntity: Participant::class)]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: "user_id", targetEntity: Profile::class)]
    private Collection $profiles;

        public function getProfiles(): Collection
        {
            return $this->profiles;
        }
    
        public function addProfile(Profile $profile): self
        {
            if (!$this->profiles->contains($profile)) {
                $this->profiles[] = $profile;
                $profile->setUser_id($this);
            }
    
            return $this;
        }
    
        public function removeProfile(Profile $profile): self
        {
            if ($this->profiles->removeElement($profile)) {
                // set the owning side to null (unless already changed)
                if ($profile->getUser_id() === $this) {
                    $profile->setUser_id(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_user", targetEntity: Planning::class)]
    private Collection $plannings;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function getRoles(): array
    {
        $roles = [$this->role];
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        return $this->password_hash;
    }

    // Optionally, if you want to set the password using setPassword:
    public function setPassword(string $password): self
    {
        $this->password_hash = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    // Add this method for backward compatibility
    public function getSalt(): ?string
    {
        return null;
    }

    // Add constructor to initialize collections
    public function __construct()
    {
        $this->courss = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->profiles = new ArrayCollection();
        $this->plannings = new ArrayCollection();
        $this->password_hash = null;
        $this->image=null;
        $this->localisation=null;
        $this->tel=null;

    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $resetToken = null;
    
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $passwordRequestedAt = null;
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getPasswordRequestedAt(): ?\DateTimeImmutable
    {
        return $this->passwordRequestedAt;
    }

    public function setPasswordRequestedAt(?\DateTimeImmutable $passwordRequestedAt): self
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }


// Getters et setters correspondants
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $facebookId = null;

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): self
    {
        $this->facebookId = $facebookId;
        return $this;
    }
}

