<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
class OrderDetail
{

    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "La commande est obligatoire")]
    private $myOrder;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom du produit ne peut pas dépasser {{ limit }} caractères"
    )]
    private $product;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(message: "La quantité est obligatoire")]
    #[Assert\Positive(message: "La quantité doit être positive")]
    private $quantity;

    #[ORM\Column(type: 'float')]
    #[Assert\NotNull(message: "Le prix est obligatoire")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    private $price;

    #[ORM\Column(type: 'float')]
    #[Assert\NotNull(message: "Le total est obligatoire")]
    #[Assert\PositiveOrZero(message: "Le total doit être positif ou zéro")]
    private $total;

    public function __toString()
    {
        return $this->getProduct() .' X '.$this->getQuantity();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMyOrder(): ?Order
    {
        return $this->myOrder;
    }

    public function setMyOrder(?Order $myOrder): self
    {
        $this->myOrder = $myOrder;

        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
