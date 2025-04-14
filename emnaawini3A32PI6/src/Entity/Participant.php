<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Users;
use Doctrine\Common\Collections\Collection;
use App\Entity\Cours_participant;

#[ORM\Entity]
class Participant
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 50)]
    private string $Nom;

    #[ORM\Column(type: "string", length: 50)]
    private string $Prenom;

    #[ORM\Column(type: "integer")]
    private int $Age;

    #[ORM\Column(type: "string", length: 255)]
    private string $adresse;

    #[ORM\Column(type: "string", length: 15)]
    private string $num_telephone;

        #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: "participants")]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Users $id_user;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getNom()
    {
        return $this->Nom;
    }

    public function setNom($value)
    {
        $this->Nom = $value;
    }

    public function getPrenom()
    {
        return $this->Prenom;
    }

    public function setPrenom($value)
    {
        $this->Prenom = $value;
    }

    public function getAge()
    {
        return $this->Age;
    }

    public function setAge($value)
    {
        $this->Age = $value;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setAdresse($value)
    {
        $this->adresse = $value;
    }

    public function getNum_telephone()
    {
        return $this->num_telephone;
    }

    public function setNum_telephone($value)
    {
        $this->num_telephone = $value;
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($value)
    {
        $this->id_user = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_participant", targetEntity: Cours_participant::class)]
    private Collection $cours_participants;

        public function getCours_participants(): Collection
        {
            return $this->cours_participants;
        }
    
        public function addCours_participant(Cours_participant $cours_participant): self
        {
            if (!$this->cours_participants->contains($cours_participant)) {
                $this->cours_participants[] = $cours_participant;
                $cours_participant->setId_participant($this);
            }
    
            return $this;
        }
    
        public function removeCours_participant(Cours_participant $cours_participant): self
        {
            if ($this->cours_participants->removeElement($cours_participant)) {
                // set the owning side to null (unless already changed)
                if ($cours_participant->getId_participant() === $this) {
                    $cours_participant->setId_participant(null);
                }
            }
    
            return $this;
        }
}
