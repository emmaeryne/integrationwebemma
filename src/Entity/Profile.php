<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Users;

#[ORM\Entity]
class Profile
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id;

        #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: "profiles")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Users $user_id;

    #[ORM\Column(type: "string", length: 50)]
    private string $first_name;

    #[ORM\Column(type: "string", length: 50)]
    private string $last_name;

    #[ORM\Column(type: "string", length: 30)]
    private string $date_of_birth;

    #[ORM\Column(type: "string", length: 255)]
    private string $profile_picture;

    #[ORM\Column(type: "string", length: 150)]
    private string $bio;

    #[ORM\Column(type: "string", length: 100)]
    private string $location;

    #[ORM\Column(type: "string", length: 20)]
    private string $phone_number;

    #[ORM\Column(type: "string", length: 255)]
    private string $website;

    #[ORM\Column(type: "string", length: 255)]
    private string $social_media_links;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($value)
    {
        $this->user_id = $value;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function setFirst_name($value)
    {
        $this->first_name = $value;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function setLast_name($value)
    {
        $this->last_name = $value;
    }

    public function getDate_of_birth()
    {
        return $this->date_of_birth;
    }

    public function setDate_of_birth($value)
    {
        $this->date_of_birth = $value;
    }

    public function getProfile_picture()
    {
        return $this->profile_picture;
    }

    public function setProfile_picture($value)
    {
        $this->profile_picture = $value;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function setBio($value)
    {
        $this->bio = $value;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($value)
    {
        $this->location = $value;
    }

    public function getPhone_number()
    {
        return $this->phone_number;
    }

    public function setPhone_number($value)
    {
        $this->phone_number = $value;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($value)
    {
        $this->website = $value;
    }

    public function getSocial_media_links()
    {
        return $this->social_media_links;
    }

    public function setSocial_media_links($value)
    {
        $this->social_media_links = $value;
    }
}
