<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Planning;

#[ORM\Entity]
class Users
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 50)]
    private string $username;

    #[ORM\Column(type: "string", length: 100)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password_hash;

    #[ORM\Column(type: "boolean")]
    private bool $is_active;

    #[ORM\Column(type: "string", length: 255)]
    private string $role;

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

    public function getPassword_hash()
    {
        return $this->password_hash;
    }

    public function setPassword_hash($value)
    {
        $this->password_hash = $value;
    }

    public function getIs_active()
    {
        return $this->is_active;
    }

    public function setIs_active($value)
    {
        $this->is_active = $value;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($value)
    {
        $this->role = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_user", targetEntity: Cours::class)]
    private Collection $courss;

        public function getCourss(): Collection
        {
            return $this->courss;
        }
    
        public function addCours(Cours $cours): self
        {
            if (!$this->courss->contains($cours)) {
                $this->courss[] = $cours;
                $cours->setId_user($this);
            }
    
            return $this;
        }
    
        public function removeCours(Cours $cours): self
        {
            if ($this->courss->removeElement($cours)) {
                // set the owning side to null (unless already changed)
                if ($cours->getId_user() === $this) {
                    $cours->setId_user(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_user", targetEntity: Participant::class)]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: "id_user", targetEntity: Planning::class)]
    private Collection $plannings;
}
