<?php

namespace App\Entity;

use App\Entity\Produit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[UniqueEntity(fields: ["nomcategorie"], message: "Cette catégorie existe déjà.")]
class CategorieProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string", length:255)]
    #[Assert\NotBlank(message: "Le nom de la catégorie est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de la catégorie ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $nomcategorie;

    #[ORM\Column(type:"blob", nullable:true)]
    // Pour un upload d'image, si besoin, vous pourriez utiliser la contrainte File en cas de gestion via un formulaire.
    private $image;

    #[ORM\Column(type:"string", length:255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $description;

    #[ORM\OneToMany(mappedBy: "Categorie", targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(string $nomcategorie): static
    {
        $this->nomcategorie = $nomcategorie;
        return $this;
    }
   
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Retourne l'image sous forme de Data URI si elle est disponible.
     */
    public function getImage(): ?string
    {
        if (is_resource($this->image)) {
            $binary = stream_get_contents($this->image);
            $base64 = base64_encode($binary);

            $finfo = finfo_open();
            $mimeType = finfo_buffer($finfo, $binary, FILEINFO_MIME_TYPE);
            finfo_close($finfo);

            return 'data:' . $mimeType . ';base64,' . $base64;
        }
        return null;
    }

    public function setImage($image): static
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCategorie($this);
        }
        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }
        return $this;
    }
}
